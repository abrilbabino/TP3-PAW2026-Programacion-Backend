class PAWPaginacion {
  constructor(container, opciones = {}) {
    this.container = container;
    this.opciones = {
      itemsPorPagina: opciones.itemsPorPagina || 6,
      onCambioPagina: opciones.onCambioPagina || null,
      ...opciones,
    };
    this.totalItems = 0;
    this.paginaActual = 1;

    this.container.addEventListener("click", (e) => {
      const btn = e.target.closest(".paw-filtros-btn-pagina");
      if (!btn) return;

      e.preventDefault();
      this.irAPagina(parseInt(btn.dataset.pagina));
    });
  }

  actualizar(totalItems, paginaActual = this.paginaActual) {
    this.totalItems = totalItems;
    this.paginaActual = paginaActual;
    this.renderizar();
  }

  obtenerRango() {
    const inicio = (this.paginaActual - 1) * this.opciones.itemsPorPagina;
    const fin = inicio + this.opciones.itemsPorPagina;

    return { inicio, fin };
  }

  obtenerCantidadVisible() {
    if (this.totalItems === 0) return 0;

    const { inicio, fin } = this.obtenerRango();
    return Math.max(0, Math.min(fin, this.totalItems) - inicio);
  }

  obtenerTotalPaginas() {
    return Math.ceil(this.totalItems / this.opciones.itemsPorPagina);
  }

  irAPagina(pagina) {
    const totalPaginas = this.obtenerTotalPaginas();

    if (pagina < 1 || pagina > totalPaginas || pagina === this.paginaActual) {
      return;
    }

    this.paginaActual = pagina;
    this.renderizar();

    if (this.opciones.onCambioPagina) {
      this.opciones.onCambioPagina(pagina);
    }
  }

  renderizar() {
    this.container.innerHTML = "";

    const totalPaginas = this.obtenerTotalPaginas();
    if (totalPaginas <= 1) return;

    const nav = PAW.nuevoElemento("nav", "", {
      class: "paw-filtros-nav-paginacion",
      "aria-label": "Paginación de resultados",
    });

    if (this.paginaActual > 1) {
      nav.appendChild(
        this.crearBoton("Anterior", this.paginaActual - 1, [
          "paw-filtros-btn-anterior",
        ]),
      );
    }

    const maxBotones = window.innerWidth < 768 ? 5 : 7;
    let inicio = Math.max(1, this.paginaActual - Math.floor(maxBotones / 2));
    let fin = Math.min(totalPaginas, inicio + maxBotones - 1);

    if (fin - inicio + 1 < maxBotones) {
      inicio = Math.max(1, fin - maxBotones + 1);
    }

    if (inicio > 1) {
      nav.appendChild(this.crearBoton("1", 1));

      if (inicio > 2) {
        nav.appendChild(
          PAW.nuevoElemento("span", "...", {
            class: "paw-filtros-puntos",
          }),
        );
      }
    }

    for (let i = inicio; i <= fin; i++) {
      nav.appendChild(this.crearBoton(String(i), i));
    }

    if (fin < totalPaginas) {
      if (fin < totalPaginas - 1) {
        nav.appendChild(
          PAW.nuevoElemento("span", "...", {
            class: "paw-filtros-puntos",
          }),
        );
      }

      nav.appendChild(this.crearBoton(String(totalPaginas), totalPaginas));
    }

    if (this.paginaActual < totalPaginas) {
      nav.appendChild(
        this.crearBoton("Siguiente", this.paginaActual + 1, [
          "paw-filtros-btn-siguiente",
        ]),
      );
    }

    this.container.appendChild(nav);
  }

  crearBoton(texto, pagina, clasesExtra = []) {
    const clases = ["paw-filtros-btn-pagina", ...clasesExtra];
    const atributos = {
      href: "#",
      class: clases.join(" "),
      "data-pagina": String(pagina),
      "aria-label": `Página ${pagina}`,
    };

    if (pagina === this.paginaActual) {
      clases.push("activa");
      atributos.class = clases.join(" ");
      atributos["aria-current"] = "page";
    }

    return PAW.nuevoElemento("a", texto, atributos);
  }
}
