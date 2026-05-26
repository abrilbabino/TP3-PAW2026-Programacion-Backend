class PAWModalesAuth {
  // Captura referencias del DOM usando querySelector
  constructor() {
    this.loginPanel = document.querySelector("#login-panel");
    this.registroPanel = document.querySelector("#registro-panel");
    
    // Disparadores
    this.loginTriggers = document.querySelectorAll('label[for="mostrar-login"]');
    this.registroTrigger = document.querySelector("#btn-abrir-registro");
    
    // Botones de cierre
    this.loginCloseBtn = document.querySelector(".login-cerrar");
    this.registroCloseBtn = document.querySelector(".registro-cerrar");
    
    this.fondoOverlay = null;
  }

  render() {
    if (!this.loginPanel && !this.registroPanel) {
      return; // No hay modales en esta página
    }
    
    this.crearOverlay();
    this.inicializarTogglePasswords();
    this.registrarEventos();
  }

  // Verifica la preexistencia del nodo para evitar duplicados en el DOM.
  // Inyecta el contenedor directamente en el body.
  crearOverlay() {
    // Evitamos duplicar el overlay si ya se inyectó
    if (document.querySelector('.fondo-modal')) {
      this.fondoOverlay = document.querySelector('.fondo-modal');
      return;
    }

    this.fondoOverlay = PAW.nuevoElemento("div", "", {
      class: "fondo-modal"
    });
    
    document.body.appendChild(this.fondoOverlay);
  }

  // Itera sobre la NodeList de triggers asignando listeners.
  // Usa preventDefault() para anular el comportamiento nativo de los <label>.
  // En la transición Login -> Registro, manipula el DOM  para mantener el overlay activo.
  registrarEventos() {
    // Eventos para abrir el modal de Login
    this.loginTriggers.forEach(trigger => {
      trigger.addEventListener("click", (e) => {
        e.preventDefault();
        this.abrirLogin();
      });
    });

    // Evento para cambiar de Login a Registro fluidamente
    if (this.registroTrigger) {
      this.registroTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        this.loginPanel.classList.remove("is-active"); // Cerramos solo el panel, mantenemos el fondo activo
        this.abrirRegistro();
      });
    }

    // Botones de Cierre
    if (this.loginCloseBtn) {
      this.loginCloseBtn.addEventListener("click", () => this.cerrarLogin());
    }

    if (this.registroCloseBtn) {
      this.registroCloseBtn.addEventListener("click", () => this.cerrarRegistro());
    }

    // Cerrar al clickear el overlay
    if (this.fondoOverlay) {
      this.fondoOverlay.addEventListener("click", () => this.cerrarTodos());
    }
  }

  abrirLogin() {
    if (!this.loginPanel) return;
    this.cerrarRegistro(); // Aseguramos que el otro esté cerrado
    this.loginPanel.classList.add("is-active");
    this.fondoOverlay.classList.add("is-active");
  }

  cerrarLogin() {
    if (!this.loginPanel) return;
    this.loginPanel.classList.remove("is-active");
    this.fondoOverlay.classList.remove("is-active");
  }

  abrirRegistro() {
    if (!this.registroPanel) return;
    // No llamamos cerrarLogin completo para evitar que titile el overlay de fondo
    this.registroPanel.classList.add("is-active");
    this.fondoOverlay.classList.add("is-active");
  }

  cerrarRegistro() {
    if (!this.registroPanel) return;
    this.registroPanel.classList.remove("is-active");
    this.fondoOverlay.classList.remove("is-active");
  }

  cerrarTodos() {
    if (this.loginPanel) this.loginPanel.classList.remove("is-active");
    if (this.registroPanel) this.registroPanel.classList.remove("is-active");
    if (this.fondoOverlay) this.fondoOverlay.classList.remove("is-active");
  }

  // Implementa navegación relativa por el árbol DOM 
  // Mediante previousElementSibling para encontrar el input asociado.
  // Modifica la propiedad .type del nodo dinámicamente para alternar la visibilidad de la password.
  inicializarTogglePasswords() {
    const toggleBtns = document.querySelectorAll('.mostrar-contraseña');
    toggleBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const input = btn.previousElementSibling;
        if (!input) return;

        if (input.type === 'password') {
          input.type = 'text';
          btn.textContent = 'visibility';
        } else {
          input.type = 'password';
          btn.textContent = 'visibility_off';
        }
      });
    });
  }
}
