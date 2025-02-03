<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeContactUs extends Model
{
    protected $table = 'home_contact_us';
    protected $fillable = ['name','email','phone','message','project_name','project_code','cluster_code','lead_source_name','cookie','ip_client','otp_code'];
}
