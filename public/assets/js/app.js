class AppPAW {
  constructor() {
    document.addEventListener("DOMContentLoaded", () => {
      this.init();
    });
  }

  init() {
    // Carga diferida del componente Menu
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
    // Aquí se pueden inicializar futuros componentes de forma aislada
    // this.initCarrusel();
  }
}

// Se instancia el objeto global para disparar el ciclo de vida de la aplicación
const app = new AppPAW();
