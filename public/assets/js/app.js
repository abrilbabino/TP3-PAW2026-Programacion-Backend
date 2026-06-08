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
    this._initResultadosBusqueda();
    this._initCarrito();
    this._initModalesAuth();
    this._initUploader();
    this._initFiltros();
    this._initAuth();
    this._initCrearLibro();
    this._initAutocomplete();
  }

  _initAutocomplete() {
    if (document.querySelector("#libro")) {
      PAW.cargarScript(
        "PAW-Autocomplete-Script",
        "/assets/js/components/paw-autocomplete.js",
        () => {
          new PAWAutocomplete("#libro", "/api/libros/buscar");
        }
      );
    }
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

  _initCarrito() {
    const barraNavegacion = document.querySelector(".Barra-navegacion");
    if (barraNavegacion) {
      PAW.cargarScript(
        "PAW-Carrito-Script",
        "/assets/js/components/paw-carrito.js",
        () => {
          let carrito = new PAWCarrito(barraNavegacion);
          carrito.render();
        },
      );
    }
  }

  _initModalesAuth() {
    // Solo instanciamos si la página tiene los modales (usualmente sí, vienen del layout general)
    if (
      document.querySelector("#login-panel") ||
      document.querySelector("#registro-panel")
    ) {
      PAW.cargarScript(
        "PAW-ModalesAuth-Script",
        "/assets/js/components/paw-modales-auth.js",
        () => {
          let modalesAuth = new PAWModalesAuth();
          modalesAuth.render();
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
    const forms = document.querySelectorAll("#form-nuevo-libro, #form-reserva");
    if (forms.length === 0) {
      return;
    }

    PAW.cargarScript(
      "paw-validador",
      "/assets/js/components/paw-validador.js",
      () => {
        forms.forEach(form => new PAWValidador(form));
      },
    );
  }

  _initFiltros() {
    const contenedores = document.querySelectorAll("[data-paw-filtros]");
    if (contenedores.length === 0) return;
    PAW.cargarScript(
      "PAW-Visualizacion-Script",
      "/assets/js/components/PAWVisualizacion.js",
      () => {
        PAW.cargarScript(
          "PAW-Filtros-Script",
          "/assets/js/components/PAWFiltros.js",
          () => {
            contenedores.forEach(function (container) {
              const opciones = {
                itemsPorPagina: parseInt(container.dataset.itemsPorPagina) || 6,
              };
              new PAWFiltros(container, opciones);
            });
          },
        );
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

  _initResultadosBusqueda() {
    const contenedor = document.querySelector("[data-paw-resultados-busqueda]");
    if (!contenedor) return;

    PAW.cargarScript(
      "PAW-Visualizacion-Script",
      "/assets/js/components/PAWVisualizacion.js",
      () => {
        try {
          const librosJson = contenedor.dataset.pawResultadosBusqueda;
          const libros = JSON.parse(librosJson);
          const itemsPorPagina = parseInt(contenedor.dataset.itemsPorPagina) || 6;
          
          const grilla = contenedor.querySelector(".grilla-libros");
          const paginacion = contenedor.querySelector(".paginacion");
          
          if (grilla && paginacion) {
            const visualizacion = new PAWVisualizacion(grilla, paginacion, itemsPorPagina);
            visualizacion.actualizarDatos(libros);
          }
        } catch (error) {
          console.error("Error al inicializar visualización de búsqueda:", error);
        }
      }
    );
  }

  _initUploader() {
    if (document.querySelector("#portada-uploader")) {
      PAW.cargarScript(
        "PAW-Uploader-Script",
        "/assets/js/components/paw-uploader.js",
        () => {
          new PAWUploader("#portada-uploader");
        },
      );
    }
  }

    mostrarMensajeExito(formElement, options = {}) {
        PAW.cargarScript(
            "PAW-Success-Script",
            "/assets/js/components/paw-success.js",
            () => {
                const mensaje = new PAWSuccessMessage(formElement, options);
                mensaje.mostrar();
            }
        );
    }

    _initAuth() {
        PAW.cargarScript(
            "PAW-Auth-Script",
            "/assets/js/components/paw-auth.js",
            () => {
                const auth = new PAWAuth(this);
                auth.init();
            }
        );
    }

    _initCrearLibro() {
        if (document.querySelector("#form-nuevo-libro")) {
            PAW.cargarScript(
                "PAW-CrearLibro-Script",
                "/assets/js/components/paw-crear-libro.js",
                () => {
                    new PAWCrearLibro();
                }
            );
        }
    }
}

// Se instancia el objeto global para disparar el ciclo de vida de la aplicación
const app = new AppPAW();
