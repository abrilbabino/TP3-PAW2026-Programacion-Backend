<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="/assets/img/icon.png">
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link rel="stylesheet" href="/assets/css/print.css" media="print" />
  <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
  <title>Catálogo</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

<main class="catalogo-main">

  <header class="hero-catalogo">
    <h1>Catálogo de Libros</h1>
    <p>Explorá nuestra colección de libros.</p>
  </header>

    <section class= "seccion-filtros">
      <details class="filtros" open>
        <summary>
              <img
                src="assets/img/FILTRAR.png"
                alt="Iconos de filtros"
                class="icono-png"
              />
              FILTRAR
            </summary>

        <form method="GET">

          <p>Género</p>
          <select name="genero">
            <option value="">Todos</option>
            <option value="Terr" <?= ($_GET['genero'] ?? '') == 'Terr' ? 'selected' : '' ?>>Terror</option>
            <option value="Fic" <?= ($_GET['genero'] ?? '') == 'Fic' ? 'selected' : '' ?>>Ficción</option>
            <option value="Rom" <?= ($_GET['genero'] ?? '') == 'Rom' ? 'selected' : '' ?>>Romance</option>
          </select>

          <p>Precio</p>
          <input type="number" name="precio_min" placeholder="Min" value="<?= $_GET['precio_min'] ?? '' ?>">
          <input type="number" name="precio_max" placeholder="Max" value="<?= $_GET['precio_max'] ?? '' ?>">
          <button type="button">IR</button>
          <p>*Min. $1800.00 - Max. $900000.00</p>

          <p>Editorial</p>
          <select name="editorial">
            <option value="">Todas</option>
            <option value="Terr" <?= ($_GET['editorial'] ?? '') == 'DeBol' ? 'selected' : '' ?>>DeBolsillo</option>
            <option value="Fic" <?= ($_GET['editorial'] ?? '') == 'Sur' ? 'selected' : '' ?>>Sur</option>
            <option value="Rom" <?= ($_GET['editorial'] ?? '') == 'Sud' ? 'selected' : '' ?>>Sudamericana</option>
            <option value="Rom" <?= ($_GET['editorial'] ?? '') == 'Alfaguara' ? 'selected' : '' ?>>Alfaguara</option>
            <option value="Rom" <?= ($_GET['editorial'] ?? '') == 'Diana' ? 'selected' : '' ?>>Diana</option>
            <option value="Rom" <?= ($_GET['editorial'] ?? '') == 'OvejaNegra' ? 'selected' : '' ?>>OvejaNegra</option>

          </select>

          <p>Idioma</p>
          <select name="idioma">
            <option value="">Todos</option>
            <option value="es" <?= ($_GET['idioma'] ?? '') == 'es' ? 'selected' : '' ?>>Español</option>
            <option value="en" <?= ($_GET['idioma'] ?? '') == 'en' ? 'selected' : '' ?>>Inglés</option>
            <option value="fr" <?= ($_GET['idioma'] ?? '') == 'fr' ? 'selected' : '' ?>>Francés</option>
          </select>

          <p>Autor</p>
          <select name="autor">
            <option value="">Todos</option>
            <?php foreach ($autores as $a): ?>
              <option value="<?= $a->fields['id'] ?>" <?= ($_GET['autor'] ?? '') == $a ? 'selected' : '' ?>>
                <?= $a->fields['nombre'] ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button type="submit">Filtrar</button>
        </form>
      </details>
    </section>

    <section class="grilla-libros">
      <?php 
      $i=0;
      foreach ($libros as $libro): 
        if($i>=$inicio && $i<$fin):
      ?>
        <article>
          <img src="/assets/img/<?= $libro->fields['imagen'] ?>" alt="Portada">

          <p><strong><?= $libro->fields['titulo'] ?></strong></p>
          <p><em>Autor:</em> <?= $libro->fields['autor_id'] ?></p>
          <p><em>Precio:</em> $<?= $libro->fields['precio'] ?></p>

          <div class="overlay">
            <p><?= $libro->fields['descripcion'] ?></p>
            <a href="/libro?id=<?= $libro->fields['id'] ?>">Ver más</a>
          </div>
          <button class="btn-add-carrito">
            <span class="material-symbols-outlined">add_circle</span>
          </button>
        </article>
      <?php 
        endif;
        $i++;
        endforeach; 
      ?>
    </section>

    <div class="paginacion">
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?= $pagina - 1 ?>" class="Boton">Atrás</a>
    <?php endif; ?>
    
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?pagina=<?= $i ?>" class="<?= $i == $pagina ? 'pagina-activa' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($fin < $totalLibros): ?>
        <a href="?pagina=<?= $pagina + 1 ?>" class="Boton">Siguiente</a>
    <?php endif; ?>
    </div>

</main>

<?php require __DIR__ . '/footer.view.php'; ?>
<?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
<?php require __DIR__ . '/carrito.view.php'; ?>

</body>
</html>