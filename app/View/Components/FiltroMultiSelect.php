<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FiltroMultiSelect extends Component
{
    public $titulo;
    public $name;
    public $items;
    public $idField;
    public $labelField;
    public $selecionados;
    public $listaId;

    public function __construct(
        $titulo,
        $name,
        $items,
        $idField,
        $labelField,
        $selecionados = [],
        $listaId
    ) {
        $this->titulo = $titulo;
        $this->name = $name;
        $this->items = $items;
        $this->idField = $idField;
        $this->labelField = $labelField;
        $this->selecionados = $selecionados;
        $this->listaId = $listaId;
    }

    public function render()
    {
        return view('components.filtro-multi-select');
    }
}
