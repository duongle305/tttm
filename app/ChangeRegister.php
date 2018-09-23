<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRegister extends Model
{
    protected $fillable = ['date','creator_id','cr_number','content','purpose','preparer_id','prepare_content','combinator_id','combine_phone_nb','executor_id','execute_content','tester_id','result','note'];
}
