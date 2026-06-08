class PAWUploader {
    constructor(selectorContenedor) {
        this.contenedor = document.querySelector(selectorContenedor);
        if (!this.contenedor) return;

        this.init();
    }
    // Construye la UI dinámicamente ocultando el <input type="file"> nativo y reemplazándolo visualmente con una zona de Drag & Drop personalizada.
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
        this.previewContainer = PAW.nuevoElemento("figure", "", {
            class: "preview-container",
            style: "display: none; position: relative;"
        });
        this.imagenPreview = PAW.nuevoElemento("img", "", {
            class: "preview-imagen"
        });
        
        this.btnEliminar = PAW.nuevoElemento("button", "close", {
            type: "button",
            class: "material-symbols-outlined btn-eliminar-imagen",
            title: "Eliminar imagen",
            style: "display: none;"
        });

        this.previewContainer.appendChild(this.imagenPreview);

        // Añadir elementos a la dropZone
        this.dropZone.appendChild(this.btnEliminar);
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

    // Implementa la Drag and Drop.
    // e.preventDefault() en 'dragover' y 'drop' para anular la acción por defecto del navegador.
    // Captura el archivo soltado mediante el objeto e.dataTransfer.files.
    // Delega el evento 'click' de la zona visual al input oculto.
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

        // Evento para eliminar imagen seleccionada
        this.btnEliminar.addEventListener('click', (e) => {
            e.stopPropagation(); // Evita que se dispare el click del dropZone
            this.inputOculto.value = ""; // Limpia el input nativo
            
            // Restaura la interfaz inicial
            this.previewContainer.style.display = "none";
            this.btnEliminar.style.display = "none";
            this.imagenPreview.src = "";
            this.icono.style.display = "block";
            this.textoMensaje.style.display = "block";
        });

        // Cambio en el input nativo (cuando el usuario selecciona por clic)
        this.inputOculto.addEventListener('change', (e) => {
            if (this.inputOculto.files && this.inputOculto.files.length > 0) {
                const archivo = this.inputOculto.files[0];
                this.procesarArchivo(archivo);
            }
        });
    }

    // Utiliza la interfaz FileReader para manipular binarios en el cliente. 
    // Lee el archivo asíncronamente con readAsDataURL() y, al resolverse el evento 'onload', inyecta la cadena resultante en el atributo src de la imagen para generar una previsualización en tiempo real.
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
            this.btnEliminar.style.display = "flex";
        };
        reader.readAsDataURL(archivo);
    }

    // Manipula el DOM para informar al usuario sobre validaciones fallidas de MIME type (ej. si no subió una imagen).
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
