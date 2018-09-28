<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetNextPosition extends Model
{
    protected $table='asset_next_positions';
    protected $fillable = ['current_id','next_id'];



}
