class PAWUploader {
    constructor(selectorContenedor) {
        this.contenedor = document.querySelector(selectorContenedor);
        if (!this.contenedor) return;

        this.init();
    }

    init() {
        // Crear el input oculto
        this.inputOculto = PAW.nuevoElemento("input", "", {
            type: "file",
            name: "imagen",
            accept: "image/*",
            style: "display: none;"
        });

        // Crear la zona de drop
        this.dropZone = PAW.nuevoElemento("div", "", {
            class: "drop-zone"
        });

        // Contenido de la zona de drop (icono y texto)
        this.icono = PAW.nuevoElemento("span", "cloud_upload", {
            class: "material-symbols-outlined uploader-icon"
        });
        
        this.textoMensaje = PAW.nuevoElemento("p", "Arrastrá la tapa del libro aquí o hacé clic para seleccionar", {
            class: "uploader-texto"
        });

        // Contenedor para el preview (oculto por defecto)
        this.previewContainer = PAW.nuevoElemento("div", "", {
            class: "preview-container",
            style: "display: none;"
        });
        this.imagenPreview = PAW.nuevoElemento("img", "", {
            class: "preview-imagen"
        });
        this.previewContainer.appendChild(this.imagenPreview);

        // Añadir elementos a la dropZone
        this.dropZone.appendChild(this.icono);
        this.dropZone.appendChild(this.textoMensaje);
        this.dropZone.appendChild(this.previewContainer);

        // Añadir dropZone y el input oculto al contenedor principal
        this.contenedor.appendChild(this.dropZone);
        this.contenedor.appendChild(this.inputOculto);

        // Mensaje de error
        this.mensajeError = PAW.nuevoElemento("span", "", {
            class: "error",
            style: "display: none;"
        });
        this.contenedor.appendChild(this.mensajeError);

        this.registrarEventos();
    }

    registrarEventos() {
        // Eventos de drag and drop en la dropZone
        this.dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropZone.classList.add('dragover');
        });

        this.dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.dropZone.classList.remove('dragover');
        });

        this.dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dropZone.classList.remove('dragover');
            
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                const archivo = e.dataTransfer.files[0];
                this.procesarArchivo(archivo);
                
                // Asignar el archivo al input oculto
                this.inputOculto.files = e.dataTransfer.files;
            }
        });

        // Clic en la dropZone abre el input nativo
        this.dropZone.addEventListener('click', () => {
            this.inputOculto.click();
        });

        // Cambio en el input nativo (cuando el usuario selecciona por clic)
        this.inputOculto.addEventListener('change', (e) => {
            if (this.inputOculto.files && this.inputOculto.files.length > 0) {
                const archivo = this.inputOculto.files[0];
                this.procesarArchivo(archivo);
            }
        });
    }

    procesarArchivo(archivo) {
        this.mostrarError(""); // Limpiar errores

        // Validar que sea una imagen
        if (!archivo.type.startsWith('image/')) {
            this.mostrarError("El archivo seleccionado no es una imagen válida. Por favor, subí un JPG, PNG o GIF.");
            return;
        }

        // Crear preview local con FileReader
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagenPreview.src = e.target.result;
            
            // Ocultar texto e icono, mostrar preview
            this.icono.style.display = "none";
            this.textoMensaje.style.display = "none";
            this.previewContainer.style.display = "flex";
        };
        reader.readAsDataURL(archivo);
    }

    mostrarError(mensaje) {
        if (mensaje) {
            this.mensajeError.textContent = mensaje;
            this.mensajeError.style.display = "block";
        } else {
            this.mensajeError.textContent = "";
            this.mensajeError.style.display = "none";
        }
    }
}
