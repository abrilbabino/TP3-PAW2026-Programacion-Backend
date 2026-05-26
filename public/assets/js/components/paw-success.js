class PAWSuccessMessage {
  constructor(formElement, options = {}) {
    this.formElement = formElement;
    this.titulo = options.titulo || "¡Operación exitosa!";
    this.mensaje = options.mensaje || "Tus datos se procesaron correctamente.";
    this.textoBoton = options.textoBoton || "Ir al Inicio";
    this.urlBoton = options.urlBoton || "/";

    PAW.cargarCSS("/assets/css/paw-success.css");
  }

  // Aplica estilos en línea para un fade-out.
  // Utiliza setTimeout() para sincronizar el cambio de display: "none" cuando termina la transición CSS.
  mostrar() {
    if (!this.formElement) return;

    // 1. Ocultar el formulario original con una transición suave (fade-out)
    this.formElement.style.transition = "opacity 0.3s ease";
    this.formElement.style.opacity = "0";

    setTimeout(() => {
      this.formElement.style.display = "none";

      // 2. Crear contenedor del mensaje de éxito
      const contenedor = PAW.nuevoElemento("div", "", { class: "paw-success-container" });

      // Icono (Material Symbols)
      const icono = PAW.nuevoElemento("span", "check_circle", { class: "material-symbols-outlined paw-success-icon" });
      
      // Título
      const titulo = PAW.nuevoElemento("h2", this.titulo, { class: "paw-success-title" });
      
      // Mensaje
      const mensaje = PAW.nuevoElemento("p", this.mensaje, { class: "paw-success-message" });
      
      // Botón CTA (Call to Action)
      const boton = PAW.nuevoElemento("a", this.textoBoton, { 
        href: this.urlBoton, 
        class: "btn-primario paw-success-btn" 
      });

      // Element.append() para insertar múltiples nodos hijos simultáneamente.
      contenedor.append(icono, titulo, mensaje, boton);
      
      // 3. Insertar justo después del formulario original
      this.formElement.parentNode.insertBefore(contenedor, this.formElement.nextSibling);

      // 4. Activar el fade-in y el movimiento hacia arriba
      requestAnimationFrame(() => {
        contenedor.classList.add("is-visible");
      });

    }, 300);
  }
}
