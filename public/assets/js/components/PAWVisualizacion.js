/*
 * public/assets/js/components/PAWVisualizacion.js
 * Manejo de Paginación y Render de la Grilla de Libros (Desacoplado)
 */
class PAWVisualizacion {
    constructor(contenedorLibros, contenedorPaginacion, itemsPorPagina = 6) {
        this.contenedorLibros = contenedorLibros;
        this.contenedorPaginacion = contenedorPaginacion;
        this.itemsPorPagina = itemsPorPagina;
        this.currentPage = 1;
        this.libros = [];
    }

    actualizarDatos(nuevosLibros) {
        this.libros = nuevosLibros;
        this.currentPage = 1;
        this.render();
    }

    render() {
        this.renderizarLibros();
    }

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
        const tituloFuerte = PAW.nuevoElemento("strong", libro.titulo, {});
        titulo.appendChild(tituloFuerte);

        const autor = PAW.nuevoElemento("p", "", { class: "tarjeta-autor" });
        const autorEm = PAW.nuevoElemento("em", "Autor: ", {});
        autor.appendChild(autorEm);
        autor.appendChild(document.createTextNode(libro.autor_nombre || "Desconocido"));

        const precioVal = parseFloat(libro.precio).toFixed(2);
        const precio = PAW.nuevoElemento("p", "", { class: "tarjeta-precio" });
        const precioEm = PAW.nuevoElemento("em", "Precio: ", {});
        precio.appendChild(precioEm);
        precio.appendChild(document.createTextNode(`$${precioVal}`));

        const overlay = PAW.nuevoElemento("div", "", { class: "overlay" });
        const descripcion = PAW.nuevoElemento("p", libro.descripcion || "", {});
        const link = PAW.nuevoElemento("a", "Ver más", {
            href: `/detalle?id=${libro.id}`,
            class: "btn-primario"
        });
        overlay.appendChild(descripcion);
        overlay.appendChild(link);

        const formulario = PAW.nuevoElemento("form", "", {
            class: "boton-agregarCarrito",
            action: "/agregarCarrito",
            method: "POST",
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
        formulario.appendChild(boton);

        articulo.appendChild(img);
        articulo.appendChild(titulo);
        articulo.appendChild(autor);
        articulo.appendChild(precio);
        articulo.appendChild(overlay);
        articulo.appendChild(formulario);

        return articulo;
    }

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

        // Páginas individuales
        const maxBotones = window.innerWidth < 768 ? 5 : 7;
        let inicio = Math.max(1, this.currentPage - Math.floor(maxBotones / 2));
        let fin = Math.min(totalPaginas, inicio + maxBotones - 1);

        if (fin - inicio + 1 < maxBotones) {
            inicio = Math.max(1, fin - maxBotones + 1);
        }

        for (let i = inicio; i <= fin; i++) {
            const btnNum = PAW.nuevoElemento("button", String(i), {
                class: "paw-paginacion-btn",
            });
            if (i === this.currentPage) {
                btnNum.classList.add("is-active");
            }
            btnNum.addEventListener("click", () => this.irAPagina(i));
            nav.appendChild(btnNum);
        }

        // Botón Siguiente
        const btnSig = PAW.nuevoElemento("button", "Siguiente", {
            class: "paw-paginacion-btn paw-filtros-btn-siguiente",
        });
        if (this.currentPage === totalPaginas) btnSig.disabled = true;
        btnSig.addEventListener("click", () => this.irAPagina(this.currentPage + 1));
        nav.appendChild(btnSig);

        this.contenedorPaginacion.appendChild(nav);
    }

    irAPagina(pagina) {
        const totalPaginas = Math.ceil(this.libros.length / this.itemsPorPagina);
        if (pagina >= 1 && pagina <= totalPaginas) {
            this.currentPage = pagina;
            this.render();
            
            // Scroll suave hacia la grilla de libros
            this.contenedorLibros.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    }
}
