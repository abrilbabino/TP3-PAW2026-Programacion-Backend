/*
 * public/assets/js/components/PAWFiltros.js
 * Manejo de Estado, Filtros y Ordenamiento (Desacoplado)
 */
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

    async cargarLibros() {
        try {
            const response = await fetch(this.opciones.urlAPI);
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
}
