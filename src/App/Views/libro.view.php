<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="/assets/img/icon.png">
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link rel="stylesheet" href="/assets/css/pawcarousel.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    <title>Libro</title>
    <script src="/assets/js/components/paw.js"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <?php require __DIR__ . '/barra-navegacion.view.php'; ?>
    <main>
        <section class="detalle-libro">
            <figure>
                <img src="/assets/img/<?= htmlspecialchars($libro->fields['imagen'] ?? 'default.png', ENT_QUOTES, 'UTF-8') ?>" alt="Portada <?= htmlspecialchars($libro->fields['titulo'], ENT_QUOTES, 'UTF-8') ?>" />
                <figcaption>
                <h2><?= htmlspecialchars($libro->fields['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
                <span class="stock-mobile">Stock Disponible</span>
                </figcaption>
            </figure>

            <article class="descripcion-libro">
                <h2>Descripción</h2>
                <p><?= htmlspecialchars($libro->fields['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                <h2>Autor</h2>
                <p><?= htmlspecialchars($autor->fields['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
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
            <img src="assets/img/<?= htmlspecialchars($autor->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>.jpg" alt="Fotografía <?= htmlspecialchars($autor->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>"/>
            </figure>

            <article>
                <h3><?= htmlspecialchars($autor->fields['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p><?= htmlspecialchars($autor->fields['biografia'], ENT_QUOTES, 'UTF-8') ?></p>
            </article>
      </section>

        <section>
            <h2>Libros que te podrían gustar</h2>
            <section class="carrusel" data-paw-carousel data-paw-effect="slide">
                <?php if (isset($relacionados)): ?>
                    <?php foreach ($relacionados as $relacionado): ?>
                        <article class="libro-relacionado tarjeta-libro">
                            <a href="/detalle?id=<?= (int)$relacionado->fields['id'] ?>">
                                <figure>
                                    <img src="/assets/img/<?= htmlspecialchars($relacionado->fields['imagen'] ?? 'default.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($relacionado->fields['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                                </figure>
                                <p class="tarjeta-titulo"><?= htmlspecialchars($relacionado->fields['titulo'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="tarjeta-autor">
                                    <?php 
                                        $nombreAutor = "Desconocido";
                                        foreach ($autores as $a) {
                                            if ($a->fields['id'] == $relacionado->fields['autor_id']) {
                                                $nombreAutor = $a->fields['nombre'];
                                                break;
                                            }
                                        }
                                        echo htmlspecialchars($nombreAutor, ENT_QUOTES, 'UTF-8');
                                    ?></p>
                                <p class="tarjeta-precio">$<?= $relacionado->fields['precio'] ?></p>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay libros relacionados en este momento</p>
                <?php endif; ?>
            </section>
            <fieldset class="efectos-selector">
                <legend>Efecto de transición</legend>
                <button class="efecto-btn active" data-efecto="slide">Slide</button>
                <button class="efecto-btn" data-efecto="fade">Fade</button>
                <button class="efecto-btn" data-efecto="zoom">Zoom</button>
            </fieldset>
        </section>
    </main>
    <?php require __DIR__ . '/footer.view.php'; ?>
    <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>

</body>
</html>