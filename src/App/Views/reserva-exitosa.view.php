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
  <title>PawPrints - Reserva Exitosa</title>
</head>

<body>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

<main>
    <section class="mensaje-exito">
        <span class="material-symbols-outlined icono-exito">
            check_circle
        </span>
        <h1>¡Reserva confirmada!</h1>
        <p>Hemos procesado tu solicitud correctamente.<br> El personal de la librería ha sido notificado por correo electrónico y se pondrá en contacto con usted a la brevedad.</p>
        
        <a href="/catalogo" class="btn-volver">VOLVER AL CATÁLOGO</a>
    </section>
</main>

<?php require __DIR__ . '/footer.view.php'; ?>
<?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
<?php require __DIR__ . '/carrito.view.php'; ?>

</body>
</html>