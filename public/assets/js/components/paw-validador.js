/*
 * public/assets/js/components/paw-validador.js
 * Validador de formularios para PAW.
 */

class PAWValidador {
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

  validarCampo(input) {
    if (input.checkValidity()) {
      this.limpiarError(input);
      return true;
    }

    const mensaje = this.obtenerMensajeError(input);
    this.mostrarError(input, mensaje);
    return false;
  }

  mostrarError(input, mensaje) {
    this.limpiarError(input);
    input.classList.add("input-invalido");

    const mensajeElemento = PAW.nuevoElemento("span", mensaje, {
      class: "msg-error",
    });

    input.insertAdjacentElement("afterend", mensajeElemento);
  }

  limpiarError(input) {
    input.classList.remove("input-invalido");

    const siguiente = input.nextElementSibling;
    if (siguiente && siguiente.classList.contains("msg-error")) {
      siguiente.remove();
    }
  }

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
