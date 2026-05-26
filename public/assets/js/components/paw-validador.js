class PAWValidador {
  // Emplea Array.from() y .filter() evaluando la propiedad nativa 'willValidate' para recolectar únicamente los campos de formulario sujetos a validación.
  // Apaga la validación visual nativa del navegador mediante el atributo 'novalidate'.
  constructor(formulario) {
    this.formulario = formulario;
    this.inputs = Array.from(
      this.formulario.querySelectorAll("input, textarea, select"),
    ).filter(
      (elemento) =>
        elemento.willValidate &&
        !elemento.disabled &&
        elemento.type !== "hidden",
    );

    this.formulario.setAttribute("novalidate", "true");
    this.registrarEventos();
  }

  // Asigna listeners.
  // Usa 'blur' para validar cuando el usuario termina de interactuar con el campo.
  // Usa 'input' condicionado para proveer feedback en tiempo real únicamente si el campo ya se encontraba en un estado inválido.
  registrarEventos() {
    this.inputs.forEach((input) => {
      input.addEventListener("blur", () => this.validarCampo(input));
      input.addEventListener("input", () => {
        if (input.classList.contains("input-invalido")) {
          this.validarCampo(input);
        }
      });
    });

    this.formulario.addEventListener("submit", (event) =>
      this.handleSubmit(event),
    );
  }

  // Intercepta el evento de envío. 
  // Utiliza Array.prototype.reduce() para computar el estado global del formulario.
  // Si es inválido, detiene el request (preventDefault) y emplea Array.prototype.find() junto con el método nativo .focus() para dirigir la atención al primer campo erróneo.
  handleSubmit(event) {
    const formularioValido = this.inputs.reduce((esValido, input) => {
      const campoValido = this.validarCampo(input);
      return esValido && campoValido;
    }, true);

    if (!formularioValido) {
      event.preventDefault();
      const primerInvalido = this.inputs.find(
        (input) => !input.checkValidity(),
      );
      if (primerInvalido) {
        primerInvalido.focus();
      }
    }
  }

  // validarCampo: Evalúa el estado mediante input.checkValidity() de la API de validación.
  validarCampo(input) {
    if (input.checkValidity()) {
      this.limpiarError(input);
      return true;
    }

    const mensaje = this.obtenerMensajeError(input);
    this.mostrarError(input, mensaje);
    return false;
  }

  // mostrarError: Inyecta el mensaje dinámicamente usando insertAdjacentElement('afterend').
  mostrarError(input, mensaje) {
    this.limpiarError(input);
    input.classList.add("input-invalido");

    const mensajeElemento = PAW.nuevoElemento("span", mensaje, {
      class: "msg-error",
    });

    input.insertAdjacentElement("afterend", mensajeElemento);
  }

  // limpiarError: Realiza DOM Traversal usando input.nextElementSibling para identificar y remover (.remove()) el nodo de error asociado sin afectar el resto del árbol.
  limpiarError(input) {
    input.classList.remove("input-invalido");

    const siguiente = input.nextElementSibling;
    if (siguiente && siguiente.classList.contains("msg-error")) {
      siguiente.remove();
    }
  }

  // Accede al objeto ValidityState (input.validity).
  // Mapea las flags booleanas nativas (valueMissing, typeMismatch, tooShort) generadas automáticamente por los atributos hacia mensajes legibles en español.
  obtenerMensajeError(input) {
    const validity = input.validity;

    if (validity.valueMissing) {
      return "Este campo es obligatorio.";
    }

    if (validity.typeMismatch) {
      if (input.type === "email") {
        return "Ingrese un correo electrónico válido.";
      }
      if (input.type === "url") {
        return "Ingrese una URL válida.";
      }
      return "El formato ingresado no es válido.";
    }

    if (validity.tooShort) {
      return `Debe tener al menos ${input.minLength} caracteres.`;
    }

    if (validity.tooLong) {
      return `No puede superar ${input.maxLength} caracteres.`;
    }

    if (validity.rangeUnderflow) {
      return `El valor debe ser mayor o igual a ${input.min}.`;
    }

    if (validity.rangeOverflow) {
      return `El valor debe ser menor o igual a ${input.max}.`;
    }

    if (validity.patternMismatch) {
      return "El formato no coincide con el esperado.";
    }

    if (validity.stepMismatch) {
      return "El valor ingresado no cumple con el paso requerido.";
    }

    if (validity.badInput) {
      return "El valor ingresado no es válido.";
    }

    return "El campo no es válido.";
  }
}
