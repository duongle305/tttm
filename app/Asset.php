<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public function qlts_code()
    {
        return $this->belongsTo('App\AssetQltsCode','asset_qlts_code_id');
    }
}
