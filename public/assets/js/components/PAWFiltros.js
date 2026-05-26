class PAWFiltros {
    constructor(container, opciones = {}) {
        this.container = typeof container === 'string' ? document.getElementById(container) : container;
        this.opciones = {
            urlAPI: "/api/libros",
            itemsPorPagina: opciones.itemsPorPagina || 6,
            ...opciones,
        };

        this.libros = [];
        this.librosFiltrados = [];
        this.estado = {
            orden: "titulo-asc",
            genero: "",
            editorial: "",
            idioma: "",
            autor: "",
            precioMin: 0,
            precioMax: Infinity,
        };

        this.init();
    }

    // Utiliza 'await' para garantizar que la UI y el puente de visualización solo se construyan una vez que AJAX haya sido resuelta exitosamente con los datos del servidor.
    async init() {
        try {
            await this.cargarLibros();
            this.crearUI();

            // Instanciar el puente de visualización pasando contenedores dinámicos
            this.visualizacion = new PAWVisualizacion(
                this.contenedorLibros,
                this.contenedorPaginacion,
                this.opciones.itemsPorPagina
            );

            this.registrarEventos();
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error inicializando filtros:", error);
        }
    }

    // Utiliza la Fetch API para obtener el catálogo.
    // Implementa manejo estricto de errores validando la propiedad response.ok.
    // Usa Array.prototype.map() y el Spread Operator (...) para clonar los objetos y aplicar tipos (parseFloat) sobre el precio.
    async cargarLibros() {
        try {
            const response = await fetch(this.opciones.urlAPI);
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const resultado = await response.json();

            if (!resultado.success) {
                throw new Error(resultado.error || "Error al cargar libros");
            }

            this.libros = resultado.data.map((libro) => ({
                ...libro,
                precio: parseFloat(libro.precio) || 0,
            }));
        } catch (error) {
            console.error("Error cargando libros:", error);
            throw error;
        }
    }

    crearUI() {
        // 1. Rescatar elementos estáticos del HTML (Botones Agregar/Descargar)
        const accionesHTML = this.container.querySelector('.barra-resultados__acciones');
        const botonesAccion = accionesHTML ? accionesHTML.cloneNode(true) : null;

        if (botonesAccion) {
            const btnDescargar = botonesAccion.querySelector('a[href*="format=csv"]');
            if (btnDescargar) {
                btnDescargar.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.descargarCSV();
                });
            }
        }

        // 2. Limpiar el contenedor
        this.container.innerHTML = "";

        // 3. Crear Filtros (Área: filtros)
        const panelFiltros = this.crearPanelFiltros();
        panelFiltros.className = "seccion-filtros";
        this.container.appendChild(panelFiltros);

        // 4. Crear Barra Unificada (Área: barra)
        const barraOpciones = this.crearBarraOpciones();
        barraOpciones.className = "barra-resultados";
        // Si encontramos los botones del HTML, los añadimos a la nueva barra dinámica
        if (botonesAccion) {
            barraOpciones.appendChild(botonesAccion);
        }
        this.container.appendChild(barraOpciones);

        // 5. Crear Grilla (Área: libros)
        this.contenedorLibros = PAW.nuevoElemento("section", "", { class: "grilla-libros" });
        this.container.appendChild(this.contenedorLibros);

        // 6. Crear Paginación (Área: paginacion)
        this.contenedorPaginacion = PAW.nuevoElemento("nav", "", { class: "paginacion" });
        this.container.appendChild(this.contenedorPaginacion);
    }

    crearPanelFiltros() {
        const panel = PAW.nuevoElemento("aside", "", {
            class: "paw-filtros-panel-aside",
        });

        // Botón toggle para Mobile
        const btnToggle = PAW.nuevoElemento("button", "", {
            class: "paw-filtros-toggle",
            "aria-label": "Abrir/cerrar filtros",
            "aria-expanded": "false",
        });
        const iconoToggle = PAW.nuevoElemento("span", "tune", {
            class: "material-symbols-outlined",
        });
        const textoToggle = PAW.nuevoElemento("span", " Filtros", {});
        btnToggle.appendChild(iconoToggle);
        btnToggle.appendChild(textoToggle);
        panel.appendChild(btnToggle);

        // Contenedor de filtros colapsable
        this.contenedorFiltrosPanel = PAW.nuevoElemento("div", "", {
            class: "paw-filtros-panel",
            "aria-hidden": "true",
        });

        // Selectores dinámicos generados usando el helper dinámico
        this.selectGenero = this.crearFiltroSelect("Género", "genero_id", "genero_nombre");
        this.selectEditorial = this.crearFiltroSelect("Editorial", "editorial_id", "editorial_nombre");
        this.selectIdioma = this.crearFiltroSelect("Idioma", "idioma_id", "idioma_nombre");
        this.selectAutor = this.crearFiltroSelect("Autor", "autor_id", "autor_nombre");

        // Inputs de Rango de Precio
        const seccionPrecio = PAW.nuevoElemento("div", "", { class: "paw-filtros-grupo" });
        seccionPrecio.appendChild(PAW.nuevoElemento("label", "Rango de Precio"));
        const rangoInputs = PAW.nuevoElemento("div", "", { class: "paw-filtros-precio-rango" });
        
        this.inputMin = PAW.nuevoElemento("input", "", {
            type: "number",
            placeholder: "Min",
            "data-filtro": "precioMin",
        });
        this.inputMax = PAW.nuevoElemento("input", "", {
            type: "number",
            placeholder: "Max",
            "data-filtro": "precioMax",
        });
        rangoInputs.appendChild(this.inputMin);
        rangoInputs.appendChild(this.inputMax);
        seccionPrecio.appendChild(rangoInputs);
        this.contenedorFiltrosPanel.appendChild(seccionPrecio);

        // Botón Limpiar Filtros
        this.btnLimpiar = PAW.nuevoElemento("button", "Limpiar filtros", {
            class: "paw-filtros-btn-limpiar",
        });
        this.contenedorFiltrosPanel.appendChild(this.btnLimpiar);

        panel.appendChild(this.contenedorFiltrosPanel);

        // Manejar click de Toggle para Mobile
        btnToggle.addEventListener("click", () => {
            const isHidden = this.contenedorFiltrosPanel.getAttribute("aria-hidden") === "true";
            if (isHidden) {
                this.contenedorFiltrosPanel.setAttribute("aria-hidden", "false");
                this.contenedorFiltrosPanel.classList.add("is-open");
                btnToggle.setAttribute("aria-expanded", "true");
            } else {
                this.contenedorFiltrosPanel.setAttribute("aria-hidden", "true");
                this.contenedorFiltrosPanel.classList.remove("is-open");
                btnToggle.setAttribute("aria-expanded", "false");
            }
        });

        return panel;
    }

    // Genera las etiquetas <select> dinámicamente.
    // Utiliza la estructura de datos Map (ES6) para limpiar duplicados eficientemente.
    // Ordena los items aplicando String.prototype.localeCompare() para respetar la semántica y los caracteres especiales del idioma español.
    crearFiltroSelect(titulo, nombreFiltro, campoLabel) {
        const seccion = PAW.nuevoElemento("div", "", { class: "paw-filtros-grupo" });
        seccion.appendChild(PAW.nuevoElemento("label", titulo));

        const dataFiltroVal = nombreFiltro.replace("_id", "");
        const select = PAW.nuevoElemento("select", "", { "data-filtro": dataFiltroVal });
        select.appendChild(PAW.nuevoElemento("option", "Todos", { value: "" }));

        const opcionesMap = new Map();
        this.libros.forEach((libro) => {
            const id = libro[nombreFiltro];
            const nombre = libro[campoLabel];

            if (id != null && id !== "" && !opcionesMap.has(id)) {
                opcionesMap.set(id, {
                    id: String(id),
                    nombre: String(nombre || ""),
                });
            }
        });

        Array.from(opcionesMap.values())
            .sort((a, b) => a.nombre.localeCompare(b.nombre))
            .forEach((opcion) => {
                select.appendChild(PAW.nuevoElemento("option", opcion.nombre, { value: opcion.id }));
            });

        seccion.appendChild(select);
        this.contenedorFiltrosPanel.appendChild(seccion);
        return select;
    }

    crearBarraOpciones() {
        const barra = PAW.nuevoElemento("div", "", {
            class: "paw-opciones-barra",
        });

        // Contador de resultados
        const contador = PAW.nuevoElemento("p", "Mostrando 0 resultados", {
            class: "paw-contador-resultados",
        });
        barra.appendChild(contador);

        // Selector de Ordenamiento
        const grupoOrden = PAW.nuevoElemento("div", "", {
            class: "paw-ordenamiento-grupo",
        });
        grupoOrden.appendChild(PAW.nuevoElemento("label", "Ordenar por: "));

        this.selectOrden = PAW.nuevoElemento("select", "", {
            "aria-label": "Ordenar por",
        });

        const opcionesOrden = [
            { value: "titulo-asc", text: "Título (A-Z)" },
            { value: "titulo-desc", text: "Título (Z-A)" },
            { value: "precio-asc", text: "Precio (Menor a Mayor)" },
            { value: "precio-desc", text: "Precio (Mayor a Menor)" },
        ];

        opcionesOrden.forEach((opt) => {
            this.selectOrden.appendChild(PAW.nuevoElemento("option", opt.text, { value: opt.value }));
        });

        grupoOrden.appendChild(this.selectOrden);
        barra.appendChild(grupoOrden);

        const grupoItems = PAW.nuevoElemento("div", "", {
            class: "paw-items-grupo",
        });
        grupoItems.appendChild(PAW.nuevoElemento("label", "Libros por página: "));

        this.inputItems = PAW.nuevoElemento("input", "", {
            type: "number",
            min: "1",
            max: "100",
            step: "1",
            value: String(this.opciones.itemsPorPagina),
            class: "paw-items-input",
            "aria-label": "Cantidad por página",
        });

        grupoItems.appendChild(this.inputItems);
        barra.appendChild(grupoItems);

        return barra;
    }

    registrarEventos() {
        const triggers = [
            this.selectGenero,
            this.selectEditorial,
            this.selectIdioma,
            this.selectAutor,
            this.inputMin,
            this.inputMax,
            this.selectOrden
        ];

        triggers.forEach((trigger) => {
            const eventName = trigger.tagName === "INPUT" ? "input" : "change";
            trigger.addEventListener(eventName, () => {
                this.estado.genero = this.selectGenero.value;
                this.estado.editorial = this.selectEditorial.value;
                this.estado.idioma = this.selectIdioma.value;
                this.estado.autor = this.selectAutor.value;
                this.estado.precioMin = this.inputMin.value ? parseFloat(this.inputMin.value) : 0;
                this.estado.precioMax = this.inputMax.value ? parseFloat(this.inputMax.value) : Infinity;
                this.estado.orden = this.selectOrden.value;
                this.aplicarFiltros();
            });
        });

        this.inputItems.addEventListener("input", () => {
            const nuevoValor = parseInt(this.inputItems.value);
            if (nuevoValor >= 1 && nuevoValor !== this.visualizacion.itemsPorPagina) {
                this.visualizacion.itemsPorPagina = nuevoValor;
                this.visualizacion.actualizarDatos(this.librosFiltrados);
            }
        });

        this.btnLimpiar.addEventListener("click", () => {
            this.selectGenero.value = "";
            this.selectEditorial.value = "";
            this.selectIdioma.value = "";
            this.selectAutor.value = "";
            this.inputMin.value = "";
            this.inputMax.value = "";
            this.selectOrden.value = "titulo-asc";

            this.estado.genero = "";
            this.estado.editorial = "";
            this.estado.idioma = "";
            this.estado.autor = "";
            this.estado.precioMin = 0;
            this.estado.precioMax = Infinity;
            this.estado.orden = "titulo-asc";

            this.aplicarFiltros();
        });
    }

    aplicarFiltros() {
        this.librosFiltrados = this.libros.filter((libro) => {
            const cumpleGenero = !this.estado.genero || libro.genero_id == this.estado.genero;
            const cumpleEditorial = !this.estado.editorial || libro.editorial_id == this.estado.editorial;
            const cumpleIdioma = !this.estado.idioma || libro.idioma_id == this.estado.idioma;
            const cumpleAutor = !this.estado.autor || libro.autor_id == this.estado.autor;
            const cumplePrecio = libro.precio >= this.estado.precioMin && libro.precio <= this.estado.precioMax;

            return cumpleGenero && cumpleEditorial && cumpleIdioma && cumpleAutor && cumplePrecio;
        });

        this.ordenarLibros();

        // Pasar los datos limpios al puente de visualización
        this.visualizacion.actualizarDatos(this.librosFiltrados);
    }

    // Utiliza Desestructuración de Arreglos para separar el criterio y la dirección, para luego aplicar Array.sort() con un algoritmo comparador personalizado.
    ordenarLibros() {
        const [criterio, direccion] = this.estado.orden.split("-");

        this.librosFiltrados.sort((a, b) => {
            let valorA, valorB;

            if (criterio === "titulo") {
                valorA = a.titulo.toLowerCase();
                valorB = b.titulo.toLowerCase();
            } else if (criterio === "precio") {
                valorA = a.precio;
                valorB = b.precio;
            }

            if (valorA < valorB) return direccion === "asc" ? -1 : 1;
            if (valorA > valorB) return direccion === "asc" ? 1 : -1;
            return 0;
        });
    }

    // Exporta los datos al vuelo utilizando Blob API.
    // Concatena el prefijo BOM (\uFEFF) para garantizar que Excel parsee el UTF-8 correctamente.
    // Construye el archivo mediante new Blob() y URL.createObjectURL(), forzando 
    // la descarga a través de un click virtual en un nodo <a> desconectado del DOM.
    descargarCSV() {
        const cabeceras = ["ID", "Título", "Autor", "Género", "Editorial", "Idioma", "Precio", "Descripción"];
        let csvContent = cabeceras.join(";") + "\n";

        // Cálculo del slice para la página actual
        const currentPage = this.visualizacion.currentPage || 1;
        const itemsPorPagina = this.visualizacion.itemsPorPagina || this.opciones.itemsPorPagina;
        const inicio = (currentPage - 1) * itemsPorPagina;
        const fin = inicio + itemsPorPagina;
        
        const librosPaginaActual = this.librosFiltrados.slice(inicio, fin);

        librosPaginaActual.forEach(libro => {
            const fila = [
                libro.id,
                libro.titulo,
                libro.autor_nombre,
                libro.genero_nombre,
                libro.editorial_nombre,
                libro.idioma_nombre,
                libro.precio,
                libro.descripcion
            ].map(valor => `"${String(valor || '').replace(/"/g, '""')}"`);
            
            csvContent += fila.join(";") + "\n";
        });

        const bom = "\uFEFF";
        const blob = new Blob([bom + csvContent], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "catalogo.csv";
        a.click();
        URL.revokeObjectURL(url);
    }
}
