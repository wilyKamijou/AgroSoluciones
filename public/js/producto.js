document.addEventListener("DOMContentLoaded", function() {

    const inputURL = document.getElementById("imagen_url");
    const previewDiv = document.getElementById("imagenPrevisualizacion");
    const previewImg = previewDiv.querySelector("img");
    const creditos = document.getElementById("creditosImagen");

    /* =============================
       BOTÓN: BUSQUEDA AUTOMÁTICA
       ============================= */
    document.getElementById("buscarImagenPixabay").addEventListener("click", function() {
        if (!inputURL.value.trim()) {
            alert("Debes ingresar primero un nombre de producto para buscar la imagen.");
            return;
        }

        // Simulación de búsqueda automática
        const query = encodeURIComponent(inputURL.value.trim());

        // Abrir búsqueda en Pixabay
        window.open(`https://pixabay.com/images/search/${query}`, "_blank");
    });

    /* =============================
       BOTÓN: IR A PIXABAY
       ============================= */
    document.getElementById("buscarManualPixabay").addEventListener("click", function() {
        window.open("https://pixabay.com/", "_blank");
    });

    /* =============================
       BOTÓN: IR A UNSPLASH
       ============================= */
    document.getElementById("buscarManualUnsplash").addEventListener("click", function() {
        window.open("https://unsplash.com/", "_blank");
    });

    /* =============================
       BOTÓN: PREVISUALIZAR IMAGEN
       ============================= */
    document.getElementById("previsualizarImagen").addEventListener("click", function() {
        const url = inputURL.value.trim();

        if (!url) {
            alert("Ingrese una URL de imagen primero.");
            return;
        }

        // Mostrar previsualización
        previewImg.src = url;
        previewDiv.classList.remove("d-none");

        // Créditos (automático si proviene de Pixabay)
        if (url.includes("pixabay.com")) {
            creditos.innerHTML = "Fuente: Pixabay · Licencia gratuita";
        } else if (url.includes("unsplash.com")) {
            creditos.innerHTML = "Fuente: Unsplash · Licencia gratuita";
        } else {
            creditos.innerHTML = "";
        }
    });

});