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
  <title>Resultados Búsqueda</title>
</head>
<body>
    <?php require __DIR__ . '/barra-navegacion.view.php'; ?>
    <main>
        <header>
            <h1>Resultado de búsqueda</h1>
        </header>
         <?php if(empty($resultado['items'])): ?>
                <p>No se encuentran resultados para: <?= $termino ?></p>
            <?php else: ?>
                <p>Mostrando los resultados para <?= $termino ?></p>
            <?php endif ?>
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
     
        <?php require __DIR__ . '/paginacion.view.php'; ?>        
    </main>
  <?php require __DIR__ . '/footer.view.php'; ?>
  <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
  <?php require __DIR__ . '/carrito.view.php'; ?>
</body>
</html>