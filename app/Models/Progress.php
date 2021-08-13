<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Progress extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function getLastViewAttribute($value)
    {
        if (empty($value)) {
            return 'Não assistido ainda ';
        }

        return date('d/m/Y', strtotime($value));
    }


}
