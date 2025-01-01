<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ingressos extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ingressos';
    protected $fillable = ['id_ingressos', 'nome', 'descricao', 'quantidade', 'quantidade_disponivel', 'valor'];
}


