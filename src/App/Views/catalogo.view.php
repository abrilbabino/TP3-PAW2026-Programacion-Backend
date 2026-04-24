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
</head>
<body>

  <?php require __DIR__ . '/barra-navegacion.view.php'; ?>

  <main class="catalogo-main">

  <header class="hero-catalogo">
    <h1>Catálogo de Libros</h1>
    <p>Explorá nuestra colección de libros.</p>
  </header>
    <section class="barra-resultados">
        <p>Mostrando <strong><?= count($libros) ?></strong> resultados</p>
        <a href="/catalogo?<?= http_build_query($request->getAll()) ?>&format=csv" class="btn-descargar">            
          <span class="material-symbols-outlined">download</span>
            DESCARGAR CSV
        </a>
  </section>
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

          <form method="GET" action="/catalogo">

            <p>Género</p>
            <select name="genero">
              <option value="">Todos</option>
              <?php foreach ($generos as $g): ?>
                <option value="<?= $g->fields['id'] ?>" <?= ($request->get('genero') == $g->fields['id']) ? 'selected' : '' ?>>
                <?= $g->fields['nombre'] ?>
                </option>
              <?php endforeach; ?>
            </select>

            <p>Precio</p>
            <input type="number" name="precio_min" placeholder="Min" value="<?= $request->get('precio_min') ?? '' ?>">
            <input type="number" name="precio_max" placeholder="Max" value="<?= $request->get('precio_max') ?? '' ?>">
            <p>*Min. $1800.00 - Max. $900000.00</p>

            <p>Editorial</p>
            <select name="editorial">
              <option value="">Todas</option>
              <?php foreach ($editoriales as $e): ?>
                <option value="<?= $e->fields['id'] ?>" <?= ($request->get('editorial') == $e->fields['id']) ? 'selected' : '' ?>>
                <?= $e->fields['nombre'] ?>
                </option>
              <?php endforeach; ?>

            </select>

            <p>Idioma</p>
            <select name="idioma">
              <option value="">Todos</option>
              <?php foreach ($idiomas as $i): ?>
                <option value="<?= $i->fields['id'] ?>" <?= ($request->get('idioma') == $i->fields['id']) ? 'selected' : '' ?>>
                <?= $i->fields['nombre'] ?>
                </option>
              <?php endforeach; ?>

            </select>

            <p>Autor</p>
            <select name="autor">
              <option value="">Todos</option>
              <?php foreach ($autores as $a): ?>
                <option value="<?= $a->fields['id'] ?>" <?= ($request->get('autor') ?? '') == $a ? 'selected' : '' ?>>
                  <?= $a->fields['nombre'] ?>
                </option>
              <?php endforeach; ?>
            </select>
            <form method="GET" action="/catalogo">
              <button type="submit">Filtrar</button>
            </form>
          </form>
        </details>
      </section>

      <section class="grilla-libros">
        <?php 
        foreach ($libros as $libro): 
        ?>
          <article>
            <img src="/assets/img/<?= $libro->fields['imagen'] ?>" alt="<?= $libro->fields['titulo'] ?>">

            <p><strong><?= $libro->fields['titulo'] ?></strong></p>
            <p><em>Autor:</em> 
            <?php 
              $nombreAutor = "Desconocido";
              foreach ($autores as $a) {
                  if ($a->fields['id'] == $libro->fields['autor_id']) {
                      $nombreAutor = $a->fields['nombre'];
                      break;
                  }
              }
              echo $nombreAutor;
            ?>
            </p>
            <p><em>Precio:</em> $<?= $libro->fields['precio'] ?></p>

            <div class="overlay">
              <p><?= $libro->fields['descripcion'] ?></p>
              <a href="/detalle?id=<?= $libro->fields['id'] ?>">Ver más</a>
            </div>
            <form class="boton-agregarCarrito" action="/agregarCarrito" method="POST">
              <button type="submit" class="btn-add-carrito">
                <span class="material-symbols-outlined">add_circle</span>
              </button>
            </form>
          </article>
        <?php endforeach; ?>
      </section>

      <div class="paginacion">
 
        <?php if ($pagination->hasPrev()): ?>
          <a href="?<?= http_build_query(array_merge($request->getAll(), ['pagina' => $pagination->currentPage - 1])) ?>" class="Boton">Atrás</a>
        <?php endif; ?>
 
        <?php for ($i = 1; $i <= $pagination->totalPages; $i++): ?>
          <a href="?<?= http_build_query(array_merge($request->getAll(), ['pagina' => $i])) ?>"
             class="<?= $i === $pagination->currentPage ? 'pagina-activa' : '' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
 
        <?php if ($pagination->hasNext()): ?>
          <a href="?<?= http_build_query(array_merge($request->getAll(), ['pagina' => $pagination->currentPage + 1])) ?>" class="Boton">Siguiente</a>
        <?php endif; ?>
 
      </div>

  </main>

  <?php require __DIR__ . '/footer.view.php'; ?>
  <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
  <?php require __DIR__ . '/carrito.view.php'; ?>
</body>
</html>