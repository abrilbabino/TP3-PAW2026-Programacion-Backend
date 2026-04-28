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
    private const POR_PAGINA = 6;


    public function catalogo()
    {
        $request= $this->request;
        $menu    = $this->menu;
        $redes   = $this->redes;

        $filtros = $this->getFiltros();
        $termino = $request->get('busqueda');
        $page = $request->paginaActual();
        $formato = $request->get('format') ?? 'html'; 

        if ($termino) {
            $resultado = $this->model->buscarPaginated($termino, $page, self::POR_PAGINA);
        } else {
            $resultado = $this->model->getPaginated($filtros, $page, self::POR_PAGINA);
        }

        $libros = $resultado['items'];
        $pagination = $resultado['pagination'];

        if ($formato === 'csv') {
            require $this->viewsDir . '/catalogo_csv.view.php';
            return;
        }
        $filtros = $this-> getFiltros();

        $generoModel = new GeneroCollection;
        $generoModel->setQueryBuilder($this->model->getQueryBuilder());
        $generos = $generoModel->getAll();

        $editorialModel = new EditorialCollection;
        $editorialModel->setQueryBuilder($this->model->getQueryBuilder());
        $editoriales = $editorialModel->getAll();

        $idiomaModel = new IdiomaCollection;
        $idiomaModel->setQueryBuilder($this->model->getQueryBuilder());
        $idiomas = $idiomaModel->getAll();

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        require $this->viewsDir . '/catalogo.view.php';
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
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->get($libro->fields['autor_id']);

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
 
        $resultado  = $this->model->buscarPaginated($termino, $page, self::POR_PAGINA);
        $libros     = $resultado['items'];
        $pagination = $resultado['pagination'];
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        require $this->viewsDir . '/busqueda.view.php';
    }
}