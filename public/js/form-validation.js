// ===== VALIDACIÓN DE FORMULARIO =====
document.addEventListener('DOMContentLoaded', () => {
  const contactForm = document.getElementById('contactForm');
  if (!contactForm) return;
  
  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    let isValid = true;
    const inputs = this.querySelectorAll('input, textarea, select');
    
    // Resetear estilos de error
    inputs.forEach(input => {
      input.style.borderColor = '';
      input.style.boxShadow = '';
      input.classList.remove('animate-shake');
    });
    
    // Validar campos
    inputs.forEach(input => {
      if (input.hasAttribute('required') && !input.value.trim()) {
        isValid = false;
        showInputError(input, 'Este campo es requerido');
      }
      
      if (input.type === 'email' && input.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(input.value)) {
          isValid = false;
          showInputError(input, 'Ingrese un email válido');
        }
      }
    });
    
    if (isValid) {
      submitForm(this);
    }
    
    return false;
  });
});

// Mostrar error en input
function showInputError(input, message) {
  input.style.borderColor = '#dc3545';
  input.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
  
  // Efecto shake
  input.classList.add('animate-shake');
  setTimeout(() => input.classList.remove('animate-shake'), 500);
  
  // Mostrar tooltip de error
  let errorTooltip = input.parentNode.querySelector('.error-tooltip');
  if (!errorTooltip) {
    errorTooltip = document.createElement('div');
    errorTooltip.className = 'error-tooltip';
    errorTooltip.style.cssText = `
      position: absolute;
      top: 100%;
      left: 0;
      background: #dc3545;
      color: white;
      padding: 0.5rem;
      border-radius: 4px;
      font-size: 0.8rem;
      margin-top: 5px;
      z-index: 1000;
      white-space: nowrap;
    `;
    input.parentNode.style.position = 'relative';
    input.parentNode.appendChild(errorTooltip);
  }
  errorTooltip.textContent = message;
  errorTooltip.style.display = 'block';
  
  // Ocultar tooltip después de 3 segundos
  setTimeout(() => {
    errorTooltip.style.display = 'none';
  }, 3000);
}

// Enviar formulario
function submitForm(form) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalHtml = submitBtn.innerHTML;
  
  // Efecto de carga
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
  submitBtn.disabled = true;
  submitBtn.style.opacity = '0.8';
  
  // Simular envío (en producción, aquí iría fetch o axios)
  setTimeout(() => {
    // Restaurar botón
    submitBtn.innerHTML = originalHtml;
    submitBtn.disabled = false;
    submitBtn.style.opacity = '1';
    
    // Mostrar mensaje de éxito
    const successMessage = document.createElement('div');
    successMessage.className = 'alert-success';
    successMessage.innerHTML = '<i class="fas fa-check-circle"></i> ¡Mensaje enviado correctamente!';
    form.prepend(successMessage);
    
    // Limpiar formulario
    form.reset();
    
    // Eliminar mensaje después de 5 segundos
    setTimeout(() => {
      successMessage.remove();
    }, 5000);
    
    // Aquí iría el envío real:
     form.submit();
    
  }, 1500);
}