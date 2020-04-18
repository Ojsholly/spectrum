<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Book extends Model
{
    use softDeletes;
    //
    protected $fillable = ['title', 'author', 'path', 'front_cover'];

    public function book_count()
    {
        return $this->all()->count();
    }

    public function get_front_cover()
    {
        return Storage::url($this->front_cover);
    }

    public function get_download_link()
    {
        return Storage::url($this->path);
    }
}
