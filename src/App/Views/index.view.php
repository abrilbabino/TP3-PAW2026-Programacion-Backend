<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/assets/img/icon.png">
    <link rel="stylesheet" href="/assets/css//style.css" />
    <link rel="stylesheet" href="/assets/css/print.css" media="print" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    <title>PawPrints</title>
  </head>
  <body>
    <?php
      require __DIR__ . '/barra-navegacion.view.php'
    ?>
    <main>
      <header class="Bienvenida">
        <h1>Bienvenido a PAWPrints</h1>
        <p>¡Descubrí un mundo de libros en nuestra tienda física y en línea!</p>
        <a href="catalogo.html">Ver Catálogo</a>
      </header>

      <section class="promociones">
        <h2>Promociones y Novedades</h2>
        <p class="ofertas-noticias">
          ¡Mirá nuestras últimas ofertas y noticias!
        </p>

        <article>
          <img src="/assets/img/nuevos-ingresos.png" alt="" />
          <h3>Nuevos Ingresos</h3>
          <p>Descubrí los últimos títulos agregados a nuestro catálogo.</p>
        </article>

        <article>
          <img src="/assets/img/descuento-mes.png" alt="" />
          <h3>Descuentos del Mes</h3>
          <p>
            Obtené hasta un 50% de descuento en artículos seleccionados este
            mes.
          </p>
        </article>

        <article>
          <img src="/assets/img/eventos.png" alt="" />
          <h3>Eventos</h3>
          <p>¡Sumate a nuestros próximos eventos de firma de libros!</p>
        </article>
      </section>

      <section class="tienda-fisica">
        <h2>Tienda Física</h2>
        <figure>
          <iframe
            src="https://www.google.com/maps/d/embed?mid=1fqEzq6nPa6IITzVkvMWnMvfQsy2sqrQ&ehbc=2E312F&noprof=1"
            width="640"
            height="480"
          ></iframe>
        </figure>

        <address>
          <p><strong>Horarios de Atención:</strong></p>
          <ul>
            <li>Lun-Vie: 9 AM - 9 PM</li>
            <li>Sáb-Dom: 10 AM - 6 PM</li>
          </ul>
        </address>
      </section>
    </main>

    <?php
      require __DIR__ . '/footer.view.php'
    ?>

    <?php
      require __DIR__ . '/iniciar-sesion.view.php'
    ?>

    <?php
      require __DIR__ . '/carrito.view.php'
    ?>
  </body>
</html>
