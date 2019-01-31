<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
    	'title'
    ];

    public $timestamps = false;

    public function videos()
    {
    	return $this->hasMany('App\Model\VideoCategory', 'category_id');
    }
}
