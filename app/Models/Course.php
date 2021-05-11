<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function SetNameAttribute($value)
    {

        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $value;

        $this->attributes['slug'] = Str::slug($value);

    }

    public function getCoverAttribute($value)
    {
        if(!empty($value)){
            return Storage::url((Cropper::thumb($value, 500, 500)));
        }else{
            return asset('images/book.svg');
        }
    }
}
