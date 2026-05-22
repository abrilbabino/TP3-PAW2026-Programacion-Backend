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
  <script src="/assets/js/components/paw.js"></script>
  <script src="/assets/js/app.js"></script>
</head>
<body>

  <?php require __DIR__ . '/barra-navegacion.view.php'; ?>

  <main class="catalogo-main">

  <header class="hero-catalogo">
    <h1>Catálogo de Libros</h1>
    <p>Explorá nuestra colección de libros.</p>
  </header>
    <section class="barra-resultados">
        <p class="barra-resultados__contador" data-paw-contador-resultados>Mostrando 0 resultados</p>
        <nav class="barra-resultados__acciones" aria-label="Acciones del catálogo">
          <a href="/crear-libro" class="btn-descargar">
            <span class="material-symbols-outlined">add</span>
            AGREGAR LIBRO
          </a>
          <a href="/catalogo?<?= http_build_query($request->getAll()) ?>&format=csv" class="btn-descargar">            
            <span class="material-symbols-outlined">download</span>
              DESCARGAR CSV
          </a>
        </nav>
  </section>

  <!-- Contenedor del componente de filtros -->
  <div data-paw-filtros data-items-por-pagina="6" data-scroll-infinito="false"></div>

  </main>

  <?php require __DIR__ . '/footer.view.php'; ?>
  <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
  <?php require __DIR__ . '/carrito.view.php'; ?>
</body>
</html>
