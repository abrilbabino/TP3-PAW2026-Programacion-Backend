<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\Autor;
class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;
    private const POR_PAGINA = 6;


    public function catalogo()
    {
        global $request;
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

        $menu = $this->menu;
        $redes = $this->redes;
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        require $this->viewsDir . '/catalogo.view.php';
    }

    private function getFiltros()
    {
        global $request;
        return[
            'genero'    => $request->get('genero'),
            'idioma'    => $request->get('idioma'),
            'autor_id'  => $request->get('autor'),
            'editorial' => $request->get('editorial'),
            'precio_min' => $request->get('precio_min'),
            'precio_max' => $request->get('precio_max'),
            ];
        
    }

    public function detalle()
    {
        global $request;
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $request->get('id');
        $libro = $this->model->get($id);
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        $filtros = [
            'genero'   => $libro->fields['genero'],
            'autor_id' => $libro->fields['autor_id'],
        ];
 
        $relacionados = $this->model->getRelations($filtros);
 
        require $this->viewsDir . '/libro.view.php';
    }

    public function buscar()
    {
        global $request;
        $termino = trim($request->get('busqueda') ?? '');
 
        if (empty($termino)) {
            header('Location: /catalogo');
            return;
        }
 
        $menu  = $this->menu;
        $redes = $this->redes;
        $page  = $request->paginaActual();
 
        $resultado  = $this->model->buscarPaginated($termino, $page, self::POR_PAGINA);
        $libros     = $resultado['items'];
        $pagination = $resultado['pagination'];
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        require $this->viewsDir . '/catalogo.view.php';
    }
}