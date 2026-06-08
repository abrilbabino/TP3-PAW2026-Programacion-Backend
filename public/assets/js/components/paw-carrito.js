class PAWCarrito {
  // Define el estado base y prepara las referencias a los nodos del DOM.
  
  constructor(contenedor) {
    this.contenedor = contenedor;
    this.triggerButton = null;
    this.estado = "cerrado";
    this.carritoPanel = null;
    this.fondoOverlay = null;
    this.closeBtn = null;
  }
  //  Implementa una cláusula para evitar errores si el contenedor principal no existe en la vista actual.
  render() {
    if (!this.contenedor) {
      console.warn("PAWCarrito: contenedor inválido.");
      return;
    }

    this.crearBoton();
    this.crearEstructura();
    this.registrarEventos();

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('abrir_carrito') && urlParams.get('abrir_carrito') === 'true') {
      this.abrirCarrito();
      urlParams.delete('abrir_carrito');
      const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
      window.history.replaceState({}, document.title, newUrl);
    }
  }

  // crearBoton: Construye el disparador del carrito. 
  // Utiliza Node.insertBefore() de la API del DOM para posicionar el ícono estratégicamente antes del buscador.
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

  // Construye el panel lateral y el overlay en memoria.
  // Los inyecta directamente en el document.body  para garantizar que el CSS los posicione por encima de toda la aplicación.
  crearEstructura() {
    // Si ya existe en el DOM, no lo volvemos a crear, pero conectamos las variables
    if (document.querySelector('.carrito-panel')) {
      this.carritoPanel = document.querySelector('.carrito-panel');
      this.closeBtn = this.carritoPanel.querySelector('.carrito-cerrar');
      
      this.fondoOverlay = document.querySelector('.fondo-carrito');
      if (!this.fondoOverlay) {
        this.fondoOverlay = PAW.nuevoElemento("div", "", { class: "fondo-carrito" });
        document.body.appendChild(this.fondoOverlay);
      }
      return;
    }

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

  //Asigna los escuchadores de eventos. 
  // Utiliza e.preventDefault() para anular el comportamiento nativo de la etiqueta <label>. 
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

    // Interceptar formularios de agregar al carrito (AJAX)
    document.addEventListener('submit', (e) => {
      const form = e.target.closest('form[action="/agregarCarrito"]');
      if (form) {
        e.preventDefault();
        const formData = new FormData(form);
        
        fetch('/agregarCarrito', {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'unauthorized') {
            // Abrir modal de login
            const loginTrigger = document.querySelector('label[for="mostrar-login"]');
            if (loginTrigger) loginTrigger.click();
          } else if (data.status === 'success') {
            // Recargar página con parámetro para abrir el carrito
            const url = new URL(window.location.href);
            url.searchParams.set('abrir_carrito', 'true');
            window.location.href = url.toString();
          }
        })
        .catch(err => console.error("Error agregando al carrito:", err));
      }
    });

    // Auto-actualizar carrito cuando cambia la cantidad
    document.addEventListener('change', (e) => {
      if (e.target.matches('.carrito-item-input')) {
        const form = e.target.closest('form[data-paw-actualizar-carrito="true"]');
        if (form) {
          form.submit();
        }
      }
    });
  }

  // abrirCarrito / cerrarCarrito: Modifican el estado lógico y actualizan el DOM utilizando classList.add/remove. 
  // JS gestiona el estado y CSS se encarga exclusivamente de las transiciones visuales.
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
