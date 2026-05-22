class AppPAW {
  constructor() {
    document.addEventListener("DOMContentLoaded", () => {
      this.init();
    });
  }

  init() {
    this._initMenu();
    this._initCarousel();
    this._initEfectos();
    this.initValidador();
    this._initBusquedas();
    this._initFiltros();
  }

  _initMenu() {
    const navElement = document.querySelector("nav");
    if (navElement) {
      PAW.cargarScript(
        "PAW-Menu-Script",
        "/assets/js/components/paw-menu.js",
        () => {
          // Instanciamos el menú pasándole el nodo del contenedor
          let menu = new PAWMenu(navElement);
          menu.render();
        },
      );
    }
  }

  _initCarousel() {
    const carruseles = document.querySelectorAll("[data-paw-carousel]");
    if (carruseles.length === 0) return;
    PAW.cargarScript(
      "PAW-Carousel-Script",
      "/assets/js/components/paw-carousel.js",
      () => {
        carruseles.forEach((container) => {
          new PAWCarousel(container);
        });
      },
    );
  }

  _initEfectos() {
    const selector = document.querySelector(".efectos-selector");
    if (!selector) return;
    selector.addEventListener("click", function (e) {
      const btn = e.target.closest(".efecto-btn");
      if (!btn) return;
      const carrusel = document.querySelector("[data-paw-carousel]");
      if (!carrusel || !carrusel.pawCarousel) return;
      carrusel.pawCarousel.cambiarEfecto(btn.dataset.efecto);
      selector.querySelectorAll(".efecto-btn").forEach(function (boton) {
        boton.classList.remove("active");
      });
      btn.classList.add("active");
    });
  }
  initValidador() {
    const libroForm = document.querySelector("#form-nuevo-libro");
    if (!libroForm) {
      return;
    }

    PAW.cargarScript(
      "paw-validador",
      "/assets/js/components/paw-validador.js",
      () => {
        new PAWValidador(libroForm);
      },
    );
  }

  _initBusquedas() {
    const contenedores = document.querySelectorAll("[data-paw-busquedas]");
    if (contenedores.length === 0) return;
    PAW.cargarScript(
      "PAW-Busquedas-Script",
      "/assets/js/components/paw-busquedas.js",
      () => {
        contenedores.forEach(function (container) {
          new PAWBusquedas(container);
        });
      },
    );
  }

  _initFiltros() {
    const contenedores = document.querySelectorAll("[data-paw-filtros]");
    if (contenedores.length === 0) return;
    PAW.cargarScript(
      "PAW-Filtros-Script",
      "/assets/js/components/paw-filtros.js",
      () => {
        contenedores.forEach(function (container) {
          const opciones = {
            itemsPorPagina: parseInt(container.dataset.itemsPorPagina) || 6,
            enableScrollInfinito: container.dataset.scrollInfinito !== "false",
          };
          new PAWFiltros(container, opciones);
        });
      },
    );
  }
}

// Se instancia el objeto global para disparar el ciclo de vida de la aplicación
const app = new AppPAW();
