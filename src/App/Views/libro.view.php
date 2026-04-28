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
    <title>Libro</title>
</head>
<body>
    <?php require __DIR__ . '/barra-navegacion.view.php'; ?>
    <main>
        <section class="detalle-libro">
            <figure>
                <img src="/assets/img/<?= $libro->fields['imagen'] ?? 'default.png' ?>" alt="Portada <?= $libro->fields['titulo'] ?>" />
                <figcaption>
                <h2><?= $libro->fields['titulo'] ?></h2>
                <span class="stock-mobile">Stock Disponible</span>
                </figcaption>
            </figure>

            <article class="descripcion-libro">
                <h2>Descripción</h2>
                <p><?= $libro->fields['descripcion'] ?></p>
                <h2>Autor</h2>
                <p><?= $autor->fields['nombre'] ?></p>
            </article>

            <section class="compra-reserva">
                <p><strong>$<?= $libro->fields['precio'] ?></strong></p>
                <span class="stock-desktop">Stock Disponible</span>
                <form class="boton-agregarCarrito" action="/agregarCarrito" method="POST">
                <button type="submit">Agregar al Carrito</button>
                </form>
                <form class="boton-reservar" action="/reserva" method="GET">
                    <button type="submit">Reservar Gratis</button>
                </form>
            </section>
        </section>

        <section class="descripcion-autor">
            <figure>
            <img src="assets/img/<?=$autor->fields['nombre']?>.jpg" alt="Fotografía <?= $autor->fields['nombre'] ?>"/>
            </figure>

            <article>
                <h3>Descripción del Autor</h3>
                <p><?= $autor->fields['biografia']?></p>
            </article>
      </section>

        <section>
            <h2>Libros que te podrían gustar</h2>
            <section class="carrusel">
                <?php if (isset($relacionados)): ?>
                    <?php foreach ($relacionados as $relacionado): ?>
                        <article class="libro-relacionado">
                            <a href="/detalle?id=<?= $relacionado->fields['id'] ?>">
                                <figure>
                                    <img src="/assets/img/<?= $relacionado->fields['imagen'] ?? 'default.png' ?>" alt="<?= $relacionado->fields['titulo'] ?>">
                                </figure>
                                <p class="titulo"><?= $relacionado->fields['titulo'] ?></p>
                                <p class="autor">
                                    <?php 
                                        $nombreAutor = "Desconocido";
                                        foreach ($autores as $a) {
                                            if ($a->fields['id'] == $relacionado->fields['autor_id']) {
                                                $nombreAutor = $a->fields['nombre'];
                                                break;
                                            }
                                        }
                                        echo $nombreAutor;
                                    ?></p>
                                <p class="precio">$<?= $relacionado->fields['precio'] ?></p>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay libros relacionados en este momento</p>
                <?php endif; ?>
            </section>
        </section>
    </main>
    <?php require __DIR__ . '/footer.view.php'; ?>
    <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
    <?php require __DIR__ . '/carrito.view.php'; ?>
</body>
</html>