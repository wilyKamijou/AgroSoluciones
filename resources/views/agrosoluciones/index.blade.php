<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/css/agrosoluciones.css" />
  <link rel="stylesheet" href="/css/productos.css">
  <link rel="stylesheet" href="/css/formulario.css">

  <script src="/js/agrosoluciones.js"></script>
  <title>AGROSOLUCIONES</title>
</head>

<body>
  <!-- NAV -->
  <nav>
    <a href="#empresa">NUESTRA EMPRESA</a>
    <a href="#agroquimicos">AGROQUIMICOS</a>

    <a href="#contacto">CONTACTO</a>
  </nav>

  <!-- LINKS DE INTERÉS -->
  <div class="links-interes">
    <p>LINKS DE INTERÉS</p>

    <a class="btn" href="https://www.meteored.com.bo/">PRONÓSTICO DEL TIEMPO</a>
    <a class="btn" href="https://www.inumet.gub.uy/clima/recursos-hidricos/boletin-pluviometrico">REGISTRO DE LLUVIAS</a>
    <a class="btn" href="https://www.cmegroup.com/markets/agriculture/grains/corn.html">MERCADO DE GRANOS</a>
  </div>

  <!-- LOGO Y NOMBRE -->
  <div class="logo">AGROSOLUCIONES</div>

  <!-- TABLAS DE CULTIVO -->
  <div class="tablas">
    <h3>TABLAS DE CULTIVO</h3>
    <div class="cultivos">
      <a class="card maiz" href="https://i.postimg.cc/XrgJkT3S/tablamaiz.png" target="_blank" rel="noopener noreferrer">
        MAÍZ
      </a>

      <a class="card soya" href="https://i.postimg.cc/G4j2xZrd/tablasoya.png" target="_blank" rel="noopener noreferrer">
        SOYA
      </a>

      <a class="card sorgo" href="https://i.postimg.cc/Hrtx9fHC/tablasorgo.png" target="_blank" rel="noopener noreferrer">
        SORGO
      </a>
    </div>
  </div>

  <!-- SECCIÓN NOSOTROS -->
  <section class="nosotros">
    <img class="hojas" src="https://i.postimg.cc/PJ7mD8Sj/hojas.png" alt="Hojas" />

    <h2 class="titulo-central" id="empresa">
      AGROSOLUCIONES, LA FUERZA DEL AGRO
    </h2>

    <div class="contenedor-nosotros">
      <!-- COLUMNA 1 -->
      <div class="columna">
        <h3>NUESTRA EMPRESA</h3>
        <p>
          AGROSOLUCIONES SRL, es una empresa dedicada a la importación de
          productos agroquímicos con base en Santa Cruz de la Sierra, inicia
          sus actividades de forma comercial el 2 de octubre de 2013, pasando
          por una restructuración estratégica el 30 de noviembre de 2015,
          dando una mejor estructura a la firma tanto en la administración
          como en la parte comercial, para poder dar un mejor servicio y estar
          más cerca del agricultor.
        </p>
        <p>
          AGROSOLUCIONES SRL, cuenta con un excelente portafolio de productos
          los cuales en su mayoría provienen de la República de Uruguay,
          siendo nuestro principal proveedor TAMPA S.A.
        </p>
        <p>
          Un estricto control de producción ha garantizado productos del más
          alto nivel de calidad, avalado esto por su permanente crecimiento en
          nuestro mercado y la región. Todos estos años han confirmado el
          esfuerzo realizado y nos compromete a seguir adelante con el proceso
          de mejora continua para garantizarles a todos nuestros clientes
          productos confiables y bien formulados.
        </p>
      </div>

      <!-- COLUMNA 2 -->
      <div class="columna">
        <h3>VISIÓN</h3>
        <p>
          Convertirnos en una empresa líder del sector agrícola mediante la
          innovación y permanente transferencia de tecnología a nuestros
          clientes y ser reconocidos como un proveedor de productos
          sustentables y de primera calidad.
        </p>

        <h3>MISIÓN</h3>
        <p>
          Somos una empresa dedicada a la comercialización de insumos
          agrícolas comprometida en satisfacer las necesidades de nuestros
          clientes respetando las normas de seguridad laboral y de protección
          al medio ambiente brindando soluciones innovadoras que aportan al
          desarrollo de la actividad.
        </p>

        <h3>VALORES</h3>
        <p>
          Poseer un comportamiento ético y responsable en lo individual y
          organizacional. Mejorar en forma continua como empresa y como
          personas.
        </p>
      </div>

      <!-- COLUMNA 3 -->
      <div class="columna">
        <h3>RESPONSABILIDAD<br />SOCIAL<br />EMPRESARIAL<br />(RSE)</h3>

        <p class="mini">Afiliados a:</p>
        <p class="mini">APIA</p>
        <p class="mini">Fundación Porsaleu</p>
        <p class="mini">
          <a href="https://www.apia-bolivia.org" target="_blank">www.apia-bolivia.org</a>
        </p>
      </div>
    </div>
  </section>

  <!-- SECCIÓN AGROQUÍMICOS -->
  <section class="agroquimicos" id="agroquimicos">
    <h2 class="titulo-agro">AGROQUÍMICOS</h2>

    <p class="descripcion-agro">
      La agroquímica es la ciencia química que estudia las causas y efectos de
      las reacciones bioquímicas que afectan al crecimiento tanto animal como
      vegetal. En esta rama se incluyen tanto los diferentes abonos o
      fertilizantes como las sustancias fitosanitarias como herbicidas,
      insecticidas, coadyuvantes, curasemillas y fungicidas.
    </p>


    <section class="productos-categoria" id="coadyuvantes">
      {{-- MENU DE CATEGORÍAS DINÁMICO --}}
      <div class="menu-categorias">
        @foreach ($tipos as $tipo)
        @php
        $idCategoria = Str::slug($tipo->nombreCat, '-');
        @endphp

        <a href="#{{ $tipo->id_categoria }}">
          {{ strtoupper($tipo->nombreCat) }}
        </a>
        @if(!$loop->last)
        /
        @endif
        @endforeach
      </div>

      <section class="productos-categoria" id="{{$tipo->id_categoria}}">

        @foreach ($tipos as $tipo)
        <h2 class="titulo-categoria">{{ $tipo->nombreCat }} </h2>
        <div class="grid-productos">

          @foreach ($tipo->productos as $producto)

          <div class="producto">

            <h3 class="principio">
              <strong>{{$producto->nombrePr}}</strong>
            </h3>

            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombrePr }}" />

            <h3 class="subtitulo-categoria">{{ $producto->nombreTecnico }}</h3>

            <p>
              {{ $producto->consentracionQuimica }} <br />
              {{ $producto->unidadMedida }}
            </p>
          </div>
          @endforeach

        </div>

        @endforeach
      </section>


    </section>

    <div class="formulario-container">

      @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
      @endif

      <h2>Enviar Queja o Consulta</h2>

      <form action="/enviar" method="POST">
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="email">Correo</label>
        <input type="email" name="email" id="email" required>

        <label for="asunto">Asunto</label>
        <input type="text" name="asunto" id="asunto" required>

        <label for="mensaje">Mensaje</label>
        <textarea name="mensaje" id="mensaje" rows="5" required></textarea>

        <button type="submit">Enviar</button>
      </form>

    </div>
    <section class="contacto" id="contacto">
      <h2 class="titulo-contacto">CONTACTO</h2>

      <div class="contacto-grid">
        <!-- DIRECCIÓN -->
        <div class="contacto-item">
          <h3>DIRECCIÓN</h3>
          <img class="icono-contacto" src="https://cdn-icons-png.flaticon.com/512/684/684908.png" />
          <p>
            Av. Final Beni # 7800 <br />
            Zona Barrio Nueva Jerusalén <br />
            Santa Cruz - Bolivia
          </p>
        </div>

        <!-- TEL/FAX -->
        <div class="contacto-item">
          <h3>TEL/CEL</h3>
          <img class="icono-contacto" src="https://cdn-icons-png.flaticon.com/512/597/597177.png" />
          <p>
            Celular: <br />
            70899084 <br />
            70059647
          </p>
        </div>

        <!-- EMAIL -->
        <div class="contacto-item">
          <h3>E-MAILS</h3>
          <img class="icono-contacto" src="https://cdn-icons-png.flaticon.com/512/561/561127.png" />
          <p>
            info@AGROSOLUCIONES-bo.com <br />
            Ing. William T. Jimenez
          </p>
        </div>

        <!-- HORARIO -->
        <div class="contacto-item">
          <h3>HORARIO</h3>
          <img class="icono-contacto" src="https://cdn-icons-png.flaticon.com/512/2088/2088617.png" />
          <p>
            Lunes a Viernes <br />
            08:30 - 16:30 <br />
            Sábado <br />
            8:30 - 12:00 <br />
            Domingo cerrado
          </p>
        </div>
      </div>
    </section>

    <script src="script.js"></script>
</body>

</html>