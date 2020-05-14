<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class post extends Model
{
    //
    //protected $table='posts';
   // protected $primaryKey='id';
    protected $fillable = ['title','content'];
    use SoftDeletes;
    public $directory='/images/';
    protected $date=['deleted_at'];

    public function user()
    {
     return $this->belongsTo(User::class);
    }

    public function photos()
    {
       return $this->morphMany(Photo::class,'imageable');
    }
    public function tags ()
    {
        return $this->morphToMany(Tag::class,'taggable');
    }
    //Accessor
    public function  getTitleAttribute($value)
    {
     // return ucfirst($value);
      return strtoupper($value);
    }
    //Mutator
    public function setTitleAttribute($value)
    {
      $this->attributes['title'] = strtoupper($value);
    }

    public function getPathAttribute($value)
    {
        return $this->directory . $value;
    }
}
