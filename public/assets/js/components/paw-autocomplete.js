class PAWAutocomplete {
    constructor(inputSelector, endpointUrl) {
        this.input = document.querySelector(inputSelector);
        this.endpointUrl = endpointUrl;
        this.timeoutId = null;
        
        if (!this.input) return;

        // Crea el contenedor para la lista
        this.listContainer = document.createElement('ul');
        this.listContainer.className = 'autocomplete-lista';
        
        // Envuelve el input en un contenedor para poder posicionar la lista relativamente
        this.wrapper = document.createElement('span');
        this.wrapper.className = 'autocomplete-wrapper';
        this.input.parentNode.insertBefore(this.wrapper, this.input);
        this.wrapper.appendChild(this.input);
        this.wrapper.appendChild(this.listContainer);

        this.init();
    }

    init() {
        this.input.addEventListener('input', () => this.handleInput());
        this.input.addEventListener('focus', () => this.handleInput());
        
        // Oculta la lista al hacer clic fuera del componente
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.esconderLista();
            }
        });
    }

    handleInput() {
        const query = this.input.value.trim();
        
        clearTimeout(this.timeoutId);
        
        if (query.length < 2) {
            this.esconderLista();
            return;
        }

        this.timeoutId = setTimeout(() => {
            this.buscarCoincidencias(query);
        }, 300);
    }

    async buscarCoincidencias(query) {
        try {
            const url = `${this.endpointUrl}?q=${encodeURIComponent(query)}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.renderizarLista(data.data);
            } else {
                this.esconderLista();
            }
        } catch (error) {
            console.error('Error al obtener datos de autocompletado:', error);
            this.esconderLista();
        }
    }

    renderizarLista(items) {
        this.listContainer.innerHTML = '';
        
        items.forEach(item => {
            const li = document.createElement('li');
            li.className = 'autocomplete-item';
            li.textContent = item.titulo;
            
            li.addEventListener('click', () => {
                this.input.value = item.titulo;
                this.esconderLista();
            });
            
            this.listContainer.appendChild(li);
        });
        
        this.listContainer.classList.add('visible');
    }

    esconderLista() {
        this.listContainer.classList.remove('visible');
    }
}
