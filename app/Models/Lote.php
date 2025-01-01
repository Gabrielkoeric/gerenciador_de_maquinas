<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Lote extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lote';
    protected $table = 'lote';

    protected $fillable = ['id_lote', 'numero_lote', 'quantidade_lote', 'quantidade_lote_disponivel', 'ativo', 'id_ingressos'];
}


