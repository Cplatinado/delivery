<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class highlight extends Model
{
    use HasFactory;


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getCoverAttribute($value)
    {
        if(!empty($value)){
            return Storage::url((Cropper::thumb($value, 1080, 1080)));
//            return Storage::url($value);
        }else{
            return asset('images/book.svg');
        }
    }

}
