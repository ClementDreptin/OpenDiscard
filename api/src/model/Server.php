<?php
namespace OpenDiscard\api\model;

use Illuminate\Database\Eloquent\Model;

class Server extends Model {
    protected $table = 'Server';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    public function members() {
        return $this->belongsToMany(User::class, "User_Server", "server_id", "user_id");
    }

    public function textChannels() {
        return $this->hasMany(TextChannel::class, "server_id", "id");
    }

    public function delete() {
        $this->members()->detach();
        $this->textChannels()->each(function(TextChannel $textChannel) {
            $textChannel->delete();
        });

        parent::delete();
    }
}