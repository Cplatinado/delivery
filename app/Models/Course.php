<?php

namespace App\Models;

use App\suport\Cropper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
            return $this->hasMany(Favorite::class);
    }

    public function commentsList($value)
    {
        return Video::with(['answer'=> function($query){
            $query->with('user:id,username');
        }])->where('course_id', $value)->get();
    }

    public function contentCourse($course)
    {
        return $this->with(['modules' => function($query){
            $query->select('id', 'name', 'course_id')
            ->with('videos:id,name,status,module_id');
        }])->where('id', $course)->first();
    }

    public function getInfo($value)
    {
        return $this->with('favorites:course_id,id,favorite')->where('id', $value)->first(['name', 'id', 'user_id', 'headline', 'desc', 'cover']);
    }

    public function betaContentCourse($course)
    {
        return $this->with(['modules' => function ($query) {
            $query->where('status', 1);
            $query->with(['videos' => function ($query) {
                $query->where('status', 1);
                $query->with('concluded');
            }]);
        }])->where('id', $course)->first();

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
            return Storage::url((Cropper::thumb($value, 1080, 1920)));
        }else{
            return asset('images/book.svg');
        }
    }

    public function cover1()
    {
        Storage::url((Cropper::thumb($this->attributes['cover'], 1080, 1600)));
    }
}
