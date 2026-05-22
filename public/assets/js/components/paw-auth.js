class PAWAuth {
    constructor(appInstance) {
        this.app = appInstance;
        this.loginForm = document.querySelector(".login-form");
        this.registroForm = document.querySelector(".registro-form");

        this.loginCloseBtn = document.querySelector(".login-cerrar");
        this.registroCloseBtn = document.querySelector(".registro-cerrar");
    }

    init() {
        if (this.loginForm) {
            this.loginForm.addEventListener("submit", (e) => this.handleAuthSubmit(e, this.loginForm, true));
        }
        if (this.registroForm) {
            this.registroForm.addEventListener("submit", (e) => this.handleAuthSubmit(e, this.registroForm, false));
        }

        // Limpieza de formularios al cerrar modales
        if (this.loginCloseBtn && this.loginForm) {
            this.loginCloseBtn.addEventListener("click", () => this.limpiarFormulario(this.loginForm));
        }
        if (this.registroCloseBtn && this.registroForm) {
            this.registroCloseBtn.addEventListener("click", () => this.limpiarFormulario(this.registroForm));
        }

        // Limpiar al clickear el fondo del modal
        document.body.addEventListener("click", (e) => {
            if (e.target.classList.contains("fondo-modal")) {
                if (this.loginForm) this.limpiarFormulario(this.loginForm);
                if (this.registroForm) this.limpiarFormulario(this.registroForm);
            }
        });
    }

    async handleAuthSubmit(e, form, isLogin) {
        if (e.defaultPrevented) return;
        e.preventDefault();

        this.limpiarErrores(form);

        const formData = new FormData(form);
        const url = form.action || window.location.href;
        const method = form.method || "POST";

        try {
            const response = await fetch(url, {
                method: method.toUpperCase(),
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                if (isLogin) {
                    window.location.reload();
                } else {
                    if (this.app && typeof this.app.mostrarMensajeExito === 'function') {
                        this.app.mostrarMensajeExito(form, {
                            titulo: "¡Registro exitoso!",
                            mensaje: "Tu cuenta ha sido creada. Ahora puedes iniciar sesión.",
                            textoBoton: "Ir al Inicio",
                            urlBoton: "/"
                        });
                        
                        // Limpieza: Asegurarnos de vaciar el formulario para que al cerrar/volver
                        // no queden datos viejos detrás del mensaje de éxito.
                        this.limpiarFormulario(form);
                    }
                }
            } else {
                this.mostrarErroresInline(form, data);
            }
        } catch (error) {
            console.error("Auth Error:", error);
            this.mostrarErrorGeneral(form, "No se pudo conectar con el servidor. Intenta de nuevo.");
        }
    }

    mostrarErroresInline(form, data) {
        if (data.errors && Object.keys(data.errors).length > 0) {
            Object.keys(data.errors).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.classList.add('input-invalido');
                    const span = document.createElement('span');
                    span.className = 'msg-error';
                    span.textContent = data.errors[key];
                    
                    if (input.parentNode.classList.contains('campo-contraseña')) {
                        input.parentNode.insertAdjacentElement('afterend', span);
                    } else {
                        input.insertAdjacentElement('afterend', span);
                    }
                }
            });
        } else {
            this.mostrarErrorGeneral(form, data.message || "Error en la autenticación.");
        }
    }

    mostrarErrorGeneral(form, mensaje) {
        const errorP = document.createElement('p');
        errorP.className = 'error-auth';
        errorP.textContent = mensaje;
        form.insertBefore(errorP, form.firstChild);
    }

    limpiarErrores(form) {
        form.querySelectorAll('.msg-error, .error-auth').forEach(el => el.remove());
        form.querySelectorAll('.input-invalido').forEach(el => el.classList.remove('input-invalido'));
    }

    limpiarFormulario(form) {
        form.reset();
        this.limpiarErrores(form);
    }
}
