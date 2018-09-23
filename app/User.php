<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $table = 'users';
    protected $fillable = ['username', 'email', 'active', 'gender', 'status', 'name', 'title', 'phone', 'avatar', 'phone2', 'email2', 'note', 'birthday', 'firstlogin', 'role_id', 'team_id', 'parent_id', 'warehouse_id', 'user_id', 'group_id', 'indexes', 'deleted_at'];
    public $timestamps = true;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function team()
    {
        return $this->belongsTo('App\Team', 'team_id');
    }
}
