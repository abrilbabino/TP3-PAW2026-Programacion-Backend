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
  <title>Crear Libro - PAWPrints</title>
  <script src="/assets/js/components/paw.js"></script>
  <script src="/assets/js/app.js"></script>
</head>
<body>

<?php require __DIR__ . '/barra-navegacion.view.php'; ?>

<main>
  <header>
    <h1>Agregar Nuevo Libro</h1>
    <p>Completá los datos para cargar el libro en el catálogo.</p>
  </header>

  <section class="Formulario">
    <h2>Datos del libro nuevo</h2>
    <?php if (!empty($errores['general'])): ?>
      <span class="error"><?= htmlspecialchars($errores['general'], ENT_QUOTES, 'UTF-8') ?></span>
    <?php endif; ?>

    <form id="form-nuevo-libro" action="/crear-libro" method="POST" enctype="multipart/form-data" novalidate>

      <fieldset>
        <legend>Información del Libro</legend>

        <label for="titulo">Título</label>
        <input id="titulo" class="form-control <?= isset($errores['titulo']) ? 'input-invalido' : '' ?>" name="titulo" type="text" maxlength="60" required value="<?= htmlspecialchars($request->post()['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
        <?php if (isset($errores['titulo'])): ?>
          <span class="error"><?= htmlspecialchars($errores['titulo'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label for="autor_id">Autor</label>
        <select id="autor_id" class="form-control <?= isset($errores['autor_id']) ? 'input-invalido' : '' ?>" name="autor_id" required>
          <option value="">Seleccione un autor</option>
          <?php foreach ($autores as $autor): ?>
            <option value="<?= htmlspecialchars($autor->fields['id'], ENT_QUOTES, 'UTF-8') ?>" <?= ($request->post()['autor_id'] ?? '') == $autor->fields['id'] ? 'selected' : '' ?> >
              <?= htmlspecialchars($autor->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($errores['autor_id'])): ?>
          <span class="error"><?= htmlspecialchars($errores['autor_id'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label for="genero_id">Género</label>
        <select id="genero_id" class="form-control <?= isset($errores['genero_id']) ? 'input-invalido' : '' ?>" name="genero_id" required>
          <option value="">Seleccione un género</option>
          <?php foreach ($generos as $genero): ?>
            <option value="<?= htmlspecialchars($genero->fields['id'], ENT_QUOTES, 'UTF-8') ?>" <?= ($request->post()['genero_id'] ?? '') == $genero->fields['id'] ? 'selected' : '' ?> >
              <?= htmlspecialchars($genero->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($errores['genero_id'])): ?>
          <span class="error"><?= htmlspecialchars($errores['genero_id'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label for="editorial_id">Editorial</label>
        <select id="editorial_id" class="form-control <?= isset($errores['editorial_id']) ? 'input-invalido' : '' ?>" name="editorial_id" required>
          <option value="">Seleccione una editorial</option>
          <?php foreach ($editoriales as $editorial): ?>
            <option value="<?= htmlspecialchars($editorial->fields['id'], ENT_QUOTES, 'UTF-8') ?>" <?= ($request->post()['editorial_id'] ?? '') == $editorial->fields['id'] ? 'selected' : '' ?> >
              <?= htmlspecialchars($editorial->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($errores['editorial_id'])): ?>
          <span class="error"><?= htmlspecialchars($errores['editorial_id'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label for="idioma_id">Idioma</label>
        <select id="idioma_id" class="form-control <?= isset($errores['idioma_id']) ? 'input-invalido' : '' ?>" name="idioma_id" required>
          <option value="">Seleccione un idioma</option>
          <?php foreach ($idiomas as $idioma): ?>
            <option value="<?= htmlspecialchars($idioma->fields['id'], ENT_QUOTES, 'UTF-8') ?>" <?= ($request->post()['idioma_id'] ?? '') == $idioma->fields['id'] ? 'selected' : '' ?> >
              <?= htmlspecialchars($idioma->fields['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($errores['idioma_id'])): ?>
          <span class="error"><?= htmlspecialchars($errores['idioma_id'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
         <label for="descripcion">Descripción</label>
         
        <textarea id="descripcion" class="form-control <?= isset($errores['descripcion']) ? 'input-invalido' : '' ?>" name="descripcion" maxlength="1000" required><?= htmlspecialchars($request->post()['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <?php if (isset($errores['descripcion'])): ?>
          <span class="error"><?= htmlspecialchars($errores['descripcion'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
      </fieldset>

      <fieldset>
        <legend>Inventario y portada</legend>

        <label for="precio">Precio</label>
        <input id="precio" class="form-control <?= isset($errores['precio']) ? 'input-invalido' : '' ?>" name="precio" type="number" step="0.01" min="0" required value="<?= htmlspecialchars($request->post()['precio'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
        <?php if (isset($errores['precio'])): ?>
          <span class="error"><?= htmlspecialchars($errores['precio'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label for="stock">Stock</label>
        <input id="stock" class="form-control <?= isset($errores['stock']) ? 'input-invalido' : '' ?>" name="stock" type="number" step="1" min="0" required value="<?= htmlspecialchars($request->post()['stock'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
        <?php if (isset($errores['stock'])): ?>
          <span class="error"><?= htmlspecialchars($errores['stock'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>

        <label>Imagen de portada</label>
        <div id="portada-uploader" class="paw-uploader-container"></div>
        <?php if (isset($errores['imagen'])): ?>
          <span class="error"><?= htmlspecialchars($errores['imagen'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
      </fieldset>

      <button type="submit" class="btn-submit btn-primario">Agregar Libro</button>
    </form>
  </section>
</main>

<?php require __DIR__ . '/footer.view.php'; ?>
<?php require __DIR__ . '/iniciar-sesion.view.php'; ?>
<?php require __DIR__ . '/carrito.view.php'; ?>

</body>
</html>
