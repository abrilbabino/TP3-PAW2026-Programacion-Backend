class AppPAW {
  constructor() {
    document.addEventListener("DOMContentLoaded", () => {
      this.init();
    });
  }

  init() {
    PAW.cargarScript("paw-utils", "/assets/js/components/paw.js", () => {
      this.initMenu();
      this.initValidador();
    });
  }

  initMenu() {
    const navElement =
      document.querySelector("#paw-menu-root") || document.querySelector("nav");
    if (!navElement) {
      return;
    }

    PAW.cargarScript(
      "PAW-Menu-Script",
      "/assets/js/components/paw-menu.js",
      () => {
        const menu = new PAWMenu(navElement);
        menu.render();
      },
    );
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
}

// Se instancia el objeto global para disparar el ciclo de vida de la aplicación
const app = new AppPAW();
