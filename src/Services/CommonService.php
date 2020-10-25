<?php

namespace Gurpreetsinghin\VaultsSecurity\Services;

use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

Class CommonService{

    use Config;

    public function isBanned($ip){
        $check = DB::table($this->prefix().'bans')->where('ip', $ip)->count();
        if($check > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getBannedId($ip){
        $ban = DB::table($this->prefix().'bans')->where('ip', $ip)->first();
        return $ban->id;
    }

}