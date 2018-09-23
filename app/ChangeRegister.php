<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRegister extends Model
{
<<<<<<< HEAD
    protected $table='change_registers';
    protected $fillable = ['date','creator_id','cr_number','content','purpose','combinator_id','combine_phone_nb','executor_id','execute_content','tester_id','result','note'];
=======
    protected $fillable = ['date','creator_id','cr_number','content','purpose','preparer_id','prepare_content','combinator_id','combine_phone_nb','executor_id','execute_content','tester_id','result','note'];
>>>>>>> a29c8c90d25952768401b8dc0f077304a12533af
}
