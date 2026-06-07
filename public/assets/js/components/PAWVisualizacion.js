class PAWVisualizacion {
    constructor(contenedorLibros, contenedorPaginacion, itemsPorPagina = 6) {
        this.contenedorLibros = contenedorLibros;
        this.contenedorPaginacion = contenedorPaginacion;
        this.itemsPorPagina = itemsPorPagina;
        this.currentPage = 1;
        this.libros = [];
    }

    // Actúa como puente entre la capa lógica (Filtros) y la Vista. 
    // Reinicia el estado de paginación (currentPage = 1) para evitar índices fuera de rango al recibir un nuevo set de datos filtrados.
    actualizarDatos(nuevosLibros) {
        this.libros = nuevosLibros;
        this.currentPage = 1;
        this.render();
    }

    render() {
        this.renderizarLibros();
    }

    // Borra el DOM previo (innerHTML = "").
    // Emplea Template Literals (``) de ES6 para inyectar dinámicamente el contador.
    // Utiliza Array.prototype.slice() para extraer funcionalmente la sublista de libros que corresponde estrictamente a la ventana matemática de la página actual.
    renderizarLibros() {
        this.contenedorLibros.innerHTML = "";
        
        // Actualizar contador en la barra de opciones
        const elementoContador = document.querySelector(".paw-contador-resultados");
        if (elementoContador) {
            elementoContador.innerHTML = `Mostrando <strong>${this.libros.length}</strong> resultados`;
        }

        if (this.libros.length === 0) {
            this.contenedorLibros.innerHTML = '<p class="resultado-busqueda">No se encontraron libros que coincidan con la búsqueda.</p>';
            this.contenedorPaginacion.innerHTML = "";
            return;
        }

        const inicio = (this.currentPage - 1) * this.itemsPorPagina;
        const fin = inicio + this.itemsPorPagina;
        const librosAMostrar = this.libros.slice(inicio, fin);

        librosAMostrar.forEach((libro) => {
            const articulo = this.crearTarjetaLibro(libro);
            this.contenedorLibros.appendChild(articulo);
        });

        const totalPaginas = Math.ceil(this.libros.length / this.itemsPorPagina);
        this.renderizarPaginacion(totalPaginas);
    }

    crearTarjetaLibro(libro) {
        const articulo = PAW.nuevoElemento("article", "", { class: "tarjeta-libro" });

        const img = PAW.nuevoElemento("img", "", {
            src: `/assets/img/${libro.imagen}`,
            alt: libro.titulo,
        });

        const titulo = PAW.nuevoElemento("p", "", { class: "tarjeta-titulo" });
        titulo.appendChild(document.createTextNode(libro.titulo));

        const autor = PAW.nuevoElemento("p", "", { class: "tarjeta-autor" });
        autor.appendChild(document.createTextNode(`Autor: ${libro.autor_nombre}`));

        const precioVal = typeof libro.precio === 'string' ? parseFloat(libro.precio).toFixed(2) : Number(libro.precio).toFixed(2);
        const precio = PAW.nuevoElemento("p", "", { class: "tarjeta-precio" });
        const precioEm = PAW.nuevoElemento("em", "Precio: ", {});
        precio.appendChild(precioEm);
        precio.appendChild(document.createTextNode(`$${precioVal}`));

        const overlay = PAW.nuevoElemento("div", "", { class: "overlay" });
        const pDesc = PAW.nuevoElemento("p", "");
        pDesc.appendChild(document.createTextNode(libro.descripcion || "Sin descripción"));
        const btnVerMas = PAW.nuevoElemento("a", "Ver más", {
            class: "btn-primario",
            href: `/detalle?id=${libro.id}`,
        });
        overlay.appendChild(pDesc);
        overlay.appendChild(btnVerMas);

        const formAdd = PAW.nuevoElemento("form", "", {
            method: "POST",
            action: `/agregarCarrito`,
            "data-paw-carrito-form": "true",
        });
        const boton = PAW.nuevoElemento("button", "", {
            type: "submit",
            class: "btn-add-carrito",
        });
        const icono = PAW.nuevoElemento("span", "", {
            class: "material-symbols-outlined",
        });
        icono.textContent = "add_circle";
        boton.appendChild(icono);
        formAdd.appendChild(boton);

        articulo.appendChild(img);
        articulo.appendChild(titulo);
        articulo.appendChild(autor);
        articulo.appendChild(precio);
        articulo.appendChild(overlay);
        articulo.appendChild(formAdd);

        return articulo;
    }

    // Accede al BOM (window.innerWidth) para adaptar dinámicamente la amplitud de la barra de paginación, aplicando un algoritmo de ventana matemática restringida en móviles.
    renderizarPaginacion(totalPaginas) {
        this.contenedorPaginacion.innerHTML = "";
        if (totalPaginas <= 1) return;

        const nav = PAW.nuevoElemento("nav", "", {
            class: "paw-filtros-nav-paginacion",
            "aria-label": "Paginación de resultados",
        });

        // Botón Anterior
        const btnAnt = PAW.nuevoElemento("button", "Anterior", {
            class: "paw-paginacion-btn paw-filtros-btn-anterior",
        });
        if (this.currentPage === 1) btnAnt.disabled = true;
        btnAnt.addEventListener("click", () => this.irAPagina(this.currentPage - 1));
        nav.appendChild(btnAnt);

        const esMovil = window.innerWidth < 994;
        const maxPaginas = esMovil ? 1 : 3;

        const crearPagina = (num) => {
            const btn = PAW.nuevoElemento("button", String(num), {
                class: "paw-paginacion-btn",
            });
            if (num === this.currentPage) btn.classList.add("is-active");
            btn.addEventListener("click", () => this.irAPagina(num));
            return btn;
        };

        const crearElipsis = () => {
            return PAW.nuevoElemento("span", "...", {
                class: "paw-paginacion-ellipsis",
            });
        };

        if (totalPaginas <= maxPaginas + 1) {
            for (let i = 1; i <= totalPaginas; i++) {
                nav.appendChild(crearPagina(i));
            }
        } else {
            nav.appendChild(crearPagina(1));

            let inicio = this.currentPage - Math.floor(maxPaginas / 2);
            let fin = inicio + maxPaginas - 1;

            if (inicio < 2) {
                fin += (2 - inicio);
                inicio = 2;
            }
            if (fin > totalPaginas - 1) {
                inicio -= (fin - (totalPaginas - 1));
                fin = totalPaginas - 1;
            }
            if (inicio < 2) inicio = 2;

            if (inicio > 2) {
                nav.appendChild(crearElipsis());
            }

            for (let i = inicio; i <= fin; i++) {
                nav.appendChild(crearPagina(i));
            }

            if (fin < totalPaginas - 1) {
                nav.appendChild(crearElipsis());
            }

            nav.appendChild(crearPagina(totalPaginas));
        }

        // Botón Siguiente
        const btnSig = PAW.nuevoElemento("button", "Siguiente", {
            class: "paw-paginacion-btn paw-filtros-btn-siguiente",
        });
        if (this.currentPage === totalPaginas) btnSig.disabled = true;
        btnSig.addEventListener("click", () => this.irAPagina(this.currentPage + 1));
        nav.appendChild(btnSig);
        this.contenedorPaginacion.appendChild(nav);

        if (totalPaginas > 5 && window.innerWidth < 994) {
            this.contenedorPaginacion.classList.add("paw-paginacion-desborda");
        } else {
            this.contenedorPaginacion.classList.remove("paw-paginacion-desborda");
        }
    }

    // Ejecuta la validación de límites matemáticos antes de mutar el estado.
    irAPagina(pagina) {
        const totalPaginas = Math.ceil(this.libros.length / this.itemsPorPagina);
        if (pagina >= 1 && pagina <= totalPaginas) {
            this.currentPage = pagina;
            this.render();
            
            // Utiliza Element.scrollIntoView({ behavior: 'smooth' }) para devolver el foco visual del usuario al inicio de la grilla tras la transición de página.
            this.contenedorLibros.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    }
}
