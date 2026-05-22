class PAWBusquedas {
  constructor(formulario) {
    this.formulario = formulario;
    this.input = formulario.querySelector("input[name='busqueda']");
    if (!this.input) return;
    PAW.cargarCSS("/assets/css/busqueda.css");
    this.clave = "paw-ultimas-busquedas";
    this.dropdown = null;
    this.guardarSiHayTerminoEnURL();
    this.crearDropdown();
    this.registrarEventos();
  }

  guardarSiHayTerminoEnURL() {
    const params = new URLSearchParams(window.location.search);
    const termino = params.get("busqueda");
    if (termino) this.guardar(termino);
  }

  obtener() {
    try {
      const datos = localStorage.getItem(this.clave);
      return datos ? JSON.parse(datos) : [];
    } catch (e) {
      return [];
    }
  }

  guardar(termino) {
    if (!termino || termino.trim() === "") return;
    const anteriores = this.obtener().filter(busqueda => busqueda !== termino);
    const busquedas = [termino, ...anteriores].slice(0, 5);
    localStorage.setItem(this.clave, JSON.stringify(busquedas));
  }

  crearDropdown() {
    this.dropdown = PAW.nuevoElemento("div", "", {
      class: "paw-busquedas-dropdown"
    });
    this.formulario.appendChild(this.dropdown);
  }

  registrarEventos() {
    this.formulario.addEventListener("submit", () => {
      const termino = this.input.value.trim();
      if (termino) this.guardar(termino);
    });

    this.input.addEventListener("focus", () => {
      this.mostrar();
    });

    this.input.addEventListener("blur", () => {
      setTimeout(() => {
        this.ocultar();
      }, 200);
    });
  }

  mostrar() {
    const busquedas = this.obtener();
    if (busquedas.length === 0) return;

    this.dropdown.style.left = this.input.offsetLeft + "px";
    this.dropdown.style.top = (this.input.offsetTop + this.input.offsetHeight + 6) + "px";
    this.dropdown.style.width = this.input.offsetWidth + "px";
    this.dropdown.innerHTML = "";

    this.dropdown.appendChild(
      PAW.nuevoElemento("h3", "Últimas búsquedas", { class: "paw-busquedas-titulo" })
    );

    const lista = PAW.nuevoElemento("ul", "", { class: "paw-busquedas-lista" });
    busquedas.forEach(t => {
      const icono = PAW.nuevoElemento("span", "", { class: "paw-busquedas-icono" });
      const link = PAW.nuevoElemento("a", t, {
        href: "/buscar?busqueda=" + encodeURIComponent(t),
        class: "paw-busquedas-link"
      });
      const item = PAW.nuevoElemento("li", "", { class: "paw-busquedas-item" });
      item.append(icono, link);
      lista.appendChild(item);
    });
    this.dropdown.appendChild(lista);
    this.dropdown.style.display = "block";
  }

  ocultar() {
    this.dropdown.style.display = "none";
  }
}
