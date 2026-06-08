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
        const articulo = PAW.nuevoElemento("article", "", {
            class: "tarjeta-libro",
            itemprop: "itemListElement",
            itemscope: "",
            itemtype: "https://schema.org/ListItem"
        });

        const wrapperBook = PAW.nuevoElemento("div", "", {
            itemprop: "item",
            itemscope: "",
            itemtype: "https://schema.org/Book",
            style: "display: contents"
        });

        const img = PAW.nuevoElemento("img", "", {
            src: `/assets/img/${libro.imagen}`,
            alt: libro.titulo,
            itemprop: "image",
        });

        const titulo = PAW.nuevoElemento("p", "", { class: "tarjeta-titulo", itemprop: "name" });
        titulo.appendChild(document.createTextNode(libro.titulo));

        const autor = PAW.nuevoElemento("p", "", {
            class: "tarjeta-autor",
            itemprop: "author",
            itemscope: "",
            itemtype: "https://schema.org/Person"
        });
        autor.appendChild(document.createTextNode("Autor: "));
        const autorNombreSpan = PAW.nuevoElemento("span", "", { itemprop: "name" });
        autorNombreSpan.appendChild(document.createTextNode(libro.autor_nombre));
        autor.appendChild(autorNombreSpan);

        const precioVal = typeof libro.precio === 'string' ? parseFloat(libro.precio).toFixed(2) : Number(libro.precio).toFixed(2);
        const precio = PAW.nuevoElemento("p", "", { class: "tarjeta-precio" });
        const precioEm = PAW.nuevoElemento("em", "Precio: ", {});
        precio.appendChild(precioEm);
        precio.appendChild(document.createTextNode(`$${precioVal}`));

        const offers = PAW.nuevoElemento("div", "", {
            itemprop: "offers",
            itemscope: "",
            itemtype: "https://schema.org/Offer",
            style: "display: contents"
        });
        const priceCurrency = PAW.nuevoElemento("meta", "", {
            itemprop: "priceCurrency",
            content: "ARS"
        });
        const price = PAW.nuevoElemento("meta", "", {
            itemprop: "price",
            content: precioVal
        });
        offers.appendChild(priceCurrency);
        offers.appendChild(price);

        const overlay = PAW.nuevoElemento("div", "", { class: "overlay" });
        const pDesc = PAW.nuevoElemento("p", "", { itemprop: "description" });
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
        
        const inputId = PAW.nuevoElemento("input", "", {
            type: "hidden",
            name: "libro_id",
            value: libro.id
        });
        formAdd.appendChild(inputId);

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

        wrapperBook.appendChild(img);
        wrapperBook.appendChild(titulo);
        wrapperBook.appendChild(autor);
        wrapperBook.appendChild(precio);
        wrapperBook.appendChild(offers);
        wrapperBook.appendChild(overlay);
        wrapperBook.appendChild(formAdd);

        articulo.appendChild(wrapperBook);

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

            const primerLibro = this.contenedorLibros.querySelector(".tarjeta-libro");
            if (primerLibro) {
                primerLibro.setAttribute("tabindex", "-1");
                primerLibro.focus({ preventScroll: true });
            }
        }
    }
}
