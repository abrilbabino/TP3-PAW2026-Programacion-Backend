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
  <script src="/assets/js/components/paw.js"></script>
  <script src="/assets/js/app.js"></script>
</head>
<body>
    <?php require __DIR__ . '/barra-navegacion.view.php'; ?>
    <main class="busqueda">
        <header>
            <h1>Resultado de búsqueda</h1>
        </header>
        <?php if(empty(json_decode($librosJson, true))): ?>
            <p class="resultado-busqueda">No se encuentran resultados para "<strong><?= htmlspecialchars($termino,ENT_QUOTES,'UTF-8') ?></strong>"</p>
        <?php else: ?>
            <p class="resultado-busqueda">Mostrando los resultados para "<strong><?= htmlspecialchars($termino,ENT_QUOTES,'UTF-8') ?></strong>"</p>
        <?php endif; ?>
        <div data-paw-resultados-busqueda='<?= htmlspecialchars($librosJson, ENT_QUOTES, 'UTF-8') ?>' data-items-por-pagina="6">
            <section class="grilla-libros"></section>
            <nav class="paginacion"></nav>
        </div>
    </main>
  <?php require __DIR__ . '/footer.view.php'; ?>
  <?php require __DIR__ . '/iniciar-sesion.view.php'; ?>

</body>
</html>