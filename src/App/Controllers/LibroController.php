<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\Libro;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\EditorialCollection;
use Paw\App\Models\GeneroCollection;
use Paw\App\Models\IdiomaCollection;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Paw\App\Core\Vista;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function catalogo(){
        $request= $this->request;
        $menu    = $this->menu;
        $redes   = $this->redes;

        $filtros = $this->getFiltros();
        $page = $request->paginaActual();
        $formato = $request->get('format') ?? 'html'; 

        $resultado = $this->model->getPaginated($filtros, $page);

        $libros = $resultado['items'];
        $pagination = $resultado['pagination'];

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this-> loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class); 

        if ($formato === 'csv') {
            require $this->viewsDir . '/catalogo_csv.view.php';
            return;
        }

        require $this->viewsDir . '/catalogo.view.php';
    }

    private function loadCollection($className){
        $model = new $className;
        $model->setQueryBuilder($this->model->getQueryBuilder());
        return $model->getAll();
    }

    private function loadCollectionModel($className){
        $model = new $className;
        $model->setQueryBuilder($this->model->getQueryBuilder());
        return $model;
    }

    private function getFiltros()
    {
        $request = $this->request;
        return[
            'genero_id'    => $request->get('genero'),
            'idioma_id'    => $request->get('idioma'),
            'autor_id'  => $request->get('autor'),
            'editorial_id' => $request->get('editorial'),
            'precio_min' => $request->get('precio_min'),
            'precio_max' => $request->get('precio_max'),
            ];
        
    }

    public function detalle()
    {
        $request = $this->request;
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $request->get('id');
        $libro = $this->model->get($id);
 
        $autorModel = $this->loadCollectionModel(AutorCollection::class); 
        $autor = $autorModel->get($libro->fields['autor_id']);
        $autores = $autorModel->getAll();

        $filtros= [
            'genero_id'    => $libro->fields['genero_id'],
            'autor_id'  => $libro->fields['autor_id'],
        ];
 
        $relacionados = $this->model->getRelations($filtros);
 
        require $this->viewsDir . '/libro.view.php';
    }

    public function create()
    {
        $request = $this->request;
        $menu = $this->menu;
        $redes = $this->redes;

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this->loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class);
        $errores = [];

        require $this->viewsDir . '/crear-libro.view.php';
    }

    public function store()
    {
        $request = $this->request;
        $menu = $this->menu;
        $redes = $this->redes;

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this->loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class);

        $errores = [];

        try {
            $libro = new Libro();
            $libro->setQueryBuilder($this->model->getQueryBuilder());
            $libro->insert($request->post(), $_FILES['imagen'] ?? []);

            $libroTitulo = $request->post()['titulo'] ?? '';
            require $this->viewsDir . '/libro-cargado.view.php';
            return;
        } catch (InvalidValueFormatException $e) {
            $errores['general'] = $e->getMessage();
        }

        require $this->viewsDir . '/crear-libro.view.php';
    }

    public function buscar()
    {
        $request = $this->request;
        $termino = trim($request->get('busqueda') ?? '');
 
        $menu  = $this->menu;
        $redes = $this->redes;
        $page  = $request->paginaActual();
 
        $resultado  = $this->model->buscarPaginated($termino,$page);
        $libros     = $resultado['items'];
        $pagination = $resultado['pagination'];
 
        $autores = $this->loadCollection(AutorCollection::class);
 
        require $this->viewsDir . '/busqueda.view.php';
    }

    public function getAllBooksJSON()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Obtener todos los libros sin paginación
            $libros = $this->model->getAll();
            
            // Obtener colecciones de metadatos
            $autores = $this->loadCollection(AutorCollection::class);
            $generos = $this->loadCollection(GeneroCollection::class);
            $editoriales = $this->loadCollection(EditorialCollection::class);
            $idiomas = $this->loadCollection(IdiomaCollection::class);
            
            // Crear mapeos para búsqueda rápida
            $autoresMap = [];
            $generosMap = [];
            $editorialesMap = [];
            $idiomasMap = [];
            
            foreach ($autores as $a) {
                $autoresMap[$a->fields['id']] = $a->fields['nombre'];
            }
            foreach ($generos as $g) {
                $generosMap[$g->fields['id']] = $g->fields['nombre'];
            }
            foreach ($editoriales as $e) {
                $editorialesMap[$e->fields['id']] = $e->fields['nombre'];
            }
            foreach ($idiomas as $i) {
                $idiomasMap[$i->fields['id']] = $i->fields['nombre'];
            }
            
            // Enriquecer libros con datos de relaciones
            $librosEnriquecidos = [];
            foreach ($libros as $libro) {
                $libroData = $libro->fields;
                $libroData['autor_nombre'] = $autoresMap[$libroData['autor_id']] ?? 'Desconocido';
                $libroData['genero_nombre'] = $generosMap[$libroData['genero_id']] ?? 'Desconocido';
                $libroData['editorial_nombre'] = $editorialesMap[$libroData['editorial_id']] ?? 'Desconocida';
                $libroData['idioma_nombre'] = $idiomasMap[$libroData['idioma_id']] ?? 'Desconocido';
                $librosEnriquecidos[] = $libroData;
            }
            
            echo json_encode([
                'success' => true,
                'data' => $librosEnriquecidos,
                'count' => count($librosEnriquecidos)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}