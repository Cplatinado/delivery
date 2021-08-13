<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use phpDocumentor\Reflection\Types\This;
use \Illuminate\Database\Eloquent\SoftDeletes;


class Video extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
        'user_id',
        '',


    ];
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

    public function getConcluded($value)
    {
        return $this->with('concluded:id,video_id,concluded')->where('id', $value)
            ->first(['link', 'id', 'player','status' ,'course_id',]);
    }

    public function setLinkAttribute($value)
    {
        if($this->attributes['player'] == 1){
            $this->attributes['link']  =  $value;

        }else{
            $this->attributes['link']  = 'https://player.vimeo.com/video/' . substr($value,18);
        }
    }



}
