<?php

ob_start();

$output = fopen('php://output', 'w');

fputs($output, "\xEF\xBB\xBF");
fputcsv($output, ['ID', 'Título', 'Descripción', 'Género', 'Editorial', 'Idioma', 'Precio', 'Autor'], ';', '"', '');

foreach ($libros as $libro) {
    $nombreAutor     = 'Desconocido';
    $nombreGenero    = 'Desconocido';
    $nombreEditorial = 'Desconocido';
    $nombreIdioma    = 'Desconocido';

    foreach ($autores as $a) {
        if ($a->fields['id'] == $libro->fields['autor_id']) {
            $nombreAutor = $a->fields['nombre'];
            break;
        }
    }

    foreach ($generos as $g) {
        if ($g->fields['id'] == $libro->fields['genero_id']) {
            $nombreGenero = $g->fields['nombre'];
            break;
        }
    }

    foreach ($editoriales as $e) {
        if ($e->fields['id'] == $libro->fields['editorial_id']) {
            $nombreEditorial = $e->fields['nombre'];
            break;
        }
    }

    foreach ($idiomas as $i) {
        if ($i->fields['id'] == $libro->fields['idioma_id']) {
            $nombreIdioma = $i->fields['nombre'];
            break;
        }
    }

    fputcsv($output, [
        $libro->fields['id'],
        $libro->fields['titulo'],
        $libro->fields['descripcion'],
        $nombreGenero,
        $nombreEditorial,
        $nombreIdioma,
        $libro->fields['precio'],
        $nombreAutor
    ], ';', '"', '');
}

fclose($output);

$contenidoCsv = ob_get_contents();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="catalogo-libros.csv"');

ob_end_clean();

echo $contenidoCsv;

exit;
?>
