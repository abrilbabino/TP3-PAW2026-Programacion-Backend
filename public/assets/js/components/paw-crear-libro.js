class PAWCrearLibro {
    constructor() {
        this.btnSearch = document.getElementById('btn-openlibrary');
        if (!this.btnSearch) return;

        this.inputSearch = document.getElementById('isbn_search');
        this.statusMsg = document.getElementById('ol-status');
        
        this.inputTitulo = document.getElementById('titulo');
        this.inputDescripcion = document.getElementById('descripcion');
        this.hiddenIsbnCover = document.getElementById('isbn_cover');
        this.formNuevoLibro = document.getElementById('form-nuevo-libro');
        this.confirmPanel = document.getElementById('confirm-panel');
        this.overlay = document.getElementById('overlay-login');

        this.init();
    }

    init() {
        this.registrarEventos();
    }

    selectOrAddOption(selectId, textToMatch) {
        if (!textToMatch) return;
        const select = document.getElementById(selectId);
        if (!select) return;

        let found = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].text.toLowerCase().includes(textToMatch.toLowerCase())) {
                select.selectedIndex = i;
                found = true;
                break;
            }
        }
        if (!found) {
            const newOption = new Option(`${textToMatch} (Nuevo)`, `new_${textToMatch}`);
            select.add(newOption);
            select.value = `new_${textToMatch}`;
        }
    }

    hideConfirmModal() {
        if (this.confirmPanel) this.confirmPanel.classList.remove('is-active');
        if (this.overlay) this.overlay.classList.remove('is-active');
    }

    registrarEventos() {
        const btnCancel1 = document.getElementById('btn-cancel-confirm');
        const btnCancel2 = document.getElementById('btn-cancel-confirm-2');
        const btnAccept = document.getElementById('btn-accept-confirm');

        if (btnCancel1) btnCancel1.addEventListener('click', () => this.hideConfirmModal());
        if (btnCancel2) btnCancel2.addEventListener('click', () => this.hideConfirmModal());

        if (btnAccept) {
            btnAccept.addEventListener('click', () => {
                this.formNuevoLibro.submit();
            });
        }

        if (this.formNuevoLibro) {
            this.formNuevoLibro.addEventListener('submit', (e) => {
                e.preventDefault();

                let hasNewItems = false;
                const campos = ['autor_id', 'genero_id', 'editorial_id', 'idioma_id'];
                campos.forEach(id => {
                    const select = document.getElementById(id);
                    if (select && select.value && select.value.startsWith('new_')) {
                        hasNewItems = true;
                    }
                });

                const warningText = document.getElementById('confirm-warning-text');
                if (warningText) {
                    warningText.style.display = hasNewItems ? 'block' : 'none';
                }
                
                if (this.confirmPanel) this.confirmPanel.classList.add('is-active');
                if (this.overlay) this.overlay.classList.add('is-active');
            });
        }

        this.btnSearch.addEventListener('click', () => this.buscarLibro());
    }

    buscarLibro() {
        const isbn = this.inputSearch.value.trim();
        if (!isbn) {
            this.statusMsg.textContent = "Por favor, ingresá un ISBN válido.";
            this.statusMsg.style.color = "red";
            return;
        }

        this.statusMsg.textContent = "Buscando en Open Library...";
        this.statusMsg.style.color = "var(--color-violeta)";
        this.btnSearch.disabled = true;

        fetch(`https://openlibrary.org/isbn/${isbn}.json`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Libro no encontrado en Open Library.");
                }
                return response.json();
            })
            .then(data => {
                if (data.title) this.inputTitulo.value = data.title;
                
                let descriptionStr = "";
                if (data.description) {
                    if (typeof data.description === 'string') {
                        descriptionStr = data.description;
                    } else if (data.description.value) {
                        descriptionStr = data.description.value;
                    }
                }
                if (descriptionStr && this.inputDescripcion) {
                    this.inputDescripcion.value = descriptionStr.substring(0, 255);
                }

                if (this.hiddenIsbnCover) this.hiddenIsbnCover.value = isbn;

                if (data.publishers && data.publishers.length > 0) {
                    this.selectOrAddOption('editorial_id', data.publishers[0]);
                }

                if (data.languages && data.languages.length > 0) {
                    let langKey = data.languages[0].key || '';
                    let langMap = {'/languages/spa': 'Español', '/languages/eng': 'Inglés'};
                    let langName = langMap[langKey] || langKey.replace('/languages/', '');
                    this.selectOrAddOption('idioma_id', langName);
                }

                const fetchAuthorName = (authorKey) => {
                    if (!authorKey) return;
                    const authOlidInput = document.getElementById('author_olid');
                    if (authOlidInput) authOlidInput.value = authorKey;

                    fetch(`https://openlibrary.org${authorKey}.json`)
                        .then(res => res.json())
                        .then(authorData => {
                            if (authorData.name) {
                                this.selectOrAddOption('autor_id', authorData.name);
                            }
                        })
                        .catch(e => console.log("Error al obtener autor:", e));
                };

                if (data.subjects && data.subjects.length > 0) {
                    this.selectOrAddOption('genero_id', data.subjects[0]);
                }

                let authorFound = false;
                if (data.authors && data.authors.length > 0) {
                    authorFound = true;
                    fetchAuthorName(data.authors[0].key);
                }

                if ((!authorFound || !data.subjects || data.subjects.length === 0) && data.works && data.works.length > 0) {
                    fetch(`https://openlibrary.org${data.works[0].key}.json`)
                        .then(res => res.json())
                        .then(workData => {
                            if ((!data.subjects || data.subjects.length === 0) && workData.subjects && workData.subjects.length > 0) {
                                this.selectOrAddOption('genero_id', workData.subjects[0]);
                            }
                            if (!authorFound && workData.authors && workData.authors.length > 0) {
                                const aKey = workData.authors[0].author ? workData.authors[0].author.key : workData.authors[0].key;
                                fetchAuthorName(aKey);
                            }
                        })
                        .catch(e => console.log("Error al obtener work:", e));
                }

                this.statusMsg.textContent = "¡Libro encontrado y autocompletado!";
                this.statusMsg.style.color = "green";
            })
            .catch(error => {
                console.error("Error OpenLibrary:", error);
                this.statusMsg.textContent = error.message;
                this.statusMsg.style.color = "red";
            })
            .finally(() => {
                this.btnSearch.disabled = false;
            });
    }
}
