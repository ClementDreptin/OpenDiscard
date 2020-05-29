<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'User';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
    protected $hidden = ['pivot'];

    public function servers() {
        return $this->belongsToMany(Server::class, "User_Server", "user_id", "server_id");
    }
}