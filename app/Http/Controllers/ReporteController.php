<?php

namespace App\Http\Controllers;

use App\Models\detalleVenta;
use App\Models\venta;
use App\Models\Empleado;
use App\Models\detalleAlmacen;
use App\Models\producto;
use App\Models\almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;


class ReporteController extends Controller
{

    public function index()
    {
        $tabs = [
            [
                'id' => 'productos-cantidad',
                'title' => 'Productos (Cantidad)',
                'icon' => 'bi-trophy',
                'action' => 'cargarProductosCantidad'
            ],
            [
                'id' => 'productos-monto',
                'title' => 'Productos (Monto)',
                'icon' => 'bi-currency-dollar',
                'action' => 'cargarProductosMonto'
            ],
            [
                'id' => 'almacenes',
                'title' => 'Almacenes',
                'icon' => 'bi-boxes',
                'action' => 'cargarDistribucionAlmacenes'
            ],
            [
                'id' => 'empleados',
                'title' => 'Empleados',
                'icon' => 'bi-people',
                'action' => 'cargarEmpleadosTop'
            ],
            [
                'id' => 'vencimientos',
                'title' => 'Vencimientos',
                'icon' => 'bi-clock-history',
                'action' => 'cargarProductosVencimientoSimple'
            ]
        ];
        
        return view('reportes.index', compact('tabs'));
    }   

    public function productosMasVendidosPorCantidad(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $limit = $request->limit ?? 15;

        $productos = DetalleVenta::select(
                'productos.nombrePr',
                'productos.id_producto',
                DB::raw('SUM(detalle_ventas.cantidadDv) as total_cantidad'),
                DB::raw('SUM(detalle_ventas.cantidadDv * detalle_ventas.precioDv) as total_monto')
            )
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->whereBetween('ventas.fechaVe', [$request->fecha_inicio, $request->fecha_fin])
            ->groupBy('productos.id_producto', 'productos.nombrePr')
            ->orderByDesc('total_cantidad')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'productos' => $productos->items(),
            'pagination' => [
                'total' => $productos->total(),
                'per_page' => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page' => $productos->lastPage()
            ]
        ]);
    }

    /**
     * Productos más vendidos por monto en un rango de fechas
     */
    public function productosMasVendidosPorMonto(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $productos = DetalleVenta::select(
                'productos.nombrePr',
                'productos.id_producto',
                DB::raw('SUM(detalle_ventas.cantidadDv) as total_cantidad'),
                DB::raw('SUM(detalle_ventas.cantidadDv * detalle_ventas.precioDv) as total_monto')
            )
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->whereBetween('ventas.fechaVe', [$request->fecha_inicio, $request->fecha_fin])
            ->groupBy('productos.id_producto', 'productos.nombrePr')
            ->orderByDesc('total_monto')
            ->get();

        return response()->json([
            'success' => true,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'productos' => $productos
        ]);
    }

    /**
     * Distribución de productos en almacenes (gráfico de torta)
     */
    public function productosEnAlmacenes()
    {
        $distribucion = DetalleAlmacen::select(
                'almacens.nombreAl',
                'almacens.id_almacen',
                DB::raw('SUM(detalle_almacens.stock) as total_stock')
            )
            ->join('almacens', 'detalle_almacens.id_almacen', '=', 'almacens.id_almacen')
            ->groupBy('almacens.id_almacen', 'almacens.nombreAl')
            ->get();

        // Calcular porcentajes
        $totalStock = $distribucion->sum('total_stock');
        $distribucion = $distribucion->map(function ($item) use ($totalStock) {
            $item->porcentaje = $totalStock > 0 ? round(($item->total_stock / $totalStock) * 100, 2) : 0;
            return $item;
        });

        return response()->json([
            'success' => true,
            'total_stock' => $totalStock,
            'distribucion' => $distribucion
        ]);
    }

    /**
     * Empleado con más ventas en un período
     */
    public function empleadoConMasVentas(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $query = Venta::select(
                'empleados.id_empleado',
                'empleados.nombreEm',
                'empleados.apellidosEm',
                DB::raw('COUNT(ventas.id_venta) as total_ventas'),
                DB::raw('SUM(ventas.montoTotalVe) as total_monto_ventas')
            )
            ->join('empleados', 'ventas.id_empleado', '=', 'empleados.id_empleado')
            ->groupBy('empleados.id_empleado', 'empleados.nombreEm', 'empleados.apellidosEm')
            ->orderByDesc('total_ventas');

        // Aplicar filtro de fechas si se proporcionan
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('ventas.fechaVe', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $empleados = $query->get();

        return response()->json([
            'success' => true,
            'fecha_inicio' => $request->fecha_inicio ?? 'Todos los tiempos',
            'fecha_fin' => $request->fecha_fin ?? 'Todos los tiempos',
            'empleados' => $empleados
        ]);
    }

    /**
     * Reporte completo con todos los datos
     */
    public function reporteCompleto(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicio = $request->fecha_inicio ?? Carbon::now()->subMonth()->format('Y-m-d');
        $fechaFin = $request->fecha_fin ?? Carbon::now()->format('Y-m-d');

        $productosCantidad = $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin);
        $productosMonto = $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin);
        $distribucionAlmacenes = $this->getDistribucionAlmacenes();
        $empleadosTop = $this->getEmpleadosTop($fechaInicio, $fechaFin);

        return response()->json([
            'success' => true,
            'periodo' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ],
            'productos_mas_vendidos_cantidad' => $productosCantidad,
            'productos_mas_vendidos_monto' => $productosMonto,
            'distribucion_almacenes' => $distribucionAlmacenes,
            'empleados_top' => $empleadosTop
        ]);
    }

    /**
     * Productos por vencer - Versión simple y minimalista
     */
   public function productosPorVencerSimple(Request $request)
{
    try {
        \Log::info('Iniciando productosPorVencerSimple');

        $request->validate([
            'dias_limite' => 'nullable|integer|min:1|max:365',
        ]);

        $diasLimite = 365; // Ver todo el año próximo
        $fechaActual = Carbon::now();
        $fechaLimite = $fechaActual->copy()->addDays($diasLimite);
        
        \Log::info('Fechas calculadas', [
            'dias_limite' => $diasLimite,
            'fecha_actual' => $fechaActual->format('Y-m-d'),
            'fecha_limite' => $fechaLimite->format('Y-m-d')
        ]);

        // ===== SOLUCIÓN TEMPORAL: QUITAR LA RELACIÓN =====
        // Productos próximos a vencer (sin relación)
        $productosProximos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '>', $fechaActual)
            ->where('fechaVencimiento', '<=', $fechaLimite)
            ->orderBy('fechaVencimiento')
            ->get()
            ->map(function ($producto) use ($fechaActual) {
                $producto->dias_restantes = $fechaActual->diffInDays(Carbon::parse($producto->fechaVencimiento), false);
                $producto->categoria = null; // O un valor por defecto
                return $producto;
            });
        
        // Productos vencidos (últimos 90 días) - sin relación
        $productosVencidos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '<=', $fechaActual)
            ->where('fechaVencimiento', '>=', $fechaActual->copy()->subDays(90))
            ->orderBy('fechaVencimiento', 'desc')
            ->get()
            ->map(function ($producto) use ($fechaActual) {
                $producto->dias_vencido = $fechaActual->diffInDays(Carbon::parse($producto->fechaVencimiento));
                $producto->categoria = null; // O un valor por defecto
                return $producto;
            });

        \Log::info('Resultados obtenidos', [
            'total_proximos' => $productosProximos->count(),
            'total_vencidos' => $productosVencidos->count()
        ]);

        return response()->json([
            'success' => true,
            'productos_proximos' => $productosProximos,
            'productos_vencidos' => $productosVencidos,
            'total_proximos' => $productosProximos->count(),
            'total_vencidos' => $productosVencidos->count(),
            'dias_limite' => $diasLimite,
            'fecha_actual' => $fechaActual->format('Y-m-d'),
            'fecha_limite' => $fechaLimite->format('Y-m-d'),
            'mensaje' => 'Datos cargados correctamente'
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en productosPorVencerSimple', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'productos_proximos' => [],
            'productos_vencidos' => [],
            'total_proximos' => 0,
            'total_vencidos' => 0
        ], 500);
    }
}


    // Métodos privados para reutilizar lógica
    private function getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin)
    {
        return DetalleVenta::select(
                'productos.nombrePr',
                'productos.id_producto',
                DB::raw('SUM(detalle_ventas.cantidadDv) as total_cantidad'),
                DB::raw('SUM(detalle_ventas.cantidadDv * detalle_ventas.precioDv) as total_monto')
            )
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->whereBetween('ventas.fechaVe', [$fechaInicio, $fechaFin])
            ->groupBy('productos.id_producto', 'productos.nombrePr')
            ->orderByDesc('total_cantidad')
            ->limit(10)
            ->get();
    }

    private function getProductosMasVendidosPorMonto($fechaInicio, $fechaFin)
    {
        return DetalleVenta::select(
                'productos.nombrePr',
                'productos.id_producto',
                DB::raw('SUM(detalle_ventas.cantidadDv) as total_cantidad'),
                DB::raw('SUM(detalle_ventas.cantidadDv * detalle_ventas.precioDv) as total_monto')
            )
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->whereBetween('ventas.fechaVe', [$fechaInicio, $fechaFin])
            ->groupBy('productos.id_producto', 'productos.nombrePr')
            ->orderByDesc('total_monto')
            ->limit(10)
            ->get();
    }

    private function getDistribucionAlmacenes()
    {
        $distribucion = DetalleAlmacen::select(
                'almacens.nombreAl',
                'almacens.id_almacen',
                DB::raw('SUM(detalle_almacens.stock) as total_stock')
            )
            ->join('almacens', 'detalle_almacens.id_almacen', '=', 'almacens.id_almacen')
            ->groupBy('almacens.id_almacen', 'almacens.nombreAl')
            ->get();

        $totalStock = $distribucion->sum('total_stock');
        
        return $distribucion->map(function ($item) use ($totalStock) {
            $item->porcentaje = $totalStock > 0 ? round(($item->total_stock / $totalStock) * 100, 2) : 0;
            return $item;
        });
    }

    private function getEmpleadosTop($fechaInicio, $fechaFin)
    {
        return Venta::select(
                'empleados.id_empleado',
                'empleados.nombreEm',
                'empleados.apellidosEm',
                DB::raw('COUNT(ventas.id_venta) as total_ventas'),
                DB::raw('SUM(ventas.montoTotalVe) as total_monto_ventas')
            )
            ->join('empleados', 'ventas.id_empleado', '=', 'empleados.id_empleado')
            ->whereBetween('ventas.fechaVe', [$fechaInicio, $fechaFin])
            ->groupBy('empleados.id_empleado', 'empleados.nombreEm', 'empleados.apellidosEm')
            ->orderByDesc('total_ventas')
            ->limit(5)
            ->get();
    }

private function getProductosPorVencerPDF($diasLimite = null) // <-- Hacemos opcional
{
    // Forzar siempre 365 días
    $diasLimite = 365;
    
    $fechaActual = Carbon::now();
    $fechaLimite = $fechaActual->copy()->addDays($diasLimite);
    
    // Productos próximos a vencer - 365 DÍAS
    $productosProximos = Producto::whereNotNull('fechaVencimiento')
        ->where('fechaVencimiento', '>', $fechaActual)
        ->where('fechaVencimiento', '<=', $fechaLimite)
        ->orderBy('fechaVencimiento')
        ->get()
        ->map(function ($producto) use ($fechaActual) {
            $fechaVencimiento = Carbon::parse($producto->fechaVencimiento);
            $producto->dias_restantes = (int) $fechaActual->diffInDays($fechaVencimiento, false);
            $producto->fecha_formateada = $fechaVencimiento->format('d/m/Y');
            return $producto;
        });
        $diasLimite = 'Todos (sin límite)';
        $fechaLimite = 'Sin fecha límite';
    
    
    // Productos vencidos (últimos 90 días)
    $productosVencidos = Producto::whereNotNull('fechaVencimiento')
        ->where('fechaVencimiento', '<=', $fechaActual)
        ->where('fechaVencimiento', '>=', $fechaActual->copy()->subDays(90))
        ->orderBy('fechaVencimiento', 'desc')
        ->get()
        ->map(function ($producto) use ($fechaActual) {
            $fechaVencimiento = Carbon::parse($producto->fechaVencimiento);
            $producto->dias_vencido = (int) $fechaActual->diffInDays($fechaVencimiento);
            $producto->fecha_formateada = $fechaVencimiento->format('d/m/Y');
            return $producto;
        });

    return [
        'productos_proximos' => $productosProximos,
        'productos_vencidos' => $productosVencidos,
        'total_proximos' => $productosProximos->count(),
        'total_vencidos' => $productosVencidos->count(),
        'dias_limite' => $diasLimite,
        'fecha_actual' => $fechaActual->format('Y-m-d'),
        'fecha_limite' => $fechaLimite
    ];
}

    /**
     * Exportar reporte a PDF
     */
     public function exportarPDF(Request $request, $tipo)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'dias_limite' => 'nullable|integer|min:1|max:365',
        ]);

        $fechaInicio = $request->fecha_inicio ?? Carbon::now()->subMonth()->format('Y-m-d');
        $fechaFin = $request->fecha_fin ?? Carbon::now()->format('Y-m-d');
        $diasLimite = $request->dias_limite ?? 30;
        
        $titulo = '';
        $vista = '';
        $data = [];

        switch ($tipo) {
            case 'productos-cantidad':
                $data = $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin);
                $titulo = 'Productos Más Vendidos (Cantidad)';
                $vista = 'reportes.pdf.productos';
                break;
                
            case 'productos-monto':
                $data = $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin);
                $titulo = 'Productos Más Vendidos (Monto)';
                $vista = 'reportes.pdf.productos';
                break;
                
            case 'almacenes':
                $data = $this->getDistribucionAlmacenes();
                $titulo = 'Distribución en Almacenes';
                $vista = 'reportes.pdf.almacenes';
                break;
                
            case 'empleados':
                $data = $this->getEmpleadosTop($fechaInicio, $fechaFin);
                $titulo = 'Empleados con Más Ventas';
                $vista = 'reportes.pdf.empleados';
                break;
                
          
            case 'vencimientos':
                // Para mostrar TODO: no pasar dias_limite o pasar un valor muy grande
                $diasLimite = $request->dias_limite ?? 730; // 2 años
                
                $data = $this->getProductosPorVencerPDF($diasLimite);
                $titulo = 'Control de Productos por Vencer';
                $vista = 'reportes.pdf.vencimientos';
                
                \Log::info('Datos preparados para vencimientos', [
                    'proximos_count' => $data['total_proximos'],
                    'vencidos_count' => $data['total_vencidos'],
                    'dias_limite' => $data['dias_limite'],
                    'fecha_limite' => $data['fecha_limite']
                ]);
                break;

            case 'completo':
                $data = [
                    'productos_cantidad' => $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin),
                    'productos_monto' => $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin),
                    'distribucion_almacenes' => $this->getDistribucionAlmacenes(),
                    'empleados_top' => $this->getEmpleadosTop($fechaInicio, $fechaFin),
                    'productos_vencimiento' => $this->getProductosPorVencerPDF($diasLimite), // Opcional: agregar al reporte completo
                    'periodo' => [
                        'fecha_inicio' => $fechaInicio,
                        'fecha_fin' => $fechaFin
                    ]
                ];
                $titulo = 'Reporte Completo del Sistema';
                $vista = 'reportes.pdf.completo';
                break;
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de reporte no válido'
                ], 400);
        }

        // Crear array de datos para la vista
        $viewData = [
            'data' => $data,
            'titulo' => $titulo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_actual' => $data['fecha_actual'],
            'dias_limite' => $data['dias_limite'],
            'fecha_limite' => $data['fecha_limite'],

            'fecha_generacion' => Carbon::now()->format('Y-m-d H:i:s'),
            
        ];

        // Si es vencimientos, necesitamos datos específicos
        if ($tipo === 'vencimientos') {
            $viewData = array_merge($viewData, [
                'productos_proximos' => $data['productos_proximos'],
                'productos_vencidos' => $data['productos_vencidos'],
                'titulo' => 'Control de Productos por Vencer'
            ]);
        }

           $pdf = PDF::loadView($vista, $viewData);
    return $pdf->download("reporte-{$tipo}-" . Carbon::now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Exportar reporte a Excel (CSV)
     */
   public function exportarExcel(Request $request, $tipo)
{
    $request->validate([
        'fecha_inicio' => 'nullable|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'dias_limite' => 'nullable|integer|min:1|max:365',
    ]);

    $fechaInicio = $request->fecha_inicio ?? Carbon::now()->subMonth()->format('Y-m-d');
    $fechaFin = $request->fecha_fin ?? Carbon::now()->format('Y-m-d');
    $diasLimite = $request->dias_limite ?? 30;

    switch ($tipo) {
        case 'productos-cantidad':
            $data = $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin);
            $filename = "productos-cantidad-{$fechaInicio}-{$fechaFin}.xls";
            return $this->generarExcelSimpleProductosCantidad($data, $filename, $fechaInicio, $fechaFin);
            
        case 'productos-monto':
            $data = $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin);
            $filename = "productos-monto-{$fechaInicio}-{$fechaFin}.xls";
            return $this->generarExcelSimpleProductosMonto($data, $filename, $fechaInicio, $fechaFin);
            
        case 'vencimientos':
            $data = $this->getProductosPorVencerPDF($diasLimite);
            $filename = "vencimientos-{$diasLimite}dias-" . date('Ymd-His') . ".xls";
            return $this->generarExcelSimpleVencimientos($data, $filename, $diasLimite);
            
        case 'almacenes':
            $data = $this->getDistribucionAlmacenes();
            $filename = "distribucion-almacenes-" . date('Ymd-His') . ".xls";
            return $this->generarExcelSimpleAlmacenes($data, $filename);
            
        case 'empleados':
            $data = $this->getEmpleadosTop($fechaInicio, $fechaFin);
            $filename = "empleados-top-{$fechaInicio}-{$fechaFin}.xls";
            return $this->generarExcelSimpleEmpleados($data, $filename, $fechaInicio, $fechaFin);
            
        default:
            return response()->json([
                'success' => false,
                'message' => 'Tipo de exportación no válido'
            ], 400);
    }
}

// Métodos adicionales para almacenes y empleados si los necesitas:

private function generarExcelSimpleAlmacenes($data, $filename)
{
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #6c757d; color: white; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: center; }
            td { padding: 8px; border: 1px solid #ddd; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .info-box { 
                background-color: #f8f9fa; 
                padding: 15px; 
                border-radius: 5px; 
                margin: 20px 0;
                border-left: 4px solid #6c757d;
            }
        </style>
    </head>
    <body>
        <h1>Distribución en Almacenes</h1>
        
        <div class="info-box">
            <p><strong>Fecha:</strong> ' . date('Y-m-d') . '</p>
            <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </div>';
    
    if ($data->count() > 0) {
        $html .= '
        <table>
            <tr>
                <th>#</th>
                <th>Almacén</th>
                <th>Stock Total</th>
                <th>Porcentaje</th>
            </tr>';
        
        $totalStock = 0;
        
        foreach ($data as $index => $almacen) {
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($almacen->nombreAl) . '</td>
                <td class="text-right">' . number_format($almacen->total_stock) . '</td>
                <td class="text-center">' . number_format($almacen->porcentaje, 1) . '%</td>
            </tr>';
            
            $totalStock += $almacen->total_stock;
        }
        
        $html .= '
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="2"><strong>TOTAL STOCK</strong></td>
                <td class="text-right"><strong>' . number_format($totalStock) . '</strong></td>
                <td class="text-center"><strong>100%</strong></td>
            </tr>
        </table>';
    } else {
        $html .= '<p style="text-align: center; font-style: italic; color: #666;">No hay datos de almacenes</p>';
    }
    
    $html .= '
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo $html;
    exit;
}

private function generarExcelSimpleEmpleados($data, $filename, $fechaInicio, $fechaFin)
{
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #dc3545; color: white; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: center; }
            td { padding: 8px; border: 1px solid #ddd; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .info-box { 
                background-color: #f8f9fa; 
                padding: 15px; 
                border-radius: 5px; 
                margin: 20px 0;
                border-left: 4px solid #dc3545;
            }
        </style>
    </head>
    <body>
        <h1>Empleados con Más Ventas</h1>
        
        <div class="info-box">
            <p><strong>Período:</strong> ' . $fechaInicio . ' al ' . $fechaFin . '</p>
            <p><strong>Fecha:</strong> ' . date('Y-m-d') . '</p>
            <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </div>';
    
    if ($data->count() > 0) {
        $html .= '
        <table>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Ventas Realizadas</th>
                <th>Monto Generado ($)</th>
            </tr>';
        
        $totalVentas = 0;
        $totalMonto = 0;
        
        foreach ($data as $index => $empleado) {
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($empleado->nombreEm . ' ' . $empleado->apellidosEm) . '</td>
                <td class="text-center">' . number_format($empleado->total_ventas) . '</td>
                <td class="text-right">$' . number_format($empleado->total_monto_ventas, 2) . '</td>
            </tr>';
            
            $totalVentas += $empleado->total_ventas;
            $totalMonto += $empleado->total_monto_ventas;
        }
        
        $html .= '
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="2"><strong>TOTALES</strong></td>
                <td class="text-center"><strong>' . number_format($totalVentas) . '</strong></td>
                <td class="text-right"><strong>$' . number_format($totalMonto, 2) . '</strong></td>
            </tr>
        </table>';
    } else {
        $html .= '<p style="text-align: center; font-style: italic; color: #666;">No hay datos de empleados</p>';
    }
    
    $html .= '
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo $html;
    exit;
}
private function generarExcelSimpleProductosCantidad($data, $filename, $fechaInicio, $fechaFin)
{
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; }
            h2 { color: #666; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #2C3E50; color: white; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: center; }
            td { padding: 8px; border: 1px solid #ddd; }
            .total-row { background-color: #f2f2f2; font-weight: bold; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        </style>
    </head>
    <body>
        <h1>Productos Más Vendidos (Cantidad)</h1>
        <p><strong>Período:</strong> ' . $fechaInicio . ' al ' . $fechaFin . '</p>
        <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>';
    
    if ($data->count() > 0) {
        $html .= '
        <table>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th>Monto Total ($)</th>
                <th>ID Producto</th>
            </tr>';
        
        $totalCantidad = 0;
        $totalMonto = 0;
        
        foreach ($data as $index => $producto) {
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($producto->nombrePr) . '</td>
                <td class="text-right">' . number_format($producto->total_cantidad) . '</td>
                <td class="text-right">' . number_format($producto->total_monto, 2) . '</td>
                <td class="text-center">' . $producto->id_producto . '</td>
            </tr>';
            
            $totalCantidad += $producto->total_cantidad;
            $totalMonto += $producto->total_monto;
        }
        
        $html .= '
            <tr class="total-row">
                <td colspan="2"><strong>TOTALES</strong></td>
                <td class="text-right"><strong>' . number_format($totalCantidad) . '</strong></td>
                <td class="text-right"><strong>$' . number_format($totalMonto, 2) . '</strong></td>
                <td></td>
            </tr>
        </table>';
    } else {
        $html .= '<p><em>No hay datos disponibles para el período seleccionado</em></p>';
    }
    
    $html .= '
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo $html;
    exit;
}

private function generarExcelSimpleProductosMonto($data, $filename, $fechaInicio, $fechaFin)
{
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; }
            h2 { color: #666; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #FFC107; color: black; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: center; }
            td { padding: 8px; border: 1px solid #ddd; }
            .total-row { background-color: #f2f2f2; font-weight: bold; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        </style>
    </head>
    <body>
        <h1>Productos Más Vendidos (Monto)</h1>
        <p><strong>Período:</strong> ' . $fechaInicio . ' al ' . $fechaFin . '</p>
        <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>';
    
    if ($data->count() > 0) {
        $html .= '
        <table>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Monto Total ($)</th>
                <th>Cantidad Vendida</th>
                <th>ID Producto</th>
            </tr>';
        
        $totalCantidad = 0;
        $totalMonto = 0;
        
        foreach ($data as $index => $producto) {
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($producto->nombrePr) . '</td>
                <td class="text-right"><strong>$' . number_format($producto->total_monto, 2) . '</strong></td>
                <td class="text-right">' . number_format($producto->total_cantidad) . '</td>
                <td class="text-center">' . $producto->id_producto . '</td>
            </tr>';
            
            $totalCantidad += $producto->total_cantidad;
            $totalMonto += $producto->total_monto;
        }
        
        $html .= '
            <tr class="total-row">
                <td colspan="2"><strong>TOTALES</strong></td>
                <td class="text-right"><strong>$' . number_format($totalMonto, 2) . '</strong></td>
                <td class="text-right"><strong>' . number_format($totalCantidad) . '</strong></td>
                <td></td>
            </tr>
        </table>';
    } else {
        $html .= '<p><em>No hay datos disponibles para el período seleccionado</em></p>';
    }
    
    $html .= '
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo $html;
    exit;
}

private function generarExcelSimpleVencimientos($data, $filename, $diasLimite)
{
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; }
            h2 { 
                padding: 10px; 
                margin-top: 30px;
                border-left: 4px solid;
            }
            .proximo-header { 
                background-color: #d1ecf1; 
                color: #0c5460; 
                border-left-color: #17a2b8;
            }
            .vencido-header { 
                background-color: #f8d7da; 
                color: #721c24;
                border-left-color: #dc3545;
            }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { 
                font-weight: bold; 
                padding: 10px; 
                border: 1px solid #ddd; 
                text-align: center; 
            }
            .proximo-th { 
                background-color: #17a2b8; 
                color: white; 
            }
            .vencido-th { 
                background-color: #dc3545; 
                color: white; 
            }
            td { padding: 8px; border: 1px solid #ddd; }
            .critico { color: #dc3545; font-weight: bold; }
            .alerta { color: #ffc107; font-weight: bold; }
            .normal { color: #28a745; }
            .vencido { color: #dc3545; font-weight: bold; }
            .text-center { text-align: center; }
            .info-box { 
                background-color: #f8f9fa; 
                padding: 15px; 
                border-radius: 5px; 
                margin: 20px 0;
                border-left: 4px solid #6c757d;
            }
        </style>
    </head>
    <body>
        <h1>Control de Productos por Vencer</h1>
        
        <div class="info-box">
            <p><strong>Fecha actual:</strong> ' . $data['fecha_actual'] . '</p>
            <p><strong>Días límite para alerta:</strong> ' . $data['dias_limite'] . ' días</p>
            <p><strong>Fecha límite:</strong> ' . $data['fecha_limite'] . '</p>
            <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </div>';
    
    // PRODUCTOS PRÓXIMOS
    $html .= '
        <h2 class="proximo-header">Productos Próximos a Vencer (' . $data['total_proximos'] . ')</h2>';
    
    if ($data['total_proximos'] > 0) {
        $html .= '
        <table>
            <tr>
                <th class="proximo-th">#</th>
                <th class="proximo-th">Producto</th>
                <th class="proximo-th">Fecha Vencimiento</th>
                <th class="proximo-th">Días Restantes</th>
                <th class="proximo-th">Estado</th>
            </tr>';
        
        foreach ($data['productos_proximos'] as $index => $producto) {
            $estadoClass = 'normal';
            $estadoTexto = 'Normal';
            
            if ($producto->dias_restantes <= 7) {
                $estadoClass = 'critico';
                $estadoTexto = 'Crítico';
            } elseif ($producto->dias_restantes <= 15) {
                $estadoClass = 'alerta';
                $estadoTexto = 'Alerta';
            }
            
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($producto->nombrePr) . '</td>
                <td class="text-center">' . $producto->fecha_formateada . '</td>
                <td class="text-center">' . $producto->dias_restantes . '</td>
                <td class="text-center ' . $estadoClass . '">' . $estadoTexto . '</td>
            </tr>';
        }
        
        $html .= '</table>';
    } else {
        $html .= '<p style="text-align: center; font-style: italic; color: #666;">No hay productos próximos a vencer</p>';
    }
    
    // PRODUCTOS VENCIDOS
    $html .= '
        <h2 class="vencido-header">Productos Vencidos (' . $data['total_vencidos'] . ')</h2>';
    
    if ($data['total_vencidos'] > 0) {
        $html .= '
        <table>
            <tr>
                <th class="vencido-th">#</th>
                <th class="vencido-th">Producto</th>
                <th class="vencido-th">Fecha Vencimiento</th>
                <th class="vencido-th">Días Vencido</th>
                <th class="vencido-th">Estado</th>
            </tr>';
        
        foreach ($data['productos_vencidos'] as $index => $producto) {
            $html .= '
            <tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($producto->nombrePr) . '</td>
                <td class="text-center">' . $producto->fecha_formateada . '</td>
                <td class="text-center">' . $producto->dias_vencido . '</td>
                <td class="text-center vencido">Vencido</td>
            </tr>';
        }
        
        $html .= '</table>';
    } else {
        $html .= '<p style="text-align: center; font-style: italic; color: #666;">No hay productos vencidos</p>';
    }
    
    $html .= '
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo $html;
    exit;
}
    public function alertasVencimientos()
    {
        $fechaActual = Carbon::now();
        $fechaLimite = $fechaActual->copy()->addDays(7); // Alerta a 7 días
        
        $productosProximos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '>', $fechaActual)
            ->where('fechaVencimiento', '<=', $fechaLimite)
            ->with(['categoria'])
            ->select(['id_producto', 'nombrePr', 'fechaVencimiento', 'compocicionQuimica', 'consentracionQuimica'])
            ->orderBy('fechaVencimiento')
            ->get();
        
        $productosVencidos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '<=', $fechaActual)
            ->get();
        
        return [
            'proximos' => $productosProximos,
            'vencidos' => $productosVencidos,
            'alerta' => $productosProximos->count() > 0 || $productosVencidos->count() > 0
        ];
    }
}