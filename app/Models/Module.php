<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function getCoverAttribute($value)
    {
        if(!empty($value)){
            return Storage::url((Cropper::thumb($value, 500, 500)));
        }else{
            return asset('images/book.svg');
        }
    }

    public function getBetaData($value)
    {
        return $this->where('status', 1)->where('course_id', $value)->with(['videos' => function ($query) {
            $query->where('status', 1);
            $query->with('concluded:id,video_id,concluded');
        }])->get(['name', 'id']);
    }
}
