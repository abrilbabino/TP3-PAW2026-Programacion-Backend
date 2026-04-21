<?php 

namespace Paw\Core;

use Paw\Core\Model;
use Paw\Core\Database\QueryBuilder;

class Controller
{
    public string $viewsDir;
    protected $menu;
    private $model;


    public ?string $modelName = null; 

    public function __construct()
    {
        global $connection, $log;
        $this -> viewsDir = __DIR__ . "/../App/Views";

        $this -> menu = [
            [
                "href" => "/",
                "name" => "Home",
            ],
            [
                "href" => "/about",
                "name" => "Quienes Somos",
            ],
            [
                "href" => "/catalogo",
                "name" => "Catalogo",
            ],
            [
                "href" => "/reservalibros",
                "name" => "Reserva Libros",
            ],
            [
                "href" => "/carrito",
                "name" => "Carrito",
            ],
        ];
        if (!is_null($this ->modelName)){
            $qb = new QueryBuilder($connection, $log);
            $model = new $this->modelName;
            $model -> setQueryBuilder($qb);
            $this -> setModel($model);
        }
    }

    public function setModel(Model $model)
    {
        $this -> model = $model;
    }
}