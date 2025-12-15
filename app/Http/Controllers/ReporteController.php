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
        $request->validate([
            'dias_limite' => 'nullable|integer|min:1|max:365',
        ]);

        $diasLimite = $request->dias_limite ?? 30;
        $fechaActual = Carbon::now();
        $fechaLimite = $fechaActual->copy()->addDays($diasLimite);
        
        // Productos próximos a vencer
        $productosProximos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '>', $fechaActual)
            ->where('fechaVencimiento', '<=', $fechaLimite)
            ->with(['categoria'])
            ->orderBy('fechaVencimiento')
            ->get()
            ->map(function ($producto) use ($fechaActual) {
                $producto->dias_restantes = $fechaActual->diffInDays($producto->fechaVencimiento, false);
                return $producto;
            });
        
        // Productos vencidos (últimos 90 días)
        $productosVencidos = Producto::whereNotNull('fechaVencimiento')
            ->where('fechaVencimiento', '<=', $fechaActual)
            ->where('fechaVencimiento', '>=', $fechaActual->copy()->subDays(90))
            ->with(['categoria'])
            ->orderBy('fechaVencimiento', 'desc')
            ->get()
            ->map(function ($producto) use ($fechaActual) {
                $producto->dias_vencido = $fechaActual->diffInDays($producto->fechaVencimiento);
                return $producto;
            });

        return response()->json([
            'success' => true,
            'productos_proximos' => $productosProximos,
            'productos_vencidos' => $productosVencidos,
            'total_proximos' => $productosProximos->count(),
            'total_vencidos' => $productosVencidos->count(),
            'dias_limite' => $diasLimite,
            'fecha_actual' => $fechaActual->format('Y-m-d'),
            'fecha_limite' => $fechaLimite->format('Y-m-d'),
        ]);
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
                
            case 'completo':
                $data = [
                    'productos_cantidad' => $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin),
                    'productos_monto' => $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin),
                    'distribucion_almacenes' => $this->getDistribucionAlmacenes(),
                    'empleados_top' => $this->getEmpleadosTop($fechaInicio, $fechaFin),
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
            'fecha_generacion' => Carbon::now()->format('Y-m-d H:i:s')
        ];

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
        ]);

        $fechaInicio = $request->fecha_inicio ?? Carbon::now()->subMonth()->format('Y-m-d');
        $fechaFin = $request->fecha_fin ?? Carbon::now()->format('Y-m-d');

        switch ($tipo) {
            case 'productos-cantidad':
                $data = $this->getProductosMasVendidosPorCantidad($fechaInicio, $fechaFin);
                $filename = "productos-cantidad-{$fechaInicio}-{$fechaFin}.csv";
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename={$filename}",
                ];
                
                $callback = function() use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Producto', 'Cantidad Vendida', 'Monto Total ($)', 'ID Producto']);
                    
                    foreach ($data as $producto) {
                        fputcsv($file, [
                            $producto->nombrePr,
                            $producto->total_cantidad,
                            number_format($producto->total_monto, 2),
                            $producto->id_producto
                        ]);
                    }
                    
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);
                
            case 'productos-monto':
                $data = $this->getProductosMasVendidosPorMonto($fechaInicio, $fechaFin);
                $filename = "productos-monto-{$fechaInicio}-{$fechaFin}.csv";
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename={$filename}",
                ];
                
                $callback = function() use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Producto', 'Monto Total ($)', 'Cantidad Vendida', 'ID Producto']);
                    
                    foreach ($data as $producto) {
                        fputcsv($file, [
                            $producto->nombrePr,
                            number_format($producto->total_monto, 2),
                            $producto->total_cantidad,
                            $producto->id_producto
                        ]);
                    }
                    
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de exportación no válido'
                ], 400);
        }
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