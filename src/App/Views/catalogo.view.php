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
        <a href="/catalogo/csv?<?= http_build_query($_GET) ?>" class="btn-descargar">            
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
              <option value="Terror" <?= ($request->get('genero') ?? '') == 'Terror' ? 'selected' : '' ?>>Terror</option>
              <option value="Ficción" <?= ($request->get('genero') ?? '') == 'Ficción' ? 'selected' : '' ?>>Ficción</option>
              <option value="Fantástico" <?= ($request->get('genero') ?? '') == 'Fantástico' ? 'selected' : '' ?>>Fantástico</option>
              <option value="Romance" <?= ($request->get('genero') ?? '') == 'Romance' ? 'selected' : '' ?>>Romance</option>
              <option value="Novela" <?= ($request->get('genero') ?? '') == 'Novela' ? 'selected' : '' ?>>Novela</option>
              <option value="Realismo Mágico" <?= ($request->get('genero') ?? '') == 'Realismo Mágico' ? 'selected' : '' ?>>Realismo Mágico</option>

            </select>

            <p>Precio</p>
            <input type="number" name="precio_min" placeholder="Min" value="<?= $request->get('precio_min') ?? '' ?>">
            <input type="number" name="precio_max" placeholder="Max" value="<?= $request->get('precio_max') ?? '' ?>">
            <p>*Min. $1800.00 - Max. $900000.00</p>

            <p>Editorial</p>
            <select name="editorial">
              <option value="">Todas</option>
              <option value="DeBolsillo" <?= ($request->get('editorial') ?? '') == 'DeBolsillo' ? 'selected' : '' ?>>DeBolsillo</option>
              <option value="Sur" <?= ($request->get('editorial') ?? '') == 'Sur' ? 'selected' : '' ?>>Sur</option>
              <option value="Sudamericana" <?= ($request->get('editorial') ?? '') == 'Sudamericana' ? 'selected' : '' ?>>Sudamericana</option>
              <option value="Alfaguara" <?= ($request->get('editorial') ?? '') == 'Alfaguara' ? 'selected' : '' ?>>Alfaguara</option>
              <option value="Diana" <?= ($request->get('editorial') ?? '') == 'Diana' ? 'selected' : '' ?>>Diana</option>
              <option value="Oveja Negra" <?= ($request->get('editorial') ?? '') == 'Oveja Negra' ? 'selected' : '' ?>>Oveja Negra</option>

            </select>

            <p>Idioma</p>
            <select name="idioma">
              <option value="">Todos</option>
              <option value="Español" <?= ($request->get('idioma') ?? '') == 'Español' ? 'selected' : '' ?>>Español</option>
              <option value="Inglés" <?= ($request->get('idioma') ?? '') == 'Inglés' ? 'selected' : '' ?>>Inglés</option>
              <option value="Francés" <?= ($request->get('idioma') ?? '') == 'Francés' ? 'selected' : '' ?>>Francés</option>
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
        $i=0;
        foreach ($libros as $libro): 
          if($i>=$inicio && $i<$fin):
        ?>
          <article>
            <img src="/assets/img/<?= $libro->fields['imagen'] ?>" alt="<?= $libro->fields['titulo'] ?>">

            <p><strong><?= $libro->fields['titulo'] ?></strong></p>
            <p><em>Autor:</em> 
            <?php 
              $nombreAutor = "Desconocido";
              $autor = $autorModel->get($libro->fields['autor_id']);
              if ($autor) {
                  $nombreAutor = $autor->fields['nombre'];
              }
              echo $nombreAutor;
            ?>
            </p>
            <p><em>Precio:</em> $<?= $libro->fields['precio'] ?></p>

            <div class="overlay">
              <p><?= $libro->fields['descripcion'] ?></p>
              <a href="/detalle?id=<?= $libro->fields['id'] ?>">Ver más</a>
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