/**
 * PAWFiltros - Componente de filtrado, ordenamiento y paginación de libros
 * Funcionalidades:
 * - Ordenar por múltiples criterios (ascendente/descendente)
 * - Filtrar por rango de precio
 * - Paginación tradicional
 * - Scroll infinito (optional)
 * - 100% responsivo para mobile
 */
class PAWFiltros {
  constructor(container, opciones = {}) {
    this.container = container;
    this.opciones = {
      urlAPI: "/api/libros",
      itemsPorPagina: opciones.itemsPorPagina || 6,
      enableScrollInfinito: opciones.enableScrollInfinito !== false,
      onLibroClick: opciones.onLibroClick || null,
      ...opciones,
    };

    this.libros = [];
    this.librosFiltrados = [];
    this.currentPage = 1;
    this.scrollInfinitoActivo = false;
    this.cargandoMas = false;

    this.estado = {
      orden: "titulo-asc",
      generoId: "",
      editorialId: "",
      idiomaId: "",
      autorId: "",
      precioMin: 0,
      precioMax: Infinity,
    };

    this.init();
  }

  async init() {
    try {
      PAW.cargarCSS("/assets/css/filtros.css");
      await this.cargarLibros();
      this.crearUI();
      this.registrarEventos();
      this.aplicarFiltros();
    } catch (error) {
      console.error("Error inicializando filtros:", error);
      this.mostrarError("Error al cargar los libros");
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

      this.actualizarRangoPrecio();
    } catch (error) {
      console.error("Error cargando libros:", error);
      throw error;
    }
  }

  actualizarRangoPrecio() {
    if (this.libros.length === 0) return;

    const precios = this.libros.map((l) => l.precio);
    this.estado.precioMin = Math.floor(Math.min(...precios));
    this.estado.precioMax = Math.ceil(Math.max(...precios));
  }

  crearUI() {
    this.container.innerHTML = "";

    const wrapper = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-wrapper",
    });

    // Panel de filtros
    const panelFiltros = this.crearPanelFiltros();
    wrapper.appendChild(panelFiltros);

    // Área de resultados
    const areaResultados = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-resultados",
    });

    // Barra de opciones
    const barraOpciones = this.crearBarraOpciones();
    areaResultados.appendChild(barraOpciones);

    // Contenedor de libros
    this.contenedorLibros = PAW.nuevoElemento("div", "", {
      class: "grilla-libros",
      "data-paginacion": "tradicional",
    });
    areaResultados.appendChild(this.contenedorLibros);

    // Paginación
    this.contenedorPaginacion = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-paginacion",
    });
    areaResultados.appendChild(this.contenedorPaginacion);

    wrapper.appendChild(areaResultados);
    this.container.appendChild(wrapper);
  }

  crearPanelFiltros() {
    const panel = PAW.nuevoElemento("aside", "", {
      class: "paw-filtros-panel",
    });

    // Botón para abrir/cerrar en mobile
    const btnToggle = PAW.nuevoElemento("button", "", {
      class: "paw-filtros-toggle",
      "aria-label": "Abrir/cerrar filtros",
      "aria-expanded": "false",
    });
    const iconoToggle = PAW.nuevoElemento("span", "", {
      class: "material-symbols-outlined",
    });
    iconoToggle.textContent = "tune";
    const textoToggle = PAW.nuevoElemento("span", "Filtros", {});
    btnToggle.appendChild(iconoToggle);
    btnToggle.appendChild(textoToggle);

    // Contenedor de filtros (colapsable en mobile)
    this.contenedorFiltrosPanel = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-contenido",
      "aria-hidden": "true",
    });

    // Filtros del catálogo
    const seccionGenero = this.crearFiltroSelect(
      "Género",
      "genero_id",
      "genero_nombre",
    );
    this.contenedorFiltrosPanel.appendChild(seccionGenero);

    const seccionEditorial = this.crearFiltroSelect(
      "Editorial",
      "editorial_id",
      "editorial_nombre",
    );
    this.contenedorFiltrosPanel.appendChild(seccionEditorial);

    const seccionIdioma = this.crearFiltroSelect(
      "Idioma",
      "idioma_id",
      "idioma_nombre",
    );
    this.contenedorFiltrosPanel.appendChild(seccionIdioma);

    const seccionAutor = this.crearFiltroSelect(
      "Autor",
      "autor_id",
      "autor_nombre",
    );
    this.contenedorFiltrosPanel.appendChild(seccionAutor);

    // Rango de precio
    const seccionPrecio = this.crearFiltroRangoPrecio();
    this.contenedorFiltrosPanel.appendChild(seccionPrecio);

    // Botón Limpiar filtros
    const btnLimpiar = PAW.nuevoElemento("button", "Limpiar filtros", {
      class: "paw-filtros-limpiar",
    });
    this.contenedorFiltrosPanel.appendChild(btnLimpiar);

    panel.appendChild(btnToggle);
    panel.appendChild(this.contenedorFiltrosPanel);

    return panel;
  }

  crearFiltroRangoPrecio() {
    const seccion = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-seccion",
    });

    const label = PAW.nuevoElemento("label", "Rango de Precio", {
      class: "paw-filtros-label",
    });
    seccion.appendChild(label);

    // Contenedor para los inputs
    const contenedorInputs = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-rango-inputs",
    });

    const inputMin = PAW.nuevoElemento("input", "", {
      type: "number",
      placeholder: "Min",
      class: "paw-filtros-input-precio",
      "data-filtro": "precioMin",
    });

    const inputMax = PAW.nuevoElemento("input", "", {
      type: "number",
      placeholder: "Max",
      class: "paw-filtros-input-precio",
      "data-filtro": "precioMax",
    });

    contenedorInputs.appendChild(inputMin);
    contenedorInputs.appendChild(inputMax);
    seccion.appendChild(contenedorInputs);

    return seccion;
  }

  crearFiltroSelect(titulo, nombreFiltro, campoLabel) {
    const seccion = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-seccion",
    });

    const label = PAW.nuevoElemento("label", titulo, {
      class: "paw-filtros-label",
    });

    const select = PAW.nuevoElemento("select", "", {
      class: "paw-filtros-select",
      "data-filtro": nombreFiltro,
    });

    const opcionTodas = PAW.nuevoElemento("option", "Todos", {
      value: "",
    });
    select.appendChild(opcionTodas);

    this.obtenerOpcionesUnicas(nombreFiltro, campoLabel).forEach((opcion) => {
      const option = PAW.nuevoElemento("option", opcion.nombre, {
        value: opcion.id,
      });
      select.appendChild(option);
    });

    seccion.appendChild(label);
    seccion.appendChild(select);

    return seccion;
  }

  obtenerOpcionesUnicas(nombreFiltro, campoLabel) {
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

    return Array.from(opcionesMap.values()).sort((a, b) =>
      a.nombre.localeCompare(b.nombre),
    );
  }

  crearBarraOpciones() {
    const barra = PAW.nuevoElemento("div", "", {
      class: "paw-filtros-barra-opciones",
    });

    // Contador de resultados
    this.elementoContador = PAW.nuevoElemento("p", "", {
      class: "paw-filtros-contador",
    });
    barra.appendChild(this.elementoContador);

    // Selector de ordenamiento
    const selectorden = PAW.nuevoElemento("select", "", {
      class: "paw-filtros-select-orden",
      "aria-label": "Ordenar por",
    });

    const opciones = [
      { value: "titulo-asc", text: "Título (A-Z)" },
      { value: "titulo-desc", text: "Título (Z-A)" },
      { value: "precio-asc", text: "Precio (menor a mayor)" },
      { value: "precio-desc", text: "Precio (mayor a menor)" },
      { value: "autor-asc", text: "Autor (A-Z)" },
      { value: "autor-desc", text: "Autor (Z-A)" },
    ];

    opciones.forEach((opt) => {
      const option = PAW.nuevoElemento("option", opt.text, {
        value: opt.value,
      });
      selectorden.appendChild(option);
    });

    selectorden.value = this.estado.orden;
    barra.appendChild(selectorden);

    return barra;
  }

  registrarEventos() {
    // Toggle filtros en mobile
    const btnToggle = this.container.querySelector(".paw-filtros-toggle");
    btnToggle?.addEventListener("click", (e) => this.toggleFiltros(e));

    // Filtros de texto y precio
    const inputsFiltro = this.container.querySelectorAll("[data-filtro]");
    inputsFiltro.forEach((input) => {
      input.addEventListener("input", (e) => this.handleFiltroChange(e));
    });

    // Botón limpiar filtros
    const btnLimpiar = this.container.querySelector(".paw-filtros-limpiar");
    btnLimpiar?.addEventListener("click", () => this.limpiarFiltros());

    // Selector de orden
    const selectOrden = this.container.querySelector(
      ".paw-filtros-select-orden",
    );
    selectOrden?.addEventListener("change", (e) => this.handleOrdenChange(e));

    // Paginación
    this.container.addEventListener("click", (e) => {
      if (e.target.closest(".paw-filtros-btn-pagina")) {
        e.preventDefault();
        const btn = e.target.closest(".paw-filtros-btn-pagina");
        const pagina = parseInt(btn.dataset.pagina);
        this.irAPagina(pagina);
      }
    });

    // Scroll infinito
    if (this.opciones.enableScrollInfinito) {
      window.addEventListener("scroll", () => this.handleScroll());
    }
  }

  toggleFiltros(e) {
    const btn = e.currentTarget;
    const contenido = this.container.querySelector(".paw-filtros-contenido");
    const estaAbierto = contenido.getAttribute("aria-hidden") === "false";

    if (estaAbierto) {
      contenido.setAttribute("aria-hidden", "true");
      btn.setAttribute("aria-expanded", "false");
    } else {
      contenido.setAttribute("aria-hidden", "false");
      btn.setAttribute("aria-expanded", "true");
    }
  }

  handleFiltroChange(e) {
    const filtro = e.target.dataset.filtro;
    const valor = e.target.value;

    if (filtro === "genero_id") {
      this.estado.generoId = valor;
    } else if (filtro === "editorial_id") {
      this.estado.editorialId = valor;
    } else if (filtro === "idioma_id") {
      this.estado.idiomaId = valor;
    } else if (filtro === "autor_id") {
      this.estado.autorId = valor;
    } else if (filtro === "precioMin") {
      this.estado.precioMin = valor ? parseFloat(valor) : 0;
    } else if (filtro === "precioMax") {
      this.estado.precioMax = valor ? parseFloat(valor) : Infinity;
    }

    this.currentPage = 1;
    this.aplicarFiltros();
  }

  handleOrdenChange(e) {
    this.estado.orden = e.target.value;
    this.ordenarLibros();
    this.currentPage = 1;
    this.renderizarLibros();
    this.renderizarPaginacion();
  }

  limpiarFiltros() {
    this.estado.generoId = "";
    this.estado.editorialId = "";
    this.estado.idiomaId = "";
    this.estado.autorId = "";
    this.estado.precioMin = 0;
    this.estado.precioMax = Infinity;
    this.estado.orden = "titulo-asc";
    this.currentPage = 1;

    // Limpiar inputs
    const inputs = this.container.querySelectorAll("[data-filtro]");
    inputs.forEach((input) => {
      input.value = "";
    });

    const selectOrden = this.container.querySelector(
      ".paw-filtros-select-orden",
    );
    if (selectOrden) selectOrden.value = "titulo-asc";

    this.aplicarFiltros();
  }

  aplicarFiltros() {
    // Filtrar libros
    this.librosFiltrados = this.libros.filter((libro) => {
      const cumplePrecio =
        libro.precio >= this.estado.precioMin &&
        libro.precio <= this.estado.precioMax;

      const cumpleGenero =
        !this.estado.generoId || libro.genero_id == this.estado.generoId;

      const cumpleEditorial =
        !this.estado.editorialId ||
        libro.editorial_id == this.estado.editorialId;

      const cumpleIdioma =
        !this.estado.idiomaId || libro.idioma_id == this.estado.idiomaId;

      const cumpleAutor =
        !this.estado.autorId || libro.autor_id == this.estado.autorId;

      return (
        cumplePrecio &&
        cumpleGenero &&
        cumpleEditorial &&
        cumpleIdioma &&
        cumpleAutor
      );
    });

    this.ordenarLibros();
    this.currentPage = 1;
    this.renderizarLibros();
    this.renderizarPaginacion();
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
      } else if (criterio === "autor") {
        valorA = a.autor_nombre.toLowerCase();
        valorB = b.autor_nombre.toLowerCase();
      }

      if (valorA < valorB) return direccion === "asc" ? -1 : 1;
      if (valorA > valorB) return direccion === "asc" ? 1 : -1;
      return 0;
    });
  }

  renderizarLibros() {
    this.contenedorLibros.innerHTML = "";
    this.actualizarContador();

    if (this.librosFiltrados.length === 0) {
      this.contenedorLibros.innerHTML =
        '<p class="resultado-busqueda">No se encontraron libros que coincidan con tu búsqueda.</p>';
      return;
    }

    const inicio = (this.currentPage - 1) * this.opciones.itemsPorPagina;
    const fin = inicio + this.opciones.itemsPorPagina;
    const librosAMostrar = this.librosFiltrados.slice(inicio, fin);

    librosAMostrar.forEach((libro) => {
      const articulo = this.crearTarjetaLibro(libro);
      this.contenedorLibros.appendChild(articulo);
    });
  }

  crearTarjetaLibro(libro) {
    const articulo = PAW.nuevoElemento("article", "", {});

    const img = PAW.nuevoElemento("img", "", {
      src: `/assets/img/${libro.imagen}`,
      alt: libro.titulo,
    });

    const titulo = PAW.nuevoElemento("p", "", {});
    const tituloFuerte = PAW.nuevoElemento("strong", libro.titulo, {});
    titulo.appendChild(tituloFuerte);

    const autor = PAW.nuevoElemento("p", "", {});
    const autorEm = PAW.nuevoElemento("em", "Autor: ", {});
    autor.appendChild(autorEm);
    autor.appendChild(document.createTextNode(libro.autor_nombre));

    const precio = PAW.nuevoElemento("p", `$${libro.precio.toFixed(2)}`, {});

    const overlay = PAW.nuevoElemento("div", "", { class: "overlay" });
    const descripcion = PAW.nuevoElemento("p", libro.descripcion, {});
    const link = PAW.nuevoElemento("a", "Ver más", {
      href: `/detalle?id=${libro.id}`,
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

  renderizarPaginacion() {
    this.contenedorPaginacion.innerHTML = "";

    const totalPaginas = Math.ceil(
      this.librosFiltrados.length / this.opciones.itemsPorPagina,
    );

    if (totalPaginas <= 1) return;

    const nav = PAW.nuevoElemento("nav", "", {
      class: "paw-filtros-nav-paginacion",
      "aria-label": "Paginación de resultados",
    });

    // Botón anterior
    if (this.currentPage > 1) {
      const btnAnterior = PAW.nuevoElemento("a", "Anterior", {
        href: "#",
        class: "paw-filtros-btn-pagina paw-filtros-btn-anterior",
        "data-pagina": this.currentPage - 1,
      });
      nav.appendChild(btnAnterior);
    }

    // Números de página (mostrar máximo 5 en mobile, 7 en desktop)
    const maxBotones = window.innerWidth < 768 ? 5 : 7;
    let inicio = Math.max(1, this.currentPage - Math.floor(maxBotones / 2));
    let fin = Math.min(totalPaginas, inicio + maxBotones - 1);

    if (fin - inicio + 1 < maxBotones) {
      inicio = Math.max(1, fin - maxBotones + 1);
    }

    if (inicio > 1) {
      const btn1 = PAW.nuevoElemento("a", "1", {
        href: "#",
        class: "paw-filtros-btn-pagina",
        "data-pagina": "1",
      });
      nav.appendChild(btn1);

      if (inicio > 2) {
        const puntos = PAW.nuevoElemento("span", "...", {
          class: "paw-filtros-puntos",
        });
        nav.appendChild(puntos);
      }
    }

    for (let i = inicio; i <= fin; i++) {
      const btn = PAW.nuevoElemento("a", String(i), {
        href: "#",
        class: `paw-filtros-btn-pagina ${i === this.currentPage ? "activa" : ""}`,
        "data-pagina": String(i),
        "aria-label": `Página ${i}`,
        "aria-current": i === this.currentPage ? "page" : undefined,
      });
      nav.appendChild(btn);
    }

    if (fin < totalPaginas) {
      if (fin < totalPaginas - 1) {
        const puntos = PAW.nuevoElemento("span", "...", {
          class: "paw-filtros-puntos",
        });
        nav.appendChild(puntos);
      }

      const btnUltima = PAW.nuevoElemento("a", String(totalPaginas), {
        href: "#",
        class: "paw-filtros-btn-pagina",
        "data-pagina": String(totalPaginas),
      });
      nav.appendChild(btnUltima);
    }

    // Botón siguiente
    if (this.currentPage < totalPaginas) {
      const btnSiguiente = PAW.nuevoElemento("a", "Siguiente", {
        href: "#",
        class: "paw-filtros-btn-pagina paw-filtros-btn-siguiente",
        "data-pagina": this.currentPage + 1,
      });
      nav.appendChild(btnSiguiente);
    }

    this.contenedorPaginacion.appendChild(nav);
  }

  irAPagina(pagina) {
    const totalPaginas = Math.ceil(
      this.librosFiltrados.length / this.opciones.itemsPorPagina,
    );

    if (pagina >= 1 && pagina <= totalPaginas) {
      this.currentPage = pagina;
      this.renderizarLibros();
      this.renderizarPaginacion();

      // Scroll al inicio del contenedor
      this.contenedorLibros.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  }

  handleScroll() {
    if (!this.opciones.enableScrollInfinito) return;

    const scrollPos = window.innerHeight + window.scrollY;
    const documentHeight = document.documentElement.scrollHeight;
    const threshold = 300;

    if (scrollPos >= documentHeight - threshold && !this.cargandoMas) {
      const totalPaginas = Math.ceil(
        this.librosFiltrados.length / this.opciones.itemsPorPagina,
      );

      if (this.currentPage < totalPaginas) {
        this.currentPage++;
        this.renderizarLibrosAdicionales();
      }
    }
  }

  renderizarLibrosAdicionales() {
    if (this.librosFiltrados.length === 0) return;

    const inicio = (this.currentPage - 1) * this.opciones.itemsPorPagina;
    const fin = inicio + this.opciones.itemsPorPagina;
    const librosAMostrar = this.librosFiltrados.slice(inicio, fin);

    librosAMostrar.forEach((libro) => {
      const articulo = this.crearTarjetaLibro(libro);
      this.contenedorLibros.appendChild(articulo);
    });
  }

  actualizarContador() {
    const inicio = (this.currentPage - 1) * this.opciones.itemsPorPagina + 1;
    const fin = Math.min(
      this.currentPage * this.opciones.itemsPorPagina,
      this.librosFiltrados.length,
    );
    const total = this.librosFiltrados.length;

    if (total === 0) {
      this.elementoContador.textContent = "Sin resultados";
    } else {
      this.elementoContador.textContent = `Mostrando ${inicio} - ${fin} de ${total} resultados`;
    }
  }

  mostrarError(mensaje) {
    const error = PAW.nuevoElemento("div", mensaje, {
      class: "paw-filtros-error",
    });
    this.container.appendChild(error);
  }
}
