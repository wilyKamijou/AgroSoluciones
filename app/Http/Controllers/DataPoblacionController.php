<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEmpleado;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\DetalleAlmacen;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DataPoblacionController extends Controller
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create('es_ES'); 
    }

    // Vista principal con botones
    public function index()
    {
        $counts = [
            'tipoEmpleado' => TipoEmpleado::count(),
            'usuarios' => User::count(),
            'empleados' => Empleado::count(),
            'clientes' => Cliente::count(),
            'categorias' => Categoria::count(),
            'productos' => Producto::count(),
            'almacenes' => Almacen::count(),
              'detalleAlmacenes' => DetalleAlmacen::count(),
            'ventas' => Venta::count(),
            'detalleVentas' => DetalleVenta::count(),
        ];
        return view('poblacion.index', compact('counts'));
    }
    // Poblar TipoEmpleado (estático - no cambia)
    public function poblarTipoEmpleado()
    {
        // Verificar si ya existen datos para no duplicar
        if (TipoEmpleado::count() > 6) {
            return redirect()->route('poblacion.index')
                ->with('info', 'Los tipos de empleado ya existen en la base de datos');
        }

        $tiposEmpleado = [
            ['nombreE' => 'Owner', 'descripcionTip' => 'Propietario del Sistema', 'rutas_acceso' => 'R U C E V A P'],
            ['nombreE' => 'Gerente', 'descripcionTip' => 'Gerente de Tienda', 'rutas_acceso' => ' U C E'],
            ['nombreE' => 'Encargado Ventas', 'descripcionTip' => 'Responsable de Ventas', 'rutas_acceso' => 'V P'],
            ['nombreE' => 'Encargado Almacenes', 'descripcionTip' => 'Responsable de Almacenes', 'rutas_acceso' => 'A P'],
        ];  

        TipoEmpleado::insert($tiposEmpleado);

        return redirect()->route('poblacion.index')
            ->with('success', 'Tipos de empleado poblados exitosamente: ' . count($tiposEmpleado) . ' registros');
    }

    // Poblar Usuarios con Faker
    public function poblarUsuarios()
    {
        $cantidad = 12; // Para 12 empleados

        for ($i = 0; $i < $cantidad; $i++) {
            User::create([
                'name' => $this->faker->userName(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
            ]);
        }

        return redirect()->route('poblacion.index')
            ->with('success', 'Usuarios poblados exitosamente: ' . $cantidad . ' registros');
    }

    // Poblar Empleado con Faker
    public function poblarEmpleado()
    {
        // Verificar que existan tipos de empleado
        if (TipoEmpleado::count() == 0) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar los tipos de empleado');
        }

        // Verificar que existan usuarios
        if (User::count() < 12) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar los usuarios (mínimo 12)');
        }

        $empleados = [];
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 12; $i++) {
            $empleados[] = [
                'nombreEm' => $this->faker->firstName(),
                'apellidosEm' => $this->faker->lastName() . ' ' . $this->faker->lastName(),
                'sueldoEm' => $this->faker->numberBetween(7000, 15000),
                'telefonoEm' => $this->faker->numerify('55#######'),
                'direccion' => $this->faker->streetAddress(),
                'id_tipoE' => $this->faker->numberBetween(1, 4), // Asigna un tipo de empleado existente
                'user_id' => $userIds[$i] // Asigna usuario correspondiente
            ];
        }

        Empleado::insert($empleados);

        return redirect()->route('poblacion.index')
            ->with('success', 'Empleados poblados exitosamente: ' . count($empleados) . ' registros');
    }

    // Poblar Cliente con Faker
    public function poblarCliente()
    {
        $clientes = [];

        for ($i = 0; $i < 30; $i++) {
            $clientes[] = [
                'nombreCl' => $this->faker->firstName(),
                'apellidosCl' => $this->faker->lastName() . ' ' . $this->faker->lastName(),
                'telefonoCl' => $this->faker->numerify('55#######')
            ];
        }

        Cliente::insert($clientes);

        return redirect()->route('poblacion.index')
            ->with('success', 'Clientes poblados exitosamente: ' . count($clientes) . ' registros');
    }
    public function poblarCategorias()
    {
        if (Categoria::count() > 15) {
            return redirect()->route('poblacion.index')
                ->with('info', 'Las categorías ya existen en la base de datos');
        }

        $categorias = [
            ['nombreCat' => 'Herbicidas', 'descripcionCat' => 'Productos para control de malezas'],
            ['nombreCat' => 'Insecticidas', 'descripcionCat' => 'Control de insectos y plagas'],
            ['nombreCat' => 'Fungicidas', 'descripcionCat' => 'Protección contra hongos'],
            ['nombreCat' => 'Fertilizantes', 'descripcionCat' => 'Nutrientes para el suelo'],
            ['nombreCat' => 'Reguladores de Crecimiento', 'descripcionCat' => 'Control del desarrollo vegetal'],
            ['nombreCat' => 'Acaricidas', 'descripcionCat' => 'Control de ácaros'],
            ['nombreCat' => 'Nematicidas', 'descripcionCat' => 'Control de nematodos'],
            ['nombreCat' => 'Rodenticidas', 'descripcionCat' => 'Control de roedores'],
            ['nombreCat' => 'Coadyuvantes', 'descripcionCat' => 'Mejoradores de aplicación'],
            ['nombreCat' => 'Bioestimulantes', 'descripcionCat' => 'Estimulantes naturales'],
            ['nombreCat' => 'Inoculantes', 'descripcionCat' => 'Bacterias beneficiosas'],
            ['nombreCat' => 'Correctores de Carencias', 'descripcionCat' => 'Suplementos nutricionales'],
            ['nombreCat' => 'Desinfectantes', 'descripcionCat' => 'Limpieza y sanitización'],
            ['nombreCat' => 'Raticidas', 'descripcionCat' => 'Control de ratas'],
            ['nombreCat' => 'Repelentes', 'descripcionCat' => 'Ahuyentadores de plagas'],
        ];

        Categoria::insert($categorias);

        return redirect()->route('poblacion.index')
            ->with('success', 'Categorías pobladas exitosamente: ' . count($categorias) . ' registros');
    }

    // Nuevo método para poblar Productos
    public function poblarProductos()
    {
        if (Categoria::count() == 0) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar las categorías');
        }

        if (Producto::count() > 30) {
            return redirect()->route('poblacion.index')
                ->with('info', 'Los productos ya existen en la base de datos');
        }

        $fechaFabricacion = now()->subMonths(6);
        $fechaVencimiento = now()->addYears(2);

        $productos = [
            [
                'nombrePr' => 'Glifosato Premium', 'nombreTecnico' => 'Glifosato 480 SL',
                'descripcionPr' => 'Herbicida sistémico no selectivo', 'compocicionQuimica' => 'Glifosato 480 g/L',
                'consentracionQuimica' => '48%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 1,
                'precioPr' => 250.00,
                'imagen_url' => 'https://sembrarideas.com.ar/wp-content/uploads/2016/06/glynomyl-premium-imagen-producto.jpg'
            ],
            [
                'nombrePr' => 'Atrazina Forte', 'nombreTecnico' => 'Atrazina 500 SC',
                'descripcionPr' => 'Herbicida selectivo para maíz', 'compocicionQuimica' => 'Atrazina 500 g/L',
                'consentracionQuimica' => '50%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 1,
                'precioPr' => 300.00,
                'imagen_url' => 'https://www.invesa.com/wp-content/uploads/2020/08/Atrazina80_1k-1024x1024.png'
            ],
            [
                'nombrePr' => 'Imidacloprid', 'nombreTecnico' => 'Imidacloprid 350 SC',
                'descripcionPr' => 'Insecticida sistémico', 'compocicionQuimica' => 'Imidacloprid 350 g/L',
                'consentracionQuimica' => '35%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 2,
                'precioPr' => 450.00,
                'imagen_url' => 'https://dva.com/wp-content/uploads/2020/07/IMIDACLOPRID-350-SC-DVA.jpg'
            ],
            [
                'nombrePr' => 'Lambda Cihalotrin', 'nombreTecnico' => 'Lambda Cihalotrin 50 EC',
                'descripcionPr' => 'Insecticida de contacto', 'compocicionQuimica' => 'Lambda Cihalotrin 50 g/L',
                'consentracionQuimica' => '5%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 2,
                'precioPr' => 400.00,
                'imagen_url' => 'https://carvalcorp.com/wp-content/uploads/2025/08/LAMBDA-500mL.jpg'
            ],
            [
                'nombrePr' => 'Azoxistrobina', 'nombreTecnico' => 'Azoxistrobina 250 SC',
                'descripcionPr' => 'Fungicida sistémico', 'compocicionQuimica' => 'Azoxistrobina 250 g/L',
                'consentracionQuimica' => '25%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 3,
                'precioPr' => 550.00,
                'imagen_url' => 'https://qira-production.s3.amazonaws.com/product-pdf/61920-image1-1730485659030'
            ],
            [
                'nombrePr' => 'Mancozeb', 'nombreTecnico' => 'Mancozeb 800 WP',
                'descripcionPr' => 'Fungicida de contacto', 'compocicionQuimica' => 'Mancozeb 800 g/Kg',
                'consentracionQuimica' => '80%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Kilogramo', 'id_categoria' => 3,
                'precioPr' => 350.00,
                'imagen_url' => 'https://www.cropprotection.net/uploads/202028235/mancozeb15119280851.jpg'
            ],
            [
                'nombrePr' => 'Urea Agrícola', 'nombreTecnico' => 'Urea 46-0-0',
                'descripcionPr' => 'Fertilizante nitrogenado', 'compocicionQuimica' => 'Nitrógeno 46%',
                'consentracionQuimica' => '46%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Kilogramo', 'id_categoria' => 4,
                'precioPr' => 280.00,
                'imagen_url' => 'https://www.agrogaia.es/wp-content/uploads/2025/04/fertilizante-urea-1.webp'
            ],
            [
                'nombrePr' => 'Triple 17', 'nombreTecnico' => 'NPK 17-17-17',
                'descripcionPr' => 'Fertilizante completo', 'compocicionQuimica' => 'N 17%, P 17%, K 17%',
                'consentracionQuimica' => '51%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Kilogramo', 'id_categoria' => 4,
                'precioPr' => 320.00,
                'imagen_url' => 'https://risso-chemical.com/wp-content/uploads/2024/04/Tower-Granulation-NPK-17-17-17SOP-1.png'
            ],
            [
                'nombrePr' => 'Etefón', 'nombreTecnico' => 'Etefón 480 SL',
                'descripcionPr' => 'Regulador de maduración', 'compocicionQuimica' => 'Etefón 480 g/L',
                'consentracionQuimica' => '48%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 5,
                'precioPr' => 600.00,
                'imagen_url' => 'https://www.bestplanthormones.com/uploads/202315034/accelerate-ripening-ethephon-480g-lb9029599-1407-41bc-81fe-d6bec0fe50b0.jpg'

            ],
            [
                'nombrePr' => 'Abamectina', 'nombreTecnico' => 'Abamectina 18 EC',
                'descripcionPr' => 'Acaricida e insecticida', 'compocicionQuimica' => 'Abamectina 18 g/L',
                'consentracionQuimica' => '1.8%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 6,
                'precioPr' => 700.00,
                'imagen_url' => 'https://www.agrospec.cl/wp-content/uploads/2023/11/Envase_Abamectin-18EC_250cc_364x364pix.jpg'
            ],
            // ... agregar más productos hasta 30
            [
                'nombrePr' => 'Oxamyl', 'nombreTecnico' => 'Oxamyl 100 SL',
                'descripcionPr' => 'Nematicida sistémico', 'compocicionQuimica' => 'Oxamyl 100 g/L',
                'consentracionQuimica' => '10%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 7,
                'precioPr' => 800.00,
                'imagen_url' => 'https://www.cropprotection.net/uploads/202028235/oxamyl43152857305.jpg'
            ],
            [
                'nombrePr' => 'Brodifacoum', 'nombreTecnico' => 'Brodifacoum 0.005%',
                'descripcionPr' => 'Rodenticida anticoagulante', 'compocicionQuimica' => 'Brodifacoum 0.005%',
                'consentracionQuimica' => '0.005%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Kilogramo', 'id_categoria' => 8,
                'precioPr' => 1500.00,
                'imagen_url' => 'https://sipcamjardin.es/wp-content/uploads/2020/06/78-VITHAL-GARDEN-RATICIDA-BLOQUES-BRODIFACOUM-300-GR.jpg'
            ],
            [
                'nombrePr' => 'Silicona Antideriva', 'nombreTecnico' => 'Coadyuvante Silicona',
                'descripcionPr' => 'Reductor de deriva', 'compocicionQuimica' => 'Silicona 100%',
                'consentracionQuimica' => '100%', 'fechaFabricacion' => $fechaFabricacion,
                 'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 9,
                'precioPr' => 120.00,
                'imagen_url' => 'https://agrolink.ec/wp-content/uploads/2025/03/productos-para-web_0008_Capa-21.jpg'

            ],
            [
                'nombrePr' => 'Algas Marinas', 'nombreTecnico' => 'Extracto de Algas',
                'descripcionPr' => 'Bioestimulante natural', 'compocicionQuimica' => 'Extracto de algas 100%',
                'consentracionQuimica' => '100%', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Litro', 'id_categoria' => 10,
                'precioPr' => 400.00,
                'imagen_url' => 'https://m.media-amazon.com/images/I/71DndsmPJ1L._AC_SX679_.jpg'
            ],
            [
                'nombrePr' => 'Rhizobium', 'nombreTecnico' => 'Inoculante Rhizobium',
                'descripcionPr' => 'Bacteria fijadora de nitrógeno', 'compocicionQuimica' => 'Rhizobium spp.',
                'consentracionQuimica' => '1x10^9 UFC/g', 'fechaFabricacion' => $fechaFabricacion,
                'fechaVencimiento' => $fechaVencimiento, 'unidadMedida' => 'Kilogramo', 'id_categoria' => 11,
                'precioPr' => 350.00,
                'imagen_url' => 'https://www.biogram.cl/wp-content/uploads/2019/04/RIZOFIX_JQG9912_1800px_72dpi.jpg'
            ],
            //--------------------------------------------------nuevos
                        [
                'nombrePr' => '2,4-D Amina', 'nombreTecnico' => '2,4-D 720 SL',
                'descripcionPr' => 'Herbicida hormonal selectivo', 'compocicionQuimica' => '2,4-D 720 g/L',
                'consentracionQuimica' => '72%', 'fechaFabricacion' => '2023-03-15',
                'fechaVencimiento' => '2024-09-15',
                'unidadMedida' => 'Litro', 'id_categoria' => 1,
                'precioPr' => 280.00,
                'imagen_url' => 'http://www.agronorte.com.py/userfiles/images/productos/4/24d-amina-2_1.jpg'
            ],
            [
                'nombrePr' => 'Clorpirifós', 'nombreTecnico' => 'Clorpirifós 480 EC',
                'descripcionPr' => 'Insecticida organofosforado', 'compocicionQuimica' => 'Clorpirifós 480 g/L',
                'consentracionQuimica' => '48%', 'fechaFabricacion' => '2024-01-20',
                'fechaVencimiento' => '2025-07-20', 'unidadMedida' => 'Litro', 'id_categoria' => 2,
                'precioPr' => 380.00,
                'imagen_url' => 'https://proquimur.com.uy/wp-content/uploads/2017/05/clorpirifos.jpg'
            ],
            [
                'nombrePr' => 'Tebuconazole', 'nombreTecnico' => 'Tebuconazole 250 EC',
                'descripcionPr' => 'Fungicida triazol sistémico', 'compocicionQuimica' => 'Tebuconazole 250 g/L',
                'consentracionQuimica' => '25%', 'fechaFabricacion' => '2023-11-10',
                'fechaVencimiento' => '2024-11-10',
                'unidadMedida' => 'Litro', 'id_categoria' => 3,
                'precioPr' => 420.00,
                'imagen_url' => 'https://www.albaugh.com/images/colombialibraries/product/tebucoz-25-ec.png?sfvrsn=55afa27b_2'
            ],
            [
                'nombrePr' => 'Sulfato de Amonio', 'nombreTecnico' => '21-0-0-24S',
                'descripcionPr' => 'Fertilizante nitrogenado y azufrado', 'compocicionQuimica' => 'N 21%, S 24%',
                'consentracionQuimica' => '45%', 'fechaFabricacion' => '2024-02-28',
                'fechaVencimiento' => '2026-02-28', 'unidadMedida' => 'Kilogramo', 'id_categoria' => 4,
                'precioPr' => 190.00,
                'imagen_url' => 'https://www.molinosycia.com/wp-content/uploads/2020/11/SULFATO-DE-AMONIO-2021-OK-web.png'
            ],
            [
                'nombrePr' => 'Ácido Giberélico', 'nombreTecnico' => 'GA3 20%',
                'descripcionPr' => 'Regulador de crecimiento vegetal', 'compocicionQuimica' => 'Ácido Giberélico 20%',
                'consentracionQuimica' => '20%', 'fechaFabricacion' => '2024-04-15',
                'fechaVencimiento' => '2025-10-15', 'unidadMedida' => 'Gramo', 'id_categoria' => 5,
                'precioPr' => 850.00,
                'imagen_url' => 'https://acdn-us.mitiendanube.com/stores/003/979/184/products/generica-8281da65dae4c0f03617144161886031-1024-1024.webp'
            ],
            [
                'nombrePr' => 'Hexitiazox', 'nombreTecnico' => 'Hexitiazox 10% SC',
                'descripcionPr' => 'Acaricida específico para ácaros', 'compocicionQuimica' => 'Hexitiazox 100 g/L',
                'consentracionQuimica' => '10%', 'fechaFabricacion' => '2024-03-01',
                'fechaVencimiento' => '2025-09-01', 'unidadMedida' => 'Litro', 'id_categoria' => 6,
                'precioPr' => 680.00,
                'imagen_url' => 'https://www.buscador.portaltecnoagricola.com/app/imagenes_aplicacion/portaltecnoagricola-globe-chemicals-HEXYGLOB-45-SC.jpg'
            ],
            [
                'nombrePr' => 'Cadusafós', 'nombreTecnico' => 'Cadusafós 100 G',
                'descripcionPr' => 'Nematicida granular', 'compocicionQuimica' => 'Cadusafós 100 g/Kg',
                'consentracionQuimica' => '10%', 'fechaFabricacion' => '2022-12-15',
                'fechaVencimiento' => '2023-12-15',
                'unidadMedida' => 'Kilogramo', 'id_categoria' => 7,
                'precioPr' => 920.00,
                'imagen_url' => 'https://agrimportec.com/wp-content/uploads/2022/05/DSAFASFDSAFDSADASD.png'
            ],
            [
                'nombrePr' => 'Bromadiolona', 'nombreTecnico' => 'Bromadiolona 0.005%',
                'descripcionPr' => 'Rodenticida de segunda generación', 'compocicionQuimica' => 'Bromadiolona 0.005%',
                'consentracionQuimica' => '0.005%', 'fechaFabricacion' => '2024-01-30',
                'fechaVencimiento' => '2025-07-30', 'unidadMedida' => 'Kilogramo', 'id_categoria' => 8,
                'precioPr' => 1250.00,
                'imagen_url' => 'https://www.agrobesser.com/10590-large_default/rugby-20l-cadusafos-nematicida-sistemico-accion-contacto-ingestion-fmc.jpg'
            ],
            [
                'nombrePr' => 'Extracto de Yucca', 'nombreTecnico' => 'Yucca schidigera',
                'descripcionPr' => 'Coadyuvante antievaporante', 'compocicionQuimica' => 'Saponinas 10%',
                'consentracionQuimica' => '10%', 'fechaFabricacion' => '2024-05-10',
                'fechaVencimiento' => '2025-11-10', 'unidadMedida' => 'Litro', 'id_categoria' => 9,
                'precioPr' => 180.00,
                'imagen_url' => 'https://mountainsideorganicos.com/cdn/shop/files/extracto-yucca-bioestimulante-saponinas-surfactante-1L-A-2048_2048x.png?v=1754600976'
            ],
            [
                'nombrePr' => 'Aminoácidos Líquidos', 'nombreTecnico' => 'Aminoácidos 12%',
                'descripcionPr' => 'Bioestimulante de rápida absorción', 'compocicionQuimica' => 'Aminoácidos 120 g/L',
                'consentracionQuimica' => '12%', 'fechaFabricacion' => '2023-08-20',
                'fechaVencimiento' => '2024-08-20', 
                'unidadMedida' => 'Litro', 'id_categoria' => 10,
                'precioPr' => 320.00,
                'imagen_url' => 'https://biosaludcr.com/file/2020/04/FM67BI354_SVCBLA16_IMD_900x.png-600x450.webp'
            ],
            [
                'nombrePr' => 'Bacillus thuringiensis', 'nombreTecnico' => 'Bt var. kurstaki',
                'descripcionPr' => 'Bioinsecticida bacteriano', 'compocicionQuimica' => 'Bacillus thuringiensis',
                'consentracionQuimica' => '32.000 UI/mg', 'fechaFabricacion' => '2024-04-05',
                'fechaVencimiento' => '2025-10-05', 'unidadMedida' => 'Kilogramo', 'id_categoria' => 11,
                'precioPr' => 410.00,
                'imagen_url' => 'https://www.cropprotection.net/uploads/202028235/bacillus-thuringiensis13452871019.jpg'
            ],
            [
                'nombrePr' => 'Paraquat', 'nombreTecnico' => 'Paraquat 200 SL',
                'descripcionPr' => 'Herbicida de contacto no selectivo', 'compocicionQuimica' => 'Paraquat 200 g/L',
                'consentracionQuimica' => '20%', 'fechaFabricacion' => '2024-06-01',
                'fechaVencimiento' => '2025-12-01', 'unidadMedida' => 'Litro', 'id_categoria' => 1,
                'precioPr' => 310.00,
                'imagen_url' => 'https://www.pomais.com/wp-content/uploads/2024/12/paraquat20SL-1-e1742434854435.jpg'
            ],
            [
                'nombrePr' => 'Deltametrina', 'nombreTecnico' => 'Deltametrina 25 EC',
                'descripcionPr' => 'Insecticida piretroide', 'compocicionQuimica' => 'Deltametrina 25 g/L',
                'consentracionQuimica' => '2.5%', 'fechaFabricacion' => '2024-02-15',
                'fechaVencimiento' => '2025-08-15', 'unidadMedida' => 'Litro', 'id_categoria' => 2,
                'precioPr' => 290.00,
                'imagen_url' => 'https://rimacsa.co.cr/wp-content/uploads/2021/05/Rimac-Deltametrina-2.5-EC-1.png'
            ],
            [
                'nombrePr' => 'Cobre Oxicloruro', 'nombreTecnico' => 'Oxicloruro de Cobre 50%',
                'descripcionPr' => 'Fungicida preventivo de cobre', 'compocicionQuimica' => 'Cobre 50%',
                'consentracionQuimica' => '50%', 'fechaFabricacion' => '2023-10-25',
                'fechaVencimiento' => '2024-10-25', 
                'unidadMedida' => 'Kilogramo', 'id_categoria' => 3,
                'precioPr' => 270.00,
                'imagen_url' => 'https://www.agrobesser.com/10328-large_default/cupravit-1kg-oxicloruro-de-cobre-fungicida-accion-contacto-preventivo-bayer.jpg'
            ],
            [
                'nombrePr' => 'Fosfato Diamónico', 'nombreTecnico' => 'DAP 18-46-0',
                'descripcionPr' => 'Fertilizante fosfatado', 'compocicionQuimica' => 'N 18%, P 46%',
                'consentracionQuimica' => '64%', 'fechaFabricacion' => '2024-03-10',
                'fechaVencimiento' => '2026-03-10', 'unidadMedida' => 'Kilogramo', 'id_categoria' => 4,
                'precioPr' => 340.00,
                'imagen_url' => 'https://www.molinosycia.com/wp-content/uploads/2020/11/molinos-y-cia-productos-fertilizantes-fosfatados-fosfato-diamonico-1.png'
            ],
            [
                'nombrePr' => 'Ethephon + Cyclanilide', 'nombreTecnico' => 'Regulador de Floración',
                'descripcionPr' => 'Combinación para regulación de floración', 'compocicionQuimica' => 'Ethephon 480 g/L + Cyclanilide 50 g/L',
                'consentracionQuimica' => '53%', 'fechaFabricacion' => '2024-05-20',
                'fechaVencimiento' => '2025-11-20', 'unidadMedida' => 'Litro', 'id_categoria' => 5,
                'precioPr' => 720.00,
                'imagen_url' => 'https://www.plantgrowthhormones.com/uploads/15034/ethephon-cyclanilidedeef2.jpg'
            ],
            [
                'nombrePr' => 'Spirodiclofen', 'nombreTecnico' => 'Spirodiclofen 240 SC',
                'descripcionPr' => 'Acaricida inhibidor de lípidos', 'compocicionQuimica' => 'Spirodiclofen 240 g/L',
                'consentracionQuimica' => '24%', 'fechaFabricacion' => '2024-01-15',
                'fechaVencimiento' => '2025-07-15', 'unidadMedida' => 'Litro', 'id_categoria' => 6,
                'precioPr' => 750.00,
                'imagen_url' => 'https://cropbusiness.com/wp-content/uploads/2020/08/80-crop-business.jpg'
            ],
            [
                'nombrePr' => 'Fluensulfone', 'nombreTecnico' => 'Fluensulfone 480 SL',
                'descripcionPr' => 'Nematicida de nueva generación', 'compocicionQuimica' => 'Fluensulfone 480 g/L',
                'consentracionQuimica' => '48%', 'fechaFabricacion' => '2024-06-05',
                'fechaVencimiento' => '2025-12-05', 'unidadMedida' => 'Litro', 'id_categoria' => 7,
                'precioPr' => 980.00,
                'imagen_url' => 'https://www.agrobesser.com/8356-large_default/nimitz-1l-fluensulfone-nematicida-selectivo-de-contacto-agroklinge.jpg'
            ],
            [
                'nombrePr' => 'Difetialona', 'nombreTecnico' => 'Difetialona 0.0025%',
                'descripcionPr' => 'Rodenticida de dosis única', 'compocicionQuimica' => 'Difetialona 0.0025%',
                'consentracionQuimica' => '0.0025%', 'fechaFabricacion' => '2023-09-15',
                'fechaVencimiento' => '2024-09-15', 
                'unidadMedida' => 'Kilogramo', 'id_categoria' => 8,
                'precioPr' => 1100.00,
                'imagen_url' => 'https://www.plagasonline.es/2283-thickbox_default/raticida-difetialona-en-grano-5kg.jpg'
            ],
            [
                'nombrePr' => 'Vinagre de Madera', 'nombreTecnico' => 'Ácido Acético 20%',
                'descripcionPr' => 'Coadyuvante acidificante natural', 'compocicionQuimica' => 'Ácido Acético 200 g/L',
                'consentracionQuimica' => '20%', 'fechaFabricacion' => '2024-04-30',
                'fechaVencimiento' => '2025-10-30', 'unidadMedida' => 'Litro', 'id_categoria' => 9,
                'precioPr' => 150.00,
                'imagen_url' => 'https://i.etsystatic.com/22808520/r/il/fb77a8/4164007448/il_1588xN.4164007448_5sui.jpg'
            ]
        ];

        Producto::insert($productos);

        return redirect()->route('poblacion.index')
            ->with('success', 'Productos poblados exitosamente: ' . count($productos) . ' registros');
    }

    // Nuevo método para poblar Almacenes
    public function poblarAlmacenes()
    {
        if (Almacen::count() > 0) {
            return redirect()->route('poblacion.index')
                ->with('info', 'Los almacenes ya existen en la base de datos');
        }

        $almacenes = [
            [
                'nombreAl' => 'Almacén Central AgroSoluciones',
                'descripcionAl' => 'Almacén principal de productos agroquímicos',
                'direccionAl' => 'Carretera Nacional Km 25.5, Zona Industrial'
            ],
            [
                'nombreAl' => 'Bodega Norte - Fertilizantes',
                'descripcionAl' => 'Almacén especializado en fertilizantes y abonos',
                'direccionAl' => 'Av. Agricultura 123, Parque Industrial Norte'
            ],
            [
                'nombreAl' => 'Centro de Distribución Sur',
                'descripcionAl' => 'Almacén para distribución regional sur',
                'direccionAl' => 'Blv. Los Cultivos 456, Polígono Industrial Sur'
            ],
            [
                'nombreAl' => 'Bodega Segura - Tóxicos',
                'descripcionAl' => 'Almacén para productos de alta toxicidad',
                'descripcionAl' => 'Productos de categoría toxicológica I y II',
                'direccionAl' => 'Sector Aislado, Zona de Seguridad Especial'
            ],
            [
                'nombreAl' => 'Almacén Express - Pedidos Urgentes',
                'descripcionAl' => 'Almacén para pedidos inmediatos y emergencias',
                'direccionAl' => 'Av. Rápida 789, Near City Center'
            ]
        ];

        Almacen::insert($almacenes);

        return redirect()->route('poblacion.index')
            ->with('success', 'Almacenes poblados exitosamente: ' . count($almacenes) . ' registros');
    }
        public function poblarDetalleAlmacen()
    {
        if (Producto::count() == 0 || Almacen::count() == 0) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar productos y almacenes');
        }

        if (DetalleAlmacen::count() > 0) {
            return redirect()->route('poblacion.index')
                ->with('info', 'Los detalles de almacén ya existen');
        }

        $productos = Producto::with('categoria')->get();
        $almacenes = Almacen::all();

        $detallesAlmacen = [];
        
        // Mapeo de categorías a almacenes específicos
        $categoriaAlmacenMap = [
            // Herbicidas e Insecticidas van al almacén central
            1 => 1, 
            2 => 1, 
            
            // Fungicidas y Fertilizantes van a bodega norte
            3 => 2, 
            4 => 2, 
            
            // Reguladores y Bioestimulantes van al centro distribución sur
            5 => 3, 
            10 => 3,
            
            // Productos tóxicos van a bodega segura
            6 => 4, 
            7 => 4, 
            8 => 4, 
            14 => 4,
            
            // Coadyuvantes e inoculantes van a almacén express
            9 => 5, 
            11 => 5,
        ];

        foreach ($productos as $producto) {
            // Determinar el almacén basado en la categoría
            $almacenId = $categoriaAlmacenMap[$producto->id_categoria] ?? 1;
            
            // Stock basado en el tipo de producto
            $stock = match($producto->id_categoria) {
                4 => rand(1000, 5000), 
                1, 2, 3 => rand(100, 500),
                default => rand(50, 200) 
            };

            $detallesAlmacen[] = [
                'id_producto' => $producto->id_producto,
                'id_almacen' => $almacenId,
                'stock' => $stock
            ];

            // Algunos productos pueden estar en múltiples almacenes (30% de probabilidad)
            if (rand(1, 100) <= 30) {
                $almacenAlternativo = $almacenes->where('id_almacen', '!=', $almacenId)->random()->id_almacen;
                $stockAlternativo = max(10, $stock * 0.3); 
                
                $detallesAlmacen[] = [
                    'id_producto' => $producto->id_producto,
                    'id_almacen' => $almacenAlternativo,
                    'stock' => $stockAlternativo
                ];
            }
        }
        
        // Insertar en lotes para mejor performance
        foreach (array_chunk($detallesAlmacen, 100) as $chunk) {
            DetalleAlmacen::insert($chunk);
        }

        return redirect()->route('poblacion.index')
            ->with('success', 'Detalles de almacén poblados exitosamente: ' . count($detallesAlmacen) . ' registros');
    }

    // Nuevo método para poblar Ventas
    public function poblarVentas()
    {
        if (Cliente::count() == 0 || Empleado::count() == 0) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar clientes y empleados');
        }

        $clientes = Cliente::pluck('id_cliente')->toArray();
        $empleados = Empleado::pluck('id_empleado')->toArray();

        $ventas = [];
        
        // Crear 50 ventas
        for ($i = 0; $i < 50; $i++) {
            $fecha = now()->subDays(rand(1, 365));
            $monto = rand(500, 10000); 
            
            $ventas[] = [
                'fechaVe' => $fecha,
                'montoTotalVe' => $monto,
                'id_cliente' => $clientes[array_rand($clientes)],
                'id_empleado' => $empleados[array_rand($empleados)]
            ];
        }

        Venta::insert($ventas);

        return redirect()->route('poblacion.index')
            ->with('success', 'Ventas pobladas exitosamente: ' . count($ventas) . ' registros');
    }

    // Nuevo método para poblar DetalleVenta
    public function poblarDetalleVenta()
    {
        if (Venta::count() == 0 || DetalleAlmacen::count() == 0) {
            return redirect()->route('poblacion.index')
                ->with('error', 'Primero debe poblar ventas y detalles de almacén');
        }

        $ventas = Venta::all();
        $detallesAlmacen = DetalleAlmacen::with('producto')->get();

        $detallesVenta = [];
        
        foreach ($ventas as $venta) {
            // Cada venta tiene entre 1 y 5 productos
            $numProductos = rand(1, 5);
            $productosVendidos = $detallesAlmacen->random(min($numProductos, $detallesAlmacen->count()));
            
            foreach ($productosVendidos as $detalleAlmacen) {
                $cantidad = rand(1, min(50, $detalleAlmacen->stock));
                
                // Precio basado en el tipo de producto con algún margen
                $precioBase = match($detalleAlmacen->producto->id_categoria) {
                    4 => rand(50, 200), 
                    1, 2, 3 => rand(100, 500), 
                    default => rand(200, 1000) 
                };
                
                $detallesVenta[] = [
                    'cantidadDv' => $cantidad,
                    'precioDv' => $precioBase,
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $detalleAlmacen->id_producto,
                    'id_almacen' => $detalleAlmacen->id_almacen
                ];
            }
        }

        // Insertar en lotes
        foreach (array_chunk($detallesVenta, 100) as $chunk) {
            DetalleVenta::insert($chunk);
        }

        return redirect()->route('poblacion.index')
            ->with('success', 'Detalles de venta poblados exitosamente: ' . count($detallesVenta) . ' registros');
    }

    // Poblar todos los modelos en secuencia
    public function poblarTodo()
    {
        try {
            \DB::beginTransaction();

            // Poblar tipos de empleado si no existen
            if (TipoEmpleado::count() == 0) {
                $this->poblarTipoEmpleado();
            }

            // Poblar usuarios si no existen suficientes
            if (User::count() < 12) {
                $this->poblarUsuarios();
            }

            // Poblar empleados si no existen
            if (Empleado::count() == 0) {
                $this->poblarEmpleado();
            }

            // Poblar clientes si no existen
            if (Cliente::count() == 0) {
                $this->poblarCliente();
            }

               if (Categoria::count() == 0) {
                $this->poblarCategorias();
            }

            if (Producto::count() == 0) {
                $this->poblarProductos();
            }

            if (Almacen::count() == 0) {
                $this->poblarAlmacenes();
            }
               // Nuevos modelos
            if (DetalleAlmacen::count() == 0) {
                $this->poblarDetalleAlmacen();
            }

            if (Venta::count() == 0) {
                $this->poblarVentas();
            }

            if (DetalleVenta::count() == 0) {
                $this->poblarDetalleVenta();
            }

            \DB::commit();

            return redirect()->route('poblacion.index')
                ->with('success', 'Todos los modelos poblados exitosamente');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('poblacion.index')
                ->with('error', 'Error al poblar los datos: ' . $e->getMessage());
        }
    }

    // Limpiar todos los datos (opcional)
    public function limpiarDatos()
    {
        try {
            \DB::beginTransaction();
            // Eliminar en orden correcto por las relaciones
            DetalleVenta::truncate();
            Venta::truncate();
            DetalleAlmacen::truncate();
            Producto::truncate();
            Categoria::truncate();
            Almacen::truncate();
            Empleado::truncate();
            Cliente::truncate();
            User::truncate();
            TipoEmpleado::truncate();

            \DB::commit();

            return redirect()->route('poblacion.index')
                ->with('success', 'Todos los datos han sido eliminados');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('poblacion.index')
                ->with('error', 'Error al limpiar los datos: ' . $e->getMessage());
        }
    }
}