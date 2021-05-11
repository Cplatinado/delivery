<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    public function getLastViewAttribute($value)
    {
        if (empty($value)) {
            return 'Não assistido ainda ';
        }

        return date('d/m/Y', strtotime($value));
    }

   
}
