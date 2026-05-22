class PAWCarrito {
  constructor(contenedor) {
    this.contenedor = contenedor;
    this.triggerButton = null;
    this.estado = "cerrado";
    this.carritoPanel = null;
    this.fondoOverlay = null;
    this.closeBtn = null;
  }

  render() {
    if (!this.contenedor) {
      console.warn("PAWCarrito: contenedor inválido.");
      return;
    }

    this.crearBoton();
    this.crearEstructura();
    this.registrarEventos();
  }

  crearBoton() {
    if (this.contenedor.querySelector('.icono-carrito')) {
      this.triggerButton = this.contenedor.querySelector('.icono-carrito');
      return;
    }

    const iconCart = PAW.nuevoElemento("span", "shopping_cart", {
      class: "material-symbols-outlined"
    });

    this.triggerButton = PAW.nuevoElemento("label", iconCart, {
      class: "icono-carrito"
    });

    const formBusqueda = this.contenedor.querySelector(".header-busqueda");
    if (formBusqueda) {
      this.contenedor.insertBefore(this.triggerButton, formBusqueda);
    } else {
      this.contenedor.appendChild(this.triggerButton);
    }
  }

  crearEstructura() {
    // Si ya existe en el DOM, no lo volvemos a crear
    if (document.querySelector('.carrito-panel')) return;

    // Overlay de fondo
    this.fondoOverlay = PAW.nuevoElemento("div", "", {
      class: "fondo-carrito"
    });

    // Panel lateral del carrito
    this.carritoPanel = PAW.nuevoElemento("aside", "", {
      class: "carrito-panel"
    });

    // Header del carrito
    const header = PAW.nuevoElemento("header", "", {
      class: "carrito-header"
    });

    const h2 = PAW.nuevoElemento("h2", "Carrito de compras");
    
    // Ícono de cierre dinámico (Material Symbols)
    const iconClose = PAW.nuevoElemento("span", "close", {
      class: "material-symbols-outlined"
    });

    // Botón de cierre dinámico
    this.closeBtn = PAW.nuevoElemento("button", iconClose, {
      class: "carrito-cerrar",
      type: "button",
      "aria-label": "Cerrar carrito"
    });

    header.appendChild(h2);
    header.appendChild(this.closeBtn);

    // Contenido por defecto (carrito vacío)
    const pVacio = PAW.nuevoElemento("p", "", {
      class: "carrito-vacio"
    });
    
    const iconoInfo = PAW.nuevoElemento("span", "info", {
      class: "material-symbols-outlined simbolos"
    });
    
    pVacio.appendChild(iconoInfo);
    pVacio.appendChild(document.createTextNode(" El carrito de compras está vacío."));

    // Construcción final del panel
    this.carritoPanel.appendChild(header);
    this.carritoPanel.appendChild(pVacio);

    // Inyectamos el carrito en el body
    document.body.appendChild(this.fondoOverlay);
    document.body.appendChild(this.carritoPanel);
  }

  registrarEventos() {
    // Abrir carrito al hacer clic en el disparador (ícono móvil)
    this.triggerButton.addEventListener("click", (e) => {
      e.preventDefault(); // Evitamos el comportamiento por defecto del label
      this.estado === "cerrado" ? this.abrirCarrito() : this.cerrarCarrito();
    });

    // Abrir carrito al hacer clic en el disparador de escritorio
    const botonDesktop = document.querySelector('label[for="mostrar-carrito"]');
    if (botonDesktop) {
      botonDesktop.addEventListener("click", (e) => {
        e.preventDefault();
        this.estado === "cerrado" ? this.abrirCarrito() : this.cerrarCarrito();
      });
    }

    // Cerrar carrito al hacer clic en el botón de cerrar
    if (this.closeBtn) {
      this.closeBtn.addEventListener("click", () => {
        this.cerrarCarrito();
      });
    }

    // Cerrar carrito al hacer clic fuera del panel (en el overlay)
    if (this.fondoOverlay) {
      this.fondoOverlay.addEventListener("click", () => {
        this.cerrarCarrito();
      });
    }
  }

  abrirCarrito() {
    if (!this.carritoPanel || !this.fondoOverlay) return;

    this.carritoPanel.classList.add("is-active");
    this.fondoOverlay.classList.add("is-active");
    this.estado = "abierto";
  }

  cerrarCarrito() {
    if (!this.carritoPanel || !this.fondoOverlay) return;

    this.carritoPanel.classList.remove("is-active");
    this.fondoOverlay.classList.remove("is-active");
    this.estado = "cerrado";
  }
}
