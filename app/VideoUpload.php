<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoUpload extends Model
{
    protected $table = 'video_uploads';
    protected $fillable = ['title','local_file','status'];
}
