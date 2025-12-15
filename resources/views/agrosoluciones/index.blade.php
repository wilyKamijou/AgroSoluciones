<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/css/agrosoluciones.css">
  <link rel="stylesheet" href="/css/animations.css">
  <link rel="stylesheet" href="/css/components.css">
  <title>AGROSOLUCIONES | Soluciones Agrícolas</title>
</head>

<body>
  <!-- FONDO ANIMADO -->
  <div class="animated-bg" id="animatedBg"></div>

  <!-- NAVBAR -->
  <nav class="navbar" id="navbar">
  <div class="nav-logo">
    <i class="fas fa-seedling"></i>
    <span>AGROSOLUCIONES</span>
  </div>
  <div class="nav-menu">
    <a href="#empresa" class="nav-link">Empresa</a>
    <a href="#productos" class="nav-link">Productos</a>
    <a href="#cultivos" class="nav-link">Cultivos</a>
    <a href="#contacto" class="nav-link">Contacto</a>
    
    <!-- Elemento que será modificado por JavaScript -->
    <div id="boton-navbar">
      <a href="/home" class="nav-btn">
        <i class="fas fa-chart-line"></i> AgroSoluciones
      </a>
    </div>
  </div>
</nav>


  <!-- HERO -->
  <section class="hero section">
    <div class="container">
      <div class="hero-content hidden" data-animate="slideInLeft">
        <h1 class="hero-title text-gradient">
          Innovación Agrícola<br>para el Futuro
        </h1>
        <p class="hero-subtitle">
          Soluciones tecnológicas y productos de alta calidad para maximizar el rendimiento
          de tus cultivos. Más de 10 años cultivando éxito.
        </p>
        <a href="#productos" class="nav-btn animate-pulse">
          <i class="fas fa-arrow-right"></i> Ver Productos
        </a>

        <div class="hero-stats">
          <div class="stat-item" style="--i: 1;">
            <span class="stat-number">10+</span>
            <span class="stat-label">Años de Experiencia</span>
          </div>
          <div class="stat-item" style="--i: 2;">
            <span class="stat-number">50+</span>
            <span class="stat-label">Productos</span>
          </div>
          <div class="stat-item" style="--i: 3;">
            <span class="stat-number">500+</span>
            <span class="stat-label">Clientes</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CULTIVOS -->
  <section id="cultivos" class="section" style="background: var(--white);">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title text-gradient">Tablas de Cultivo</h2>
        <p class="section-subtitle">Información técnica especializada para cada cultivo</p>
      </div>

      <div class="cultivos-grid">
        <div class="cultivo-card hidden" data-animate="fadeInUp">
          <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Maíz" class="cultivo-image">
          <div class="cultivo-content">
            <h3 class="cultivo-title">Maíz</h3>
            <p>Guía completa para el cultivo óptimo de maíz</p>
            <a href="https://i.postimg.cc/XrgJkT3S/tablamaiz.png" target="_blank" class="cultivo-btn">
              Ver Tabla <i class="fas fa-external-link-alt"></i>
            </a>
          </div>
        </div>

        <div class="cultivo-card hidden" data-animate="fadeInUp" data-delay="0.1">
          <img src="https://cdn.pixabay.com/photo/2017/02/05/11/11/soybeans-2039641_1280.jpg" alt="Soya" class="cultivo-image">
          <div class="cultivo-content">
            <h3 class="cultivo-title">Soya</h3>
            <p>Tablas técnicas para el cultivo de soya</p>
            <a href="https://i.postimg.cc/G4j2xZrd/tablasoya.png" target="_blank" class="cultivo-btn">
              Ver Tabla <i class="fas fa-external-link-alt"></i>
            </a>
          </div>
        </div>

        <div class="cultivo-card hidden" data-animate="fadeInUp" data-delay="0.2">
          <img src="https://cdn.pixabay.com/photo/2016/08/04/07/24/grain-1568509_1280.jpg" alt="Sorgo" class="cultivo-image">
          <div class="cultivo-content">
            <h3 class="cultivo-title">Sorgo</h3>
            <p>Información técnica para cultivo de sorgo</p>
            <a href="https://i.postimg.cc/Hrtx9fHC/tablasorgo.png" target="_blank" class="cultivo-btn">
              Ver Tabla <i class="fas fa-external-link-alt"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- NOSOTROS -->
  <section id="empresa" class="section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title text-gradient">Nuestra Empresa</h2>
        <p class="section-subtitle">Innovación y calidad en soluciones agrícolas</p>
      </div>

      <div class="nosotros-grid">
        <div class="nosotros-card hidden" data-animate="slideInLeft">
          <div class="card-icon">
            <i class="fas fa-bullseye"></i>
          </div>
          <h3 class="card-title">Nuestra Historia</h3>
          <p>Desde 2013, nos especializamos en importar productos agroquímicos de alta calidad para el mercado boliviano, trabajando con los mejores proveedores internacionales.</p>
        </div>

        <div class="nosotros-card hidden" data-animate="fadeInUp">
          <div class="card-icon">
            <i class="fas fa-eye"></i>
          </div>
          <h3 class="card-title">Visión & Misión</h3>
          <p>Ser líderes en innovación agrícola mediante tecnología de punta y productos sustentables, comprometidos con la excelencia y el desarrollo sostenible.</p>
        </div>

        <div class="nosotros-card hidden" data-animate="slideInRight">
          <div class="card-icon">
            <i class="fas fa-hands-helping"></i>
          </div>
          <h3 class="card-title">Responsabilidad Social</h3>
          <p>Trabajamos con APIA y Fundación Porsaleu para promover prácticas agrícolas responsables y apoyar a las comunidades productoras.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- PRODUCTOS -->
  <section id="productos" class="section" style="background: var(--white);">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title text-gradient">Nuestros Productos</h2>
        <p class="section-subtitle">Soluciones especializadas para cada necesidad agrícola</p>
      </div>

      <div class="category-tabs">
        @foreach ($tipos as $tipo)
        <button class="category-tab {{ $loop->first ? 'active' : '' }}" data-category="{{ $tipo->id_categoria }}">
          <span>{{ $tipo->nombreCat }}</span>
        </button>
        @endforeach
      </div>

      @foreach ($tipos as $tipo)
      <div class="categoria-productos" id="categoria-{{ $tipo->id_categoria }}" style="{{ !$loop->first ? 'display: none;' : '' }}">

        @forelse ($tipo->productos as $producto)
        <div class="producto-card hidden" data-animate="fadeInUp" data-delay="{{ $loop->index * 0.1 }}" data-category="{{ $tipo->id_categoria }}">
          <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombrePr }}" class="producto-img" loading="lazy" onerror="this.src='https://via.placeholder.com/300x200/4CAF50/FFFFFF?text=AGRO+PRODUCTO'">
          <h3 class="producto-title">{{ $producto->nombrePr }}</h3>
          <p class="producto-subtitle">{{ $producto->nombreTecnico }}</p>
          <div class="producto-details">
            {{ $producto->consentracionQuimica }} • {{ $producto->unidadMedida }}
          </div>
          @if($producto->descripcionPr)
          <div class="producto-descripcion">
            {{ Str::limit($producto->descripcionPr, 100) }}
          </div>
          @endif
        </div>
        @empty
        <div class="empty-state">
          <i class="fas fa-seedling"></i>
          <p>No hay productos en esta categoría</p>
        </div>
        @endforelse
      </div>
      @endforeach
    </div>
  </section>

  <!-- FORMULARIO -->
  <section class="section form-section">
    <div class="container">
      <div class="form-container hidden" data-animate="fadeInUp">
        <h2 class="form-title text-gradient">Contáctanos</h2>

        @if(session('success'))
        <div class="alert-success">
          <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <form action="/enviar" method="POST" id="contactForm">
          @csrf

          <div class="form-group">
            <label class="form-label">Nombre Completo</label>
            <input type="text" name="nombre" class="form-input" placeholder="Tu nombre" required>
          </div>

          <div class="form-group">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-input" placeholder="ejemplo@email.com" required>
          </div>

          <div class="form-group">
            <label class="form-label">Asunto</label>
            <select name="asunto" class="form-input" required>
              <option value="">Selecciona un asunto</option>
              <option value="consulta">Consulta General</option>
              <option value="cotizacion">Solicitud de Cotización</option>
              <option value="tecnica">Consulta Técnica</option>
              <option value="soporte">Soporte</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Mensaje</label>
            <textarea name="mensaje" class="form-input" rows="4" placeholder="Describe tu consulta aquí..." required></textarea>
          </div>

          <button type="submit" class="form-submit">
            <i class="fas fa-paper-plane"></i> Enviar Mensaje
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- CONTACTO -->
  <section id="contacto" class="section contacto-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Contáctanos</h2>
        <p class="section-subtitle">Estamos aquí para ayudarte</p>
      </div>

      <div class="contacto-grid">
        <div class="contacto-item hidden" data-animate="fadeInUp">
          <div class="contacto-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <h3 class="contacto-title">Dirección</h3>
          <p>Av. Final Beni #7800<br>Santa Cruz - Bolivia</p>
        </div>

        <div class="contacto-item hidden" data-animate="fadeInUp" data-delay="0.1">
          <div class="contacto-icon">
            <i class="fas fa-phone"></i>
          </div>
          <h3 class="contacto-title">Teléfono</h3>
          <p>+591 70899084<br>+591 70059647</p>
        </div>

        <div class="contacto-item hidden" data-animate="fadeInUp" data-delay="0.2">
          <div class="contacto-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <h3 class="contacto-title">Email</h3>
          <p>info@agrosoluciones-bo.com</p>
        </div>

        <div class="contacto-item hidden" data-animate="fadeInUp" data-delay="0.3">
          <div class="contacto-icon">
            <i class="fas fa-clock"></i>
          </div>
          <h3 class="contacto-title">Horario</h3>
          <p>Lun-Vie: 8:30-16:30<br>Sáb: 8:30-12:00</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="nav-logo footer-logo">
          <i class="fas fa-seedling"></i>
          <span>AGROSOLUCIONES</span>
        </div>

        <div class="footer-links">
          <a href="#empresa" class="footer-link">Empresa</a>
          <a href="#productos" class="footer-link">Productos</a>
          <a href="#cultivos" class="footer-link">Cultivos</a>
          <a href="#contacto" class="footer-link">Contacto</a>
          <a href="https://www.apia-bolivia.org" target="_blank" class="footer-link">APIA</a>
        </div>

        <p class="footer-copyright">
          © 2024 AGROSOLUCIONES SRL. Todos los derechos reservados.
        </p>
      </div>
    </div>
  </footer>

  <!-- BOTONES FLOTANTES -->
  <div class="floating-buttons">
    <a href="#top" class="floating-btn" id="back-to-top">
      <i class="fas fa-arrow-up"></i>
    </a>
    <a href="https://wa.me/59170899084" class="floating-btn whatsapp" target="_blank">
      <i class="fab fa-whatsapp"></i>
    </a>
  </div>

  <script src="/js/animations.js"></script>
  <!--<script src="/js/form-validation.js"></script>-->
  <script src="/js/agrosoluciones.js"></script>
</body>

</html>
<script>
async function actualizarNavbar() {
  try {
    const response = await fetch('/api/sesion');
    const data = await response.json();
    
    const navMenu = document.querySelector('.nav-menu');
    const botonExistente = document.querySelector('.nav-btn');
    
    if (botonExistente) {
      botonExistente.remove();
    }
    
    let nuevoBoton;
    if (data.logeado) {
      nuevoBoton = document.createElement('a');
      nuevoBoton.href = '/mi-cuenta/perfil';
      nuevoBoton.className = 'nav-btn nav-btn-empleado';
      nuevoBoton.innerHTML = '<i class="fas fa-user"></i> Mi Cuenta';
    } else {
      nuevoBoton = document.createElement('a');
      nuevoBoton.href = '/home';
      nuevoBoton.className = 'nav-btn';
      nuevoBoton.innerHTML = '<i class="fas fa-chart-line"></i> AgroSoluciones';
    }
    
    navMenu.appendChild(nuevoBoton);
  } catch (error) {
    console.error('Error al verificar sesión:', error);
  }
}

// Llamar al cargar la página
document.addEventListener('DOMContentLoaded', actualizarNavbar);
</script>