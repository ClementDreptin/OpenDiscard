<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class Server extends Model {
    protected $table = 'Server';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
}