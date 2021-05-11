<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use phpDocumentor\Reflection\Types\This;

class Video extends Model
{
    use HasFactory;
    public function getCoverAttribute($value)
    {
        if(!empty($value)){
            return Storage::url((Cropper::thumb($value, 500, 500)));
        }else{
            return asset('images/book.svg');
        }
    }
    public function concluded()
    {
        return $this->hasOne(Progress::class);
    }

    public function answer()
    {
        return $this->hasMany(Comment::class);
    }

    public function setLinkAttribute($value)
    {
        if($this->attributes['player'] == 1){
            $this->attributes['link']  = substr($value, 0, 24) . 'embed/' . substr($value,32 );

        }else{
            $this->attributes['link']  = 'https://player.vimeo.com/video/' . substr($value,18);
        }
    }

  

}
