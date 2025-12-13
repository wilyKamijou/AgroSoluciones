
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('dropdownRutas');
        const checkboxes = document.querySelectorAll('.ruta-checkbox');

        checkboxes.forEach(chk => {
            chk.addEventListener('change', function() {
                const selected = Array.from(checkboxes)
                    .filter(i => i.checked)
                    .map(i => i.nextElementSibling.innerText);

                // Actualiza el texto del botón
                dropdownButton.textContent = selected.length > 0 ? selected.join(', ') : 'Elige rutas';
            });
        });
    });
