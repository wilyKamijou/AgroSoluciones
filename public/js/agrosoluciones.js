// ===== INICIALIZACIÓN PRINCIPAL =====
document.addEventListener('DOMContentLoaded', () => {
  // Crear fondo animado
  createAnimatedBackground();
  
  // Añadir efectos glow
  addGlowEffect();
  
  // Mejorar efectos hover
  enhanceHoverEffects();
  
  // Configurar sistema de pestañas
  setupCategoryTabs();
  
  // Configurar scroll suave
  setupSmoothScroll();
  
  // Configurar efectos de scroll
  setupScrollEffects();
  
  // Configurar animaciones iniciales
  initializeAnimations();
});

// ===== CREAR FONDO ANIMADO =====
function createAnimatedBackground() {
  const bg = document.getElementById('animatedBg');
  if (!bg) return;
  
  // Crear partículas flotantes
  for (let i = 0; i < 20; i++) {
    const particle = document.createElement('div');
    particle.className = 'bg-particle';
    const size = Math.random() * 100 + 50;
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.left = `${Math.random() * 100}%`;
    particle.style.top = `${Math.random() * 100}%`;
    particle.style.animationDuration = `${Math.random() * 30 + 20}s`;
    particle.style.animationDelay = `${Math.random() * 5}s`;
    bg.appendChild(particle);
  }
  
  // Crear hojas flotantes
  for (let i = 0; i < 10; i++) {
    const leaf = document.createElement('div');
    leaf.className = 'bg-leaf';
    leaf.innerHTML = '<i class="fas fa-leaf"></i>';
    leaf.style.left = `${Math.random() * 100}%`;
    leaf.style.top = `${Math.random() * 100}%`;
    leaf.style.animationDuration = `${Math.random() * 40 + 30}s`;
    leaf.style.animationDelay = `${Math.random() * 10}s`;
    leaf.style.fontSize = `${Math.random() * 40 + 30}px`;
    leaf.style.opacity = Math.random() * 0.05 + 0.01;
    bg.appendChild(leaf);
  }
  
  // Crear líneas de gradiente
  for (let i = 0; i < 5; i++) {
    const line = document.createElement('div');
    line.className = 'gradient-line';
    line.style.top = `${Math.random() * 100}%`;
    line.style.width = `${Math.random() * 300 + 100}px`;
    line.style.left = `${Math.random() * 100}%`;
    line.style.transform = `rotate(${Math.random() * 360}deg)`;
    document.body.appendChild(line);
  }
}

// ===== EFECTO GLOW EN ELEMENTOS AL HOVER =====
function addGlowEffect() {
  const elements = document.querySelectorAll('.nav-btn, .cultivo-btn, .form-submit, .floating-btn');
  
  elements.forEach(element => {
    element.addEventListener('mouseenter', () => {
      const glow = document.createElement('div');
      glow.style.position = 'absolute';
      glow.style.top = '0';
      glow.style.left = '0';
      glow.style.width = '100%';
      glow.style.height = '100%';
      glow.style.borderRadius = getComputedStyle(element).borderRadius;
      glow.style.background = 'radial-gradient(circle at center, rgba(0, 168, 89, 0.3) 0%, transparent 70%)';
      glow.style.zIndex = '-1';
      glow.style.opacity = '0';
      glow.style.animation = 'pulse 1s infinite';
      
      element.style.position = 'relative';
      element.appendChild(glow);
      
      setTimeout(() => {
        glow.style.opacity = '1';
      }, 10);
    });
    
    element.addEventListener('mouseleave', () => {
      const glow = element.querySelector('div');
      if (glow) {
        glow.style.opacity = '0';
        setTimeout(() => glow.remove(), 300);
      }
    });
  });
}

// ===== CONFIGURAR PESTAÑAS DE CATEGORÍAS =====
function setupCategoryTabs() {
  const tabs = document.querySelectorAll('.category-tab');
  if (!tabs.length) return;
  
  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      // Efecto de click
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
      }, 200);
      
      // Remover activo de todos
      tabs.forEach(t => t.classList.remove('active'));
      // Agregar activo al clickeado
      this.classList.add('active');
      
      const categoryId = this.dataset.category;
      
      // Efecto de transición
      const grids = document.querySelectorAll('.categoria-productos');
      grids.forEach(grid => {
        if (grid.style.display !== 'none') {
          grid.style.opacity = '0';
          grid.style.transform = 'translateY(20px) scale(0.98)';
          grid.style.filter = 'blur(5px)';
          
          setTimeout(() => {
            grid.style.display = 'none';
            grid.style.filter = 'none';
          }, 300);
        }
      });
      
      // Mostrar nueva categoría con efecto
      const targetGrid = document.getElementById(`categoria-${categoryId}`);
      if (targetGrid) {
        setTimeout(() => {
          targetGrid.style.display = 'grid';
          setTimeout(() => {
            targetGrid.style.opacity = '1';
            targetGrid.style.transform = 'translateY(0) scale(1)';
            targetGrid.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            
            // Animar productos individualmente
            const productos = targetGrid.querySelectorAll('.producto-card');
            productos.forEach((producto, index) => {
              producto.style.opacity = '0';
              producto.style.transform = 'translateY(20px) scale(0.9)';
              
              setTimeout(() => {
                producto.style.opacity = '1';
                producto.style.transform = 'translateY(0) scale(1)';
                producto.style.transition = `all 0.4s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s`;
              }, 50);
            });
          }, 50);
        }, 300);
      }
    });
  });
}

// ===== EFECTO HOVER MEJORADO =====
function enhanceHoverEffects() {
  const cards = document.querySelectorAll('.producto-card, .nosotros-card, .cultivo-card');
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      card.style.setProperty('--mouse-x', `${x}px`);
      card.style.setProperty('--mouse-y', `${y}px`);
      
      // Efecto de elevación
      card.style.zIndex = '100';
      card.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.15)';
    });
    
    card.addEventListener('mouseleave', () => {
      card.style.zIndex = '';
      card.style.boxShadow = '';
    });
  });
}

// ===== CONFIGURAR SCROLL SUAVE =====
function setupSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        // Efecto de click
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = '';
        }, 200);
        
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
      }
    });
  });
}

// ===== CONFIGURAR EFECTOS DE SCROLL =====
function setupScrollEffects() {
  const navbar = document.getElementById('navbar');
  const backToTop = document.getElementById('back-to-top');
  
  if (!navbar || !backToTop) return;
  
  window.addEventListener('scroll', () => {
    // Navbar scroll effect
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
      navbar.style.backdropFilter = 'blur(20px)';
    } else {
      navbar.classList.remove('scrolled');
      navbar.style.backdropFilter = 'blur(20px)';
    }
    
    // Back to top button
    if (window.scrollY > 300) {
      backToTop.style.display = 'flex';
      backToTop.style.opacity = '1';
    } else {
      backToTop.style.opacity = '0';
      setTimeout(() => {
        if (window.scrollY <= 300) {
          backToTop.style.display = 'none';
        }
      }, 300);
    }
  });
}

// ===== INICIALIZAR ANIMACIONES =====
function initializeAnimations() {
  // Observador de intersección para animaciones
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const animation = el.dataset.animate || 'fadeInUp';
        const delay = el.dataset.delay || 0;
        
        setTimeout(() => {
          el.classList.remove('hidden');
          el.classList.add(`animate-${animation}`);
        }, delay * 1000);
        
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -100px 0px' });
  
  document.querySelectorAll('.hidden').forEach(el => observer.observe(el));
}