<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    protected $table = 'microsite';
    protected $fillable = ['name','slug','home_variant','navbar_variant','status','menu_id','site_logo'];
}
