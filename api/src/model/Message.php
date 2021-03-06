<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $table = 'Message';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    public function author() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}