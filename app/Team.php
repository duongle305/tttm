<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table='teams';
    protected $fillable=['name','shortname','note','user_id','group_id','indexes'];
    public $timestamps=true;
}
