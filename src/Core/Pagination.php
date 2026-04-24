<?php

namespace Paw\Core;

class Pagination
{
    public int $currentPage;// pagina actual 
    public int $perPage;// registros por pagina
    public int $total;//total de registro de la bd 
    public int $offset;// cuantos registros debemos saltarnos en la consulta sql
    public int $totalPages; // cantidad de pagias que se pudieron formar 

    public function __construct(int $page, int $perPage, int $total)
    {
        // almenos un registro por pagina
        $this->perPage    = max(1, $perPage);
        // total no negativo 
        $this->total      = max(0, $total);
        // cantidad de paginas en total (ej: 25 items / 6 por pág = 4.16 -> 5 páginas)
        $this->totalPages = (int) ceil($this->total / $this->perPage);
        // pag actual entre 1 y el maximo 
        $this->currentPage = max(1, min($page, $this->totalPages ?: 1));
        // calculo de desplazamiento
        //si estoy en la pág 2 y muestro 6 por pág, el offset es (2-1)*6 = 6 (se salta los primeros 6)
        $this->offset     = ($this->currentPage - 1) * $this->perPage;
    }
    //existe una pagina anterior? 
    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }
    // existe pagina siguiente? 
    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }
}