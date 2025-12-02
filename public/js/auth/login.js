// Login Script - AgroSoluciones
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const loginForm = document.querySelector('form');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const rememberCheckbox = document.getElementById('remember');
    const customCheckbox = document.querySelector('.custom-checkbox');
    const loginBtn = document.querySelector('.login-btn');
    const errorMessage = document.querySelector('.error-message');
    
    // Inicializar partículas (efecto decorativo)
    initParticles();
    
    // Verificar si hay credenciales guardadas
    checkSavedCredentials();
    
    // Event Listeners
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
    
    if (customCheckbox) {
        customCheckbox.addEventListener('click', toggleRemember);
    }
    
    // Validación en tiempo real
    if (emailInput) {
        emailInput.addEventListener('input', validateEmail);
        emailInput.addEventListener('blur', validateEmail);
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', validatePassword);
        passwordInput.addEventListener('blur', validatePassword);
    }
    
    // Efecto de focus en inputs
    document.querySelectorAll('.input-field').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Función para validar email
    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            setInputState(emailInput, false, 'El email es requerido');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            setInputState(emailInput, false, 'Ingresa un email válido');
            return false;
        }
        
        setInputState(emailInput, true);
        return true;
    }
    
    // Función para validar contraseña
    function validatePassword() {
        const password = passwordInput.value;
        
        if (!password) {
            setInputState(passwordInput, false, 'La contraseña es requerida');
            return false;
        }
        
        if (password.length < 6) {
            setInputState(passwordInput, false, 'Mínimo 6 caracteres');
            return false;
        }
        
        setInputState(passwordInput, true);
        return true;
    }
    
    // Función para cambiar estado del input
    function setInputState(inputElement, isValid, errorText = '') {
        const container = inputElement.parentElement;
        
        if (isValid) {
            inputElement.classList.add('valid');
            inputElement.classList.remove('invalid');
            hideError();
        } else {
            inputElement.classList.add('invalid');
            inputElement.classList.remove('valid');
            if (errorText) {
                showError(errorText);
            }
        }
    }
    
    // Función para mostrar error
    function showError(message) {
        if (errorMessage) {
            errorMessage.textContent = message;
            errorMessage.classList.add('show');
            
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                errorMessage.classList.remove('show');
            }, 5000);
        }
    }
    
    // Función para ocultar error
    function hideError() {
        if (errorMessage) {
            errorMessage.classList.remove('show');
        }
    }
    
    // Función para manejar el submit del login
    function handleLoginSubmit(e) {
        e.preventDefault();
        
        // Validar antes de enviar
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        
        if (!isEmailValid || !isPasswordValid) {
            showError('Por favor, corrige los errores del formulario');
            return;
        }
        
        // Mostrar loading en el botón
        const originalText = loginBtn.innerHTML;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
        loginBtn.disabled = true;
        
        // Simular delay para mostrar animación (en producción quitar esto)
        setTimeout(() => {
            // En producción, el formulario se enviaría normalmente
            loginForm.submit();
        }, 1500);
    }
    
    // Función para toggle del checkbox personalizado
    function toggleRemember() {
        const isChecked = customCheckbox.classList.contains('checked');
        
        if (isChecked) {
            customCheckbox.classList.remove('checked');
            if (rememberCheckbox) rememberCheckbox.checked = false;
        } else {
            customCheckbox.classList.add('checked');
            if (rememberCheckbox) rememberCheckbox.checked = true;
        }
    }
    
    // Verificar credenciales guardadas
    function checkSavedCredentials() {
        const savedEmail = localStorage.getItem('agrosol_email');
        const savedRemember = localStorage.getItem('agrosol_remember') === 'true';
        
        if (savedEmail && savedRemember && emailInput) {
            emailInput.value = savedEmail;
            if (rememberCheckbox) rememberCheckbox.checked = true;
            if (customCheckbox) customCheckbox.classList.add('checked');
            passwordInput.focus();
        }
    }
    
    // Guardar credenciales si "Recordarme" está activado
    if (rememberCheckbox) {
        rememberCheckbox.addEventListener('change', function() {
            if (this.checked && emailInput.value) {
                localStorage.setItem('agrosol_email', emailInput.value);
                localStorage.setItem('agrosol_remember', 'true');
            } else {
                localStorage.removeItem('agrosol_email');
                localStorage.removeItem('agrosol_remember');
            }
        });
    }
    
    // Función para crear partículas decorativas
    function initParticles() {
        const particlesContainer = document.querySelector('.particles');
        if (!particlesContainer) return;
        
        const particleCount = 15;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Tamaño aleatorio
            const size = Math.random() * 6 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Posición inicial aleatoria
            particle.style.left = `${Math.random() * 100}%`;
            
            // Retraso de animación aleatorio
            particle.style.animationDelay = `${Math.random() * 5}s`;
            
            // Duración de animación aleatoria
            const duration = Math.random() * 10 + 10;
            particle.style.animationDuration = `${duration}s`;
            
            particlesContainer.appendChild(particle);
        }
    }
    
    // Efecto de entrada para la card
    const loginCard = document.querySelector('.login-card');
    if (loginCard) {
        loginCard.style.opacity = '0';
        loginCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            loginCard.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            loginCard.style.opacity = '1';
            loginCard.style.transform = 'translateY(0)';
        }, 300);
    }
});