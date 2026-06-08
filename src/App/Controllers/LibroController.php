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

    public function apiBuscar() {
        header('Content-Type: application/json');
        
        $request = $this->request;
        $termino = trim($request->get('q') ?? '');
        
        if (empty($termino)) {
            echo json_encode(['success' => true, 'data' => []]);
            exit;
        }

        $resultado = $this->model->buscarPorTituloPaginated($termino, 1, 5);
        $librosData = [];
        foreach ($resultado['items'] as $libro) {
            $librosData[] = [
                'id' => $libro->fields['id'],
                'titulo' => $libro->fields['titulo']
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

    private function loadModel($className){
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
 
        $autorModel = $this->loadModel(AutorCollection::class); 
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
            $postData = $request->post();
            


            // Lógica para autocompletar dinámicamente: Si viene "new_Nombre", creamos el registro
            $clasesModelos = [
                'autor_id' => \Paw\App\Models\Autor::class,
                'genero_id' => \Paw\App\Models\Genero::class,
                'editorial_id' => \Paw\App\Models\Editorial::class,
                'idioma_id' => \Paw\App\Models\Idioma::class
            ];

            $libroMain = new Libro();
            $libroMain->setQueryBuilder($this->model->getQueryBuilder());

            foreach ($clasesModelos as $campo => $claseModelo) {
                if (isset($postData[$campo]) && strpos($postData[$campo], 'new_') === 0) {
                    $nuevoNombre = substr($postData[$campo], 4);
                    
                    $modeloEntidad = $this->loadModel($claseModelo);
                    
                    $nuevoId = $libroMain->obtenerIdRelacion($modeloEntidad, $nuevoNombre, $postData['author_olid'] ?? null);
                    $postData[$campo] = (string)$nuevoId;
                }
            }

            $libroMain->insert($postData, $request->file('imagen') ?? []);

            $libroTitulo = $request->post()['titulo'] ?? '';
            echo $this->twig->render('libro-cargado.html.twig', [
                'libroTitulo' => $libroTitulo,
                'app' => ['request' => $request]
            ]);
            return;
        } catch (InvalidValueFormatException $e) {
            $msg = $e->getMessage();
            $msgLower = strtolower($msg);
            
            if (strpos($msgLower, 'título') !== false || strpos($msgLower, 'titulo') !== false) {
                if (strpos($msgLower, 'ya existe un libro') !== false) {
                    $errores['general'] = $msg;
                } else {
                    $errores['titulo'] = $msg;
                }
            } elseif (strpos($msgLower, 'descripción') !== false || strpos($msgLower, 'descrip') !== false) {
                $errores['descripcion'] = $msg;
            } elseif (strpos($msgLower, 'precio') !== false) {
                $errores['precio'] = $msg;
            } elseif (strpos($msgLower, 'género') !== false || strpos($msgLower, 'genero') !== false) {
                $errores['genero_id'] = $msg;
            } elseif (strpos($msgLower, 'editorial') !== false) {
                $errores['editorial_id'] = $msg;
            } elseif (strpos($msgLower, 'idioma') !== false) {
                $errores['idioma_id'] = $msg;
            } elseif (strpos($msgLower, 'stock') !== false) {
                $errores['stock'] = $msg;
            } elseif (strpos($msgLower, 'autor') !== false) {
                $errores['autor_id'] = $msg;
            } elseif (strpos($msgLower, 'imagen') !== false) {
                $errores['imagen'] = $msg;
            } else {
                $errores['general'] = $msg;
            }
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