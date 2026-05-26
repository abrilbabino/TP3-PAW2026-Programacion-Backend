class PAWPaginacion {
  // Inicializa el estado fusionando opciones por defecto con el spread operator (...).
  // Implementa el Patrón de Delegación de Eventos en el contenedor padre, utilizando e.target.closest() para capturar clics en los botones dinámicos sin necesidad de reasignar listeners en cada renderizado.
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

  // Métodos de cálculo: Utilizan el objeto estándar Math (ceil, max, min) para derivar matemáticamente los rangos de índices (slice) y el total de páginas.
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

  // Valida los límites de navegación, actualiza el estado interno y ejecuta la función de callback (onCambioPagina) inyectada por el orquestador para volver a renderizar la grilla de datos.
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

  // Reconstruye la barra de navegación. 
  // Accede a window.innerWidth para aplicar lógica responsive en JS, reduciendo la cantidad de botones en dispositivos móviles. 
  // Implementa un algoritmo de "ventana deslizante" con (...) para manejar volúmenes grandes.
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

  // fábrica de nodos) que construye las etiquetas <a>.
  // Incorpora directivas de Accesibilidad Web (ARIA) como 'aria-label' y 'aria-current="page"' para indicar semánticamente la página activa a los lectores de pantalla.
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
