<?php 

namespace Paw\Core;

use Paw\Core\Model;
use Paw\Core\Database\QueryBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    public string $viewsDir;
    protected $menu;
    protected $redes;
    protected $model;
    protected Environment $twig;

    public ?string $modelName = null; 
    
    protected $request;
    protected $log;

    public function __construct($request, $log, $connection)
    {
        $this->request = $request;
        $this->log = $log;
        $this->viewsDir = __DIR__ . "/../App/views";

        // Configuración de Twig 
        $loader = new FilesystemLoader($this->viewsDir);
        
        // Crear directorio de caché si no existe
        $cacheDir = $this->viewsDir . '/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $this->twig = new Environment($loader, [
            'cache' => $cacheDir,
            'auto_reload' => true, // Para desarrollo, recarga automática de plantillas
        ]);

        // Variables Globales de Twig
        $this->twig->addGlobal('session', $_SESSION ?? []);
        
        $this->menu = [
            [
                "href" => "/",
                "name" => "Inicio",
                "icon" => "home",
                "type" => "link",
            ],
            [
                "name" => "Iniciar Sesión",
                "for"   => "mostrar-login",
                "icon"  => "login",
                "type"  => "label"
            ],
            [
                "href" => "/sobreNosotros",
                "name" => "Sobre Nosotros",
                "icon"  => "groups",
                "type"  => "link"
            ],
            [
                "href" => "/catalogo",
                "name" => "Catálogo",
                "icon"  => "menu_book",
                "type"  => "link"
            ],
            [
                "href" => "/reserva",
                "name" => "Reserva",
                "icon"  => "event",
                "type"  => "link"
            ],
            [
                "name" => "Carrito",
                "for"   => "mostrar-carrito",
                "icon"  => "shopping_cart",
                "type"  => "label",
                "li_class" => "item-carrito-texto"
            ],
        ];

        $this->redes = [
            [
                'name' => 'Facebook', 
                'url' => 'https://facebook.com', 
                'img' => 'facebook.png'
            ],
            [
                'name' => 'Twitter', 
                'url' => 'https://twitter.com', 
                'img' => 'twitter.png'
            ],
            [
                'name' => 'Instagram', 
                'url' => 'https://instagram.com', 
                'img' => 'instagram.png'
            ],
        ];

        if (isset($_SESSION['user'])) {
            $this->menu[1] = [
                "name" => "Cerrar sesión",
                "href" => "/logout",
                "icon" => "logout",
                "type" => "link",
            ];

            if (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'staff') {
                array_splice($this->menu, 5, 0, [[
                    "href" => "/pedidos",
                    "name" => "Pedidos",
                    "icon"  => "admin_panel_settings",
                    "type"  => "link"
                ]]);
            }
        }

        // Exponer globales manuales en Twig 
        $this->twig->addGlobal('menu', $this->menu);
        $this->twig->addGlobal('redes', $this->redes);

        if (!is_null($this->modelName)){
            $qb = new QueryBuilder($connection, $log);
            $model = new $this->modelName;
            $model->setQueryBuilder($qb);
            $this->setModel($model);
        }
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }
}