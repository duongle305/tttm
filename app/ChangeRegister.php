<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRegister extends Model
{
    protected $fillable = ['date','creator_id','cr_number','content','purpose','combinator_id','combine_phone_nb','executor_id','execute_content','tester_id','result','note'];
}
