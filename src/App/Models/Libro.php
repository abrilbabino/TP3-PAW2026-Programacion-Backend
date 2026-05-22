<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Paw\Core\Exceptions\LibroNotFoundException;

class Libro extends Model
{
    public $table = 'libro';

    public $fields = [
        "id" => null,
        "imagen" => null,
        "titulo" => null,
        "descripcion" => null,
        "precio" => null,
        "genero_id" => null,
        "editorial_id" => null,
        "idioma_id" => null,
        "stock" => null,
        "autor_id" => null,
    ];

    public function setId($id){
        if($id < 0 || !is_numeric($id)){
            throw new InvalidValueFormatException("El ID del libro debe ser un entero mayor a 0");
        }
        $this->fields["id"] = $id;
    }

    public function setImagen(string $imagen){
        $this->fields["imagen"] = $imagen ?? 'portada.png';
    }

    public function setTitulo(string $titulo)
    {
        if (strlen($titulo) > 60) {
            throw new InvalidValueFormatException("El titulo no debe ser mayor a 60 caracteres");
        }
        if (strlen($titulo) < 1) {
            throw new InvalidValueFormatException("El titulo es obligatorio");
        }
        $this->fields["titulo"] = $titulo;
    }

    public function setDescripcion(string $descripcion)
    {
        $descripcion = trim($descripcion);
        if (strlen($descripcion) < 1) {
            throw new InvalidValueFormatException("La descripción es obligatoria");
        }
        if (strlen($descripcion) > 1000) {
            throw new InvalidValueFormatException("La descripción no debe superar los 1000 caracteres");
        }
        $this->fields["descripcion"] = $descripcion;
    }

    public function setPrecio(float $precio)
    {
        if ($precio < 0) {
            throw new InvalidValueFormatException("El precio no puede ser negativo");
        }
        $this->fields["precio"] = $precio;
    }

     public function setGenero_Id(int $generoId)
    {
        if ($generoId < 1) {
            throw new InvalidValueFormatException("El ID del género debe ser mayor a 0");
        }
        $this->fields["genero_id"] = $generoId;
    }

     public function setEditorial_Id(int $editorialId)
    {
        if ($editorialId < 1) {
            throw new InvalidValueFormatException("El ID de la editorial debe ser mayor a 0");
        }
        $this->fields["editorial_id"] = $editorialId;
    }

     public function setIdioma_Id(int $idiomaId)
    {
        if ($idiomaId < 1) {
            throw new InvalidValueFormatException("El ID del idioma debe ser mayor a 0");
        }
        $this->fields["idioma_id"] = $idiomaId;
    }

    public function setStock(int $stock)
    {
        if ($stock < 0) {
            throw new InvalidValueFormatException("El stock no puede ser negativo");
        }
        $this->fields["stock"] = $stock;
    }

    public function setAutor_Id(int $autorId)
    {
        if ($autorId < 1 || !is_numeric($autorId)) {
            throw new InvalidValueFormatException("El ID del autor debe ser un entero mayor a 0");
        }
        $this->fields["autor_id"] = $autorId;
    }

    public function set(array $values)
    {
        foreach (array_keys($this->fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($values[$field]);
            }
        }
    }

    public function existeLibro(array $data): bool
    {
        $titulo = trim((string) ($data['titulo'] ?? ''));
        $generoId = isset($data['genero_id']) ? (int) $data['genero_id'] : 0;
        $editorialId = isset($data['editorial_id']) ? (int) $data['editorial_id'] : 0;
        $autorId = isset($data['autor_id']) ? (int) $data['autor_id'] : 0;

        if ($titulo === '' || $generoId < 1 || $editorialId < 1 || $autorId < 1) {
            return false;
        }

        $params = [
            'titulo' => $titulo,
            'genero_id' => $generoId,
            'editorial_id' => $editorialId,
            'autor_id' => $autorId,
        ];

        $resultado = $this->queryBuilder->select($this->table, $params);
        return !empty($resultado);
    }

    public function insert(array $data, array $imageFile = []): int
    {
        if ($this->existeLibro($data)) {
            throw new InvalidValueFormatException('Ya existe un libro con el mismo título, género, autor y editorial');
        }

        $this->handleImageUpload($imageFile);
        $data['imagen'] = $this->fields['imagen'] ?? 'portada.png';
        $this->set($data);

        return $this->queryBuilder->insert($this->table, $this->fields);
    }

    private function handleImageUpload(array $imageFile = []): void
    {
        if (empty($imageFile) || ($imageFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $this->setImagen('portada.png');
            return;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $originalName = basename($imageFile['name'] ?? '');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new InvalidValueFormatException('La imagen debe ser JPG, PNG, WEBP o GIF.');
        }

        $imagenNombre = uniqid('libro_', true) . '.' . $extension;
        $destino = __DIR__ . '/../../../public/assets/img/' . $imagenNombre;

        if (!move_uploaded_file($imageFile['tmp_name'] ?? '', $destino)) {
            throw new InvalidValueFormatException('No se pudo guardar la imagen de portada.');
        }

        $this->setImagen($imagenNombre);
    }

    public function load($id){
        if(!is_numeric($id)||$id < 0){
            throw new InvalidValueFormatException("El ID del libro debe ser un entero mayor a 0");
        }
        $params = ["id" => $id];
        $record = current($this->queryBuilder->select($this->table, $params));
        if ($record) {
            $this->set($record);
        }
        else{
            throw new LibroNotFoundException("No se encontró un libro con el ID proporcionado");
        }
    }
}
