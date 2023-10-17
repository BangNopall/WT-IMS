<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Discord extends Model
{
    use Notifiable;

    protected $fillable = ['channel_id'];

    public function routeNotificationForDiscord()
    {
        return $this->channel_id;
    }
}
