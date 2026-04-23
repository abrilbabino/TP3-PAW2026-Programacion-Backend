<?php

namespace Paw\Core;

class Request{
	public function uri()
	{
		$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		if (str_contains($path, 'index.php')) {
        	$path = '/';
    	}
		return $path;
	}

	public function method()
	{
		return $_SERVER["REQUEST_METHOD"];
	}

	public function route()
	{
		return [
			$this->uri(),
			$this->method()
		];
	}
	public function get($key)
	{
		return $_POST[$key] ?? $_GET[$key] ?? null;
	}

	public function post(){
		return $_POST;
	}

	public function paginaActual(){
		return $_GET['pagina'] ?? 1;
	}

}
