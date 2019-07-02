<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['page','ads_id','city','lang','img','url','message','title','price','profile','profile_name','user_id','published'];
}
