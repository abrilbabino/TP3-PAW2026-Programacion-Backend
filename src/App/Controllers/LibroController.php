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
use Paw\Core\Request;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function catalogo(){
        $request= $this->request;
        $menu    = $this->menu;
        $redes   = $this->redes;

        $filtros = $this->getFiltros();
        $page = $request->paginaActual();

        $resultado = $this->model->getPaginated($filtros, $page);

        $libros = $resultado['items'];
        $pagination = $resultado['pagination'];

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this-> loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class); 

        echo $this->twig->render('catalogo.html.twig', [
            'libros' => $libros,
            'pagination' => $pagination,
            'generos' => $generos,
            'editoriales' => $editoriales,
            'idiomas' => $idiomas,
            'autores' => $autores,
            'query_string' => http_build_query($request->getAll()),
            'app' => ['request' => $request]
        ]);
    }

    public function apiLibros() {
        header('Content-Type: application/json');
        
        $autores = $this->loadCollection(AutorCollection::class); 
        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this->loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        
        $resultado = $this->model->getAll(); 
        
        $librosData = [];
        foreach ($resultado as $libro) {
            $nombreAutor = "Desconocido";
            foreach ($autores as $a) {
                if ($a->fields['id'] == $libro->fields['autor_id']) {
                    $nombreAutor = $a->fields['nombre'];
                    break;
                }
            }
            
            $nombreGenero = "Otros";
            foreach ($generos as $g) {
                if ($g->fields['id'] == $libro->fields['genero_id']) {
                    $nombreGenero = $g->fields['nombre'];
                    break;
                }
            }

            $nombreEditorial = "Otros";
            foreach ($editoriales as $e) {
                if ($e->fields['id'] == $libro->fields['editorial_id']) {
                    $nombreEditorial = $e->fields['nombre'];
                    break;
                }
            }

            $nombreIdioma = "Otros";
            foreach ($idiomas as $i) {
                if ($i->fields['id'] == $libro->fields['idioma_id']) {
                    $nombreIdioma = $i->fields['nombre'];
                    break;
                }
            }

            $librosData[] = [
                'id' => $libro->fields['id'],
                'titulo' => $libro->fields['titulo'],
                'imagen' => $libro->fields['imagen'],
                'autor_id' => $libro->fields['autor_id'],
                'autor_nombre' => $nombreAutor,
                'genero_id' => $libro->fields['genero_id'],
                'genero_nombre' => $nombreGenero,
                'editorial_id' => $libro->fields['editorial_id'],
                'editorial_nombre' => $nombreEditorial,
                'idioma_id' => $libro->fields['idioma_id'],
                'idioma_nombre' => $nombreIdioma,
                'precio' => floatval($libro->fields['precio']),
                'descripcion' => $libro->fields['descripcion'] ?? ''
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => $librosData
        ]);
        exit;
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
 
        $relacionadosRicos = [];
        foreach ($relacionados as $rel) {
            $nombreAutor = 'Desconocido';
            foreach ($autores as $a) {
                if ($a->fields['id'] == $rel->fields['autor_id']) {
                    $nombreAutor = $a->fields['nombre'];
                    break;
                }
            }
            // Creamos un array con el objeto modelo y el nombre del autor agregado artificialmente
            $relacionadosRicos[] = [
                'libro' => $rel,
                'autor_nombre' => $nombreAutor
            ];
        }

        echo $this->twig->render('libro.html.twig', [
            'libro' => $libro,
            'autor' => $autor,
            'relacionados' => $relacionadosRicos,
            'app' => ['request' => $request]
        ]);
    }

    public function create()
    {
        $userSession = Request::session('user');
        if (!$userSession || ($userSession['rol'] ?? '') !== 'staff') {
            header('Location: /catalogo');
            exit;
        }

        $request = $this->request;
        $menu = $this->menu;
        $redes = $this->redes;

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this->loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class);
        $errores = [];

        echo $this->twig->render('crear-libro.html.twig', [
            'generos' => $generos,
            'editoriales' => $editoriales,
            'idiomas' => $idiomas,
            'autores' => $autores,
            'errores' => $errores,
            'app' => ['request' => $request]
        ]);
    }

    public function store()
    {
        $userSession = Request::session('user');
        if (!$userSession || ($userSession['rol'] ?? '') !== 'staff') {
            header('Location: /catalogo');
            exit;
        }

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
            echo $this->twig->render('libro-cargado.html.twig', [
                'libroTitulo' => $libroTitulo,
                'app' => ['request' => $request]
            ]);
            return;
        } catch (InvalidValueFormatException $e) {
            $errores['general'] = $e->getMessage();
        }

        echo $this->twig->render('crear-libro.html.twig', [
            'generos' => $generos,
            'editoriales' => $editoriales,
            'idiomas' => $idiomas,
            'autores' => $autores,
            'errores' => $errores,
            'app' => ['request' => $request]
        ]);
    }

    public function buscar()
    {
        $request = $this->request;
        $termino = trim($request->get('busqueda') ?? '');
 
        $menu  = $this->menu;
        $redes = $this->redes;
 
        $libros = $this->model->buscar($termino);
        $autores = $this->loadCollection(AutorCollection::class);
 
        $librosData = [];
        foreach ($libros as $libro) {
            $nombreAutor = "Desconocido";
            foreach ($autores as $a) {
                if ($a->fields['id'] == $libro->fields['autor_id']) {
                    $nombreAutor = $a->fields['nombre'];
                    break;
                }
            }

            $librosData[] = [
                'id' => $libro->fields['id'],
                'titulo' => $libro->fields['titulo'],
                'imagen' => $libro->fields['imagen'],
                'autor_nombre' => $nombreAutor,
                'precio' => floatval($libro->fields['precio']),
                'descripcion' => $libro->fields['descripcion'] ?? ''
            ];
        }

        $librosJson = json_encode($librosData);
 
        echo $this->twig->render('busqueda.html.twig', [
            'termino' => $termino,
            'librosJson' => $librosJson,
            'libros' => $librosData,
            'app' => ['request' => $request]
        ]);
    }
}