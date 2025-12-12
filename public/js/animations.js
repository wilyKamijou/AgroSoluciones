// ===== ANIMACIONES Y EFECTOS VISUALES =====

// Efecto de partículas (opcional)
function createParticleEffect() {
  if (Math.random() > 0.97) {
    const particle = document.createElement('div');
    particle.style.cssText = `
      position: fixed;
      width: 2px;
      height: 2px;
      background: var(--primary);
      border-radius: 50%;
      pointer-events: none;
      z-index: -1;
      opacity: 0.3;
    `;
    particle.style.left = `${Math.random() * 100}vw`;
    particle.style.top = '0';
    document.body.appendChild(particle);
    
    const duration = Math.random() * 3 + 2;
    particle.animate([
      { transform: 'translateY(0) scale(1)', opacity: 0.3 },
      { transform: `translateY(${window.innerHeight}px) scale(0)`, opacity: 0 }
    ], { duration: duration * 1000 });
    
    setTimeout(() => particle.remove(), duration * 1000);
  }
}

// Iniciar efecto de partículas
function startParticleEffects() {
  // Crear algunas partículas iniciales
  for (let i = 0; i < 5; i++) {
    setTimeout(createParticleEffect, i * 500);
  }
  
  // Intervalo para partículas
  setInterval(createParticleEffect, 2000);
}

// Efecto de typing en título hero
function typeWriterEffect() {
  const title = document.querySelector('.hero-title');
  if (!title) return;
  
  const text = title.textContent;
  title.textContent = '';
  let i = 0;
  
  function type() {
    if (i < text.length) {
      title.textContent += text.charAt(i);
      i++;
      setTimeout(type, 50);
    }
  }
  
  // Iniciar typing cuando el elemento sea visible
  const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      type();
      observer.unobserve(title);
    }
  });
  observer.observe(title);
}

// Efecto parallax en scroll
function parallaxEffect() {
  const scrolled = window.pageYOffset;
  const hero = document.querySelector('.hero');
  
  if (hero) {
    hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
  }
  
  // Mover partículas del fondo
  const particles = document.querySelectorAll('.bg-particle, .bg-leaf');
  particles.forEach(particle => {
    const speed = parseFloat(particle.style.animationDuration) || 25;
    const moveY = scrolled * 0.1;
    particle.style.transform = `translateY(${moveY}px)`;
  });
}

// Inicializar animaciones avanzadas
document.addEventListener('DOMContentLoaded', () => {
  // Efecto typing
  typeWriterEffect();
  
  // Efecto de partículas (opcional - descomentar si se desea)
  // startParticleEffects();
  
  // Añadir parallax al scroll
  window.addEventListener('scroll', parallaxEffect);
});