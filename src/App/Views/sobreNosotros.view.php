<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/print.css" media="print" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    <title>PawPrints - SobreNosotros</title>
  </head>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

    <main class="sobreNosotros-contenedor">
      <header class="sobreNosotros-encabezado">
        <h1>Sobre PawPrints</h1>
        <p>
          Aprende más sobre nuestra historia, misión y servicios a la comunidad
        </p>
      </header>

      <section class="sobreNosotros-seccion">
        <h2>Nuestra historia</h2>
        <section class="sobreNosotros-contenedor-tarjetas">
          <article class="sobreNosotros-tarjeta">
            <img
              src="/assets/img/nuestroOrigen.png"
              alt="Origen de nuestra librería"
              class="img-cuadrada"
            />
            <section class="sobreNosotros-tarjeta-cuerpo">
              <h3>Nuestros Orígenes</h3>
              <p>Fundada en 2004 empezamos como...</p>
            </section>
          </article>
        </section>
      </section>

      <section class="sobreNosotros-seccion">
        <h2>Nuestra misión</h2>
        <section class="sobreNosotros-contenedor-tarjetas">
          <article class="sobreNosotros-tarjeta">
            <img
              src="/assets/img/nuestraMision.png"
              alt="Nuestra Declaración de Misión"
              class="img-cuadrada"
            />
            <section class="sobreNosotros-tarjeta-cuerpo">
              <h3>Declaración</h3>
              <p>Lo que nos impulsa a servir a nuestra comunidad y ...</p>
            </section>
          </article>
        </section>
      </section>

      <section class="sobreNosotros-seccion">
        <h2>Nuestros servicios</h2>
        <section class="sobreNosotros-contenedor-tarjetas">
          <article class="sobreNosotros-tarjeta">
            <img
              src="/assets/img/serviciosComunitario.png"
              alt="Eventos Comunitarios"
              class="img-cuadrada"
            />
            <section class="sobreNosotros-tarjeta-cuerpo">
              <h3>Servicios Comunitarios</h3>
              <p>Organizamos lecturas de libros semanales...</p>
              <footer class="sobreNosotros-etiquetas">
                <small>Eventos</small>
                <small>Talleres</small>
              </footer>
            </section>
          </article>

          <article class="sobreNosotros-tarjeta">
            <img
              src="/assets/img/recomendacionLibros.png"
              alt="Recomendaciones de Libros"
              class="img-cuadrada"
            />
            <section class="sobreNosotros-tarjeta-cuerpo">
              <h3>Recomendaciones de Libros</h3>
              <p>Nuestro personal ofrece recomendaciones...</p>
              <footer class="sobreNosotros-etiquetas">
                <small>Servicio Personalizado</small>
              </footer>
            </section>
          </article>
        </section>
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
