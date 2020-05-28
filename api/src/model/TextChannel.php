<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class TextChannel extends Model {
    protected $table = 'Text_channel';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    public function server() {
        return $this->belongsTo(Server::class, 'server_id', 'id');
    }
}