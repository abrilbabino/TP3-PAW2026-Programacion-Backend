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
        if (strlen($descripcion) > 255) {
            throw new InvalidValueFormatException("La descripción no debe superar los 255 caracteres");
        }
        $this->fields["descripcion"] = $descripcion;
    }

    public function setPrecio($precio)
    {
        if (!is_numeric($precio) || $precio < 0) {
            throw new InvalidValueFormatException("El precio no puede ser negativo y debe ser un número válido");
        }
        $this->fields["precio"] = (float)$precio;
    }

     public function setGenero_Id($generoId)
    {
        if (!is_numeric($generoId) || $generoId < 1) {
            throw new InvalidValueFormatException("El ID del género debe ser mayor a 0");
        }
        $this->fields["genero_id"] = (int)$generoId;
    }

     public function setEditorial_Id($editorialId)
    {
        if (!is_numeric($editorialId) || $editorialId < 1) {
            throw new InvalidValueFormatException("El ID de la editorial debe ser mayor a 0");
        }
        $this->fields["editorial_id"] = (int)$editorialId;
    }

     public function setIdioma_Id($idiomaId)
    {
        if (!is_numeric($idiomaId) || $idiomaId < 1) {
            throw new InvalidValueFormatException("El ID del idioma debe ser mayor a 0");
        }
        $this->fields["idioma_id"] = (int)$idiomaId;
    }

    public function setStock($stock)
    {
        if (!is_numeric($stock) || $stock < 0) {
            throw new InvalidValueFormatException("El stock no puede ser negativo y debe ser un número");
        }
        $this->fields["stock"] = (int)$stock;
    }

    public function setAutor_Id($autorId)
    {
        if (!is_numeric($autorId) || $autorId < 1) {
            throw new InvalidValueFormatException("El ID del autor debe ser un entero mayor a 0");
        }
        $this->fields["autor_id"] = (int)$autorId;
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

    public function obtenerIdRelacion($modeloEntidad, string $nuevoNombre, ?string $authorOlid = null): int
    {
        // Verificamos si ya existe en la base de datos para evitar duplicados
        $existente = $modeloEntidad->findBy(['nombre' => $nuevoNombre]);
        
        if (!empty($existente)) {
            return $existente[0]['id'];
        }

        // Usamos el Modelo para validar y guardar
        $modeloEntidad->set(['nombre' => $nuevoNombre]);
        $nuevoId = $modeloEntidad->save();

        // Si es un autor nuevo y tenemos su OLID, descargamos su foto
        if ($modeloEntidad instanceof \Paw\App\Models\Autor && !empty($authorOlid)) {
            $olid = str_replace('/authors/', '', $authorOlid);
            $imageUrl = "https://covers.openlibrary.org/a/olid/{$olid}-L.jpg";
            $imageData = @file_get_contents($imageUrl);
            // Verificamos que sea una imagen válida (>100 bytes para evitar el gif transparente 1x1 por defecto)
            if ($imageData && strlen($imageData) > 100) {
                $imagePath = __DIR__ . '/../../../public/assets/img/autor_' . $nuevoNombre . '.jpg';
                file_put_contents($imagePath, $imageData);
            }
        }

        return $nuevoId;
    }

    public function insert(array $data, array $imageFile = []): int
    {
        if ($this->existeLibro($data)) {
            throw new InvalidValueFormatException('Ya existe un libro con el mismo título, género, autor y editorial');
        }

        $this->handleImageUpload($data, $imageFile);
        $data['imagen'] = $this->fields['imagen'] ?? 'portada.png';
        $this->set($data);

        return $this->queryBuilder->insert($this->table, $this->fields);
    }

    private function handleImageUpload(array $data, array $imageFile = []): void
    {
        if (empty($imageFile) || ($imageFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            // Intentar recuperar portada de Open Library si se proporcionó un ISBN
            if (!empty($data['isbn_cover'])) {
                $isbn = preg_replace('/[^0-9X]/i', '', $data['isbn_cover']);
                if (!empty($isbn)) {
                    $url = "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg";
                    // Suprimir warnings en caso de que falle la descarga
                    $imgContent = @file_get_contents($url);
                    // OpenLibrary devuelve una imagen 1x1 GIF (aprox 43 bytes) si no la encuentra. Filtramos por > 1024 bytes.
                    if ($imgContent !== false && strlen($imgContent) > 1024) {
                        $imagenNombre = uniqid('libro_ol_', true) . '.jpg';
                        $destino = __DIR__ . '/../../../public/assets/img/' . $imagenNombre;
                        if (file_put_contents($destino, $imgContent) !== false) {
                            $this->setImagen($imagenNombre);
                            return;
                        }
                    }
                }
            }

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
