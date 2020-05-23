<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'User';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
}