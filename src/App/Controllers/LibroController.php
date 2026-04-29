<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\EditorialCollection;
use Paw\App\Models\GeneroCollection;
use Paw\App\Models\IdiomaCollection;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function catalogo()
    {
        $request= $this->request;
        $menu    = $this->menu;
        $redes   = $this->redes;

        $filtros = $this->getFiltros();
        $page = $request->paginaActual();
        $formato = $request->get('format') ?? 'html'; 

        $resultado = $this->model->getPaginated($filtros, $page);

        $libros = $resultado['items'];
        $pagination = $resultado['pagination'];

        if ($formato === 'csv') {
            require $this->viewsDir . '/catalogo_csv.view.php';
            return;
        }

        $generos = $this->loadCollection(GeneroCollection::class);
        $editoriales = $this-> loadCollection(EditorialCollection::class);
        $idiomas = $this->loadCollection(IdiomaCollection::class);
        $autores = $this->loadCollection(AutorCollection::class); 

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
}