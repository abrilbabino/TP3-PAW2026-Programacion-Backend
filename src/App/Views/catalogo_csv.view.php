<?php

use \Paw\App\Models\Autor;

ob_start();

$output = fopen('php://output', 'w');

fputs($output, "\xEF\xBB\xBF");
fputcsv($output, ['ID', 'Título', 'Descripción', 'Género', 'Editorial', 'Idioma', 'Precio', 'Autor'], ',', '"', '\\');

$autorModel = new Autor();
$autorModel->setQueryBuilder($this->model->getQueryBuilder());

foreach ($libros as $libro) {
    $autorModel->load($libro->fields['autor_id']);
    $nombreAutor = $autorModel->fields['nombre'] ?? 'Desconocido';

    fputcsv($output, [
        $libro->fields['id'],
        $libro->fields['titulo'],
        $libro->fields['descripcion'],
        $libro->fields['genero'],
        $libro->fields['editorial'],
        $libro->fields['idioma'],
        $libro->fields['precio'],
        $nombreAutor
    ], ',', '"', '\\');
}

fclose($output);

$contenidoCsv = ob_get_clean(); 

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=catalogo-libros.csv');

echo $contenidoCsv;
exit; 
?>