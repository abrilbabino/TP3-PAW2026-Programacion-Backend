<input type="checkbox" id="mostrar-carrito" class="carrito-check" />
    <label for="mostrar-carrito" class="fondo-carrito"></label>

    <aside class="carrito-panel">
      <header class="carrito-header">
        <h2>Carrito de compras</h2>
        <label for="mostrar-carrito" class="carrito-cerrar">✕</label>
      </header>

      <?php if (empty($productos_carrito)) : ?>
          <p class="carrito-vacio">
            <span class="material-symbols-outlined simbolos">info</span>
            El carrito de compras está vacío.
          </p>
      <?php else : ?>
          <ul class="lista-productos">
          </ul>
      <?php endif; ?>
    </aside>