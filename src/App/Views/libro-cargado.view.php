<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="/assets/img/icon.png">
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,1,0"
    />
  <title>PawPrints - Libro agregado</title>
  <script src="/assets/js/components/paw.js"></script>
  <script src="/assets/js/app.js"></script>
</head>

<body>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

<main>
    <section class="mensaje-exito">
        <span class="material-symbols-outlined icono-exito">
            check_circle
        </span>
        <h1>¡Libro agregado exitosamente!</h1>
        <?php if (!empty($libroTitulo)): ?>
          <p>El libro "<?= htmlspecialchars($libroTitulo, ENT_QUOTES, 'UTF-8') ?>" se cargó correctamente en el catálogo.</p>
        <?php else: ?>
          <p>El libro se cargó correctamente en el catálogo.</p>
        <?php endif; ?>
        <a href="/catalogo" class="btn-volver">VOLVER AL CATÁLOGO</a>
    </section>
</main>

<?php require __DIR__ . '/footer.view.php'; ?>
<?php require __DIR__ . '/iniciar-sesion.view.php'; ?>


</body>
</html>
