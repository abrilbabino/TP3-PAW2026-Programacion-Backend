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
  <title>PawPrints - Reserva</title>
</head>

<body>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

<main>
  <header>
    <h1>Reserva de Libros</h1>
    <p>Solicitá tu reserva a continuación</p>
  </header>

  <section class="Formulario">
    <h2>Formulario de Reserva</h2>

    <form action="/reserva/procesar-reserva" method="POST">

      <fieldset>
        <legend>Datos Personales</legend>

        <label>Nombre</label>
        <input type="text" name="nombre" required />
        <?php if (isset($errores['nombre'])): ?>
            <span class="error-inline"><?= htmlspecialchars($errores['nombre']) ?></span>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" required />
        <?php if (isset($errores['email'])): ?>
            <span class="error-inline"><?= htmlspecialchars($errores['email']) ?></span>
        <?php endif; ?>

        <label>Teléfono</label>
        <input type="tel" name="tel" />
      </fieldset>

      <fieldset>
        <legend>Detalles</legend>

        <label>Cantidad</label>
        <input type="number" name="cantidad" min="1" max="5" required />
        <?php if (isset($errores['cantidad'])): ?>
            <span class="error-inline"><?= htmlspecialchars($errores['cantidad']) ?></span>
        <?php endif; ?>

        <label>Título</label>
        <input type="text" name="libro" required />
        <?php if (isset($errores['libro'])): ?>
            <span class="error-inline"><?= htmlspecialchars($errores['libro']) ?></span>
        <?php endif; ?>
      </fieldset>

      <button type="submit">Reservar</button>
    </form>
  </section>
</main>

<?php require __DIR__ . '/footer.view.php'; ?>
<?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
<?php require __DIR__ . '/carrito.view.php'; ?>

</body>
</html>