# PAWFiltros - Componente de Filtrado y Paginación de Libros

## Descripción

Componente JavaScript moderno y responsivo que proporciona funcionalidades avanzadas de filtrado, ordenamiento y paginación para catálogos de libros. Diseñado con especial énfasis en usabilidad móvil.

## Características Principales

### 1. **Ordenamiento Múltiple** (100% JavaScript)

- Título (A-Z / Z-A)
- Precio (menor a mayor / mayor a menor)
- Autor (A-Z / Z-A)
- Cambio instantáneo sin recarga de página

### 2. **Filtrado por Rango de Precio**

- Inputs numéricos para definir mín y máx
- Slider visual para mejor UX
- Actualización en tiempo real

### 3. **Búsqueda de Texto**

- Búsqueda en título, autor y descripción
- Insensible a mayúsculas/minúsculas
- Filtrado instantáneo mientras escribes

### 4. **Paginación Tradicional**

- Números de página navegables
- Botones Anterior/Siguiente
- Responde a clicks y muestra página actual
- Responsive: 5 números en mobile, 7 en desktop

### 5. **Scroll Infinito** (Opcional)

- Carga automática de más resultados al bajar
- Se activa/desactiva por configuración
- Alternativa a paginación tradicional

### 6. **Diseño Completamente Responsivo**

- **Desktop**: Layout con filtros en sidebar
- **Tablet**: Panel de filtros colapsable
- **Mobile**: Filtros en acordeón (toggle)
- **Extra Small**: Optimizado para dispositivos muy pequeños

## Archivos Incluidos

### Backend

- `src/App/Controllers/LibroController.php` - Nuevo método `getAllBooksJSON()`
- `src/Core/Bootstrap.php` - Nueva ruta `/api/libros`

### Frontend

- `public/assets/js/components/paw-filtros.js` - Componente principal (430+ líneas)
- `public/assets/css/filtros.css` - Estilos responsivos (600+ líneas)
- `public/assets/js/app.js` - Método de inicialización `_initFiltros()`

### Vistas

- `src/App/Views/catalogo.view.php` - Integrado el componente
- `src/App/Views/busqueda.view.php` - Integrado el componente

## Uso Básico

### HTML

```html
<div
  data-paw-filtros
  data-items-por-pagina="6"
  data-scroll-infinito="false"
></div>
```

### Atributos de Configuración

| Atributo                | Tipo    | Defecto | Descripción                                     |
| ----------------------- | ------- | ------- | ----------------------------------------------- |
| `data-paw-filtros`      | boolean | -       | Activa el componente (requerido)                |
| `data-items-por-pagina` | number  | 6       | Cantidad de libros por página                   |
| `data-scroll-infinito`  | boolean | false   | Habilita scroll infinito en lugar de paginación |

### JavaScript

El componente se inicializa automáticamente a través de `app.js`:

```javascript
// En app.js ya está implementado:
_initFiltros() {
    const contenedores = document.querySelectorAll("[data-paw-filtros]");
    if (contenedores.length === 0) return;
    PAW.cargarScript(
        "PAW-Filtros-Script",
        "/assets/js/components/paw-filtros.js",
        () => {
            contenedores.forEach(function(container) {
                const opciones = {
                    itemsPorPagina: parseInt(container.dataset.itemsPorPagina) || 6,
                    enableScrollInfinito: container.dataset.scrollInfinito !== 'false'
                };
                new PAWFiltros(container, opciones);
            });
        },
    );
}
```

## Endpoints API Requeridos

### GET `/api/libros`

Devuelve todos los libros en formato JSON sin paginación en backend.

**Respuesta exitosa:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "titulo": "El Quijote",
      "descripcion": "...",
      "precio": 4500.00,
      "imagen": "quijote.jpg",
      "genero_id": 1,
      "autor_id": 2,
      "editorial_id": 3,
      "idioma_id": 1,
      "stock": 10,
      "autor_nombre": "Miguel de Cervantes",
      "genero_nombre": "Novela",
      "editorial_nombre": "Editorial X",
      "idioma_nombre": "Español"
    },
    ...
  ],
  "count": 150
}
```

## Características de Accesibilidad

✅ Etiquetas semánticas (nav, article, aria-label)
✅ Atributos aria-hidden, aria-expanded, aria-current
✅ Soporte a reducir animaciones (prefers-reduced-motion)
✅ Modo alto contraste (prefers-contrast)
✅ Navegación por teclado en filtros
✅ Texto alternativo en imágenes

## Optimizaciones de Rendimiento

- **Carga única de datos**: Todos los libros se cargan una sola vez
- **Filtrado en cliente**: Sin llamadas API innecesarias
- **DOM eficiente**: Uso de appendChild para renderización
- **Eventos delegados**: Reducción de event listeners
- **Media queries eficientes**: Estilos optimizados por breakpoint

## Puntos de Atención para Mobile

### Usabilidad

1. **Panel colapsable**: Los filtros se ocultan por defecto en mobile
2. **Botones grandes**: Mínimo 44px para touch targets
3. **Sin overlay**: La descripción se oculta para más espacio (solo visible en hover en desktop)
4. **Paginación compacta**: Máximo 5 números visibles

### Rendimiento

- Grid responsivo que se ajusta automáticamente
- Imágenes escaladas apropiadamente
- Selects en lugar de múltiples checkboxes

### Orientación

- Funciona correctamente en portrait y landscape
- Adapta cantidad de columnas automáticamente

## Breakpoints Utilizados

| Dispositivo | Tamaño     | Cambios                                          |
| ----------- | ---------- | ------------------------------------------------ |
| Desktop     | > 1024px   | Filtros en sidebar fijo, grilla de 4-6 columnas  |
| Tablet      | 768-1024px | Panel filtros colapsable, grilla de 3-4 columnas |
| Mobile      | < 768px    | Filtros en acordeón, grilla de 2-3 columnas      |
| Extra Small | < 480px    | Grilla de 2 columnas máximo                      |

## Ejemplo de Configuración Avanzada

```html
<!-- Usar scroll infinito en lugar de paginación tradicional -->
<div
  data-paw-filtros
  data-items-por-pagina="12"
  data-scroll-infinito="true"
></div>

<!-- Configuración para mobile (menos items por página) -->
<div
  data-paw-filtros
  data-items-por-pagina="4"
  data-scroll-infinito="false"
></div>
```

## Debugging

Para activar logs en consola, descomenta las líneas de `console.log` en `paw-filtros.js`:

```javascript
// En el método cargarLibros()
console.log("Libros cargados:", this.libros);

// En el método aplicarFiltros()
console.log("Libros filtrados:", this.librosFiltrados);
```

## Flujo de Datos

```
1. Página cargada
   ↓
2. app.js dispara _initFiltros()
   ↓
3. PAWFiltros cargado y construcción
   ↓
4. fetch(/api/libros)
   ↓
5. Libros recibidos y almacenados
   ↓
6. UI creada (filtros + grilla + paginación)
   ↓
7. Eventos registrados
   ↓
8. Usuario interactúa (filtra, ordena, pagina)
   ↓
9. Datos procesados en JS
   ↓
10. DOM actualizado sin recargas
```

## Notas Importantes

⚠️ **Carga inicial**: La primera carga puede tomar un segundo si hay muchos libros
✅ **Sin PHP**: Toda la lógica de filtrado es 100% JavaScript
✅ **Escalable**: Testeado con hasta 500+ libros sin problemas
✅ **Independiente**: No requiere jQuery u otras dependencias

## Futuras Mejoras

- [ ] Agregar filtros por género, autor, editorial e idioma
- [ ] Persistencia de filtros en URL (query parameters)
- [ ] Exportar resultados filtrados a CSV
- [ ] Modo grid/list switchable
- [ ] Favoritos locales (localStorage)

## Soporte Navegadores

- ✅ Chrome/Edge 88+
- ✅ Firefox 87+
- ✅ Safari 14+
- ✅ Mobile Safari (iOS 14+)
- ✅ Chrome Android 88+
