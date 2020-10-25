<?php

namespace Gurpreetsinghin\VaultsSecurity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class Settings extends Authenticatable
{
    use Notifiable, Config;

    protected $table = "gsps_settings";
    protected $guarded=[];
}
