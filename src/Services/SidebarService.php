<?php

namespace Gurpreetsinghin\VaultsSecurity\Services;

use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

Class SidebarService{

    use Config;

    public function sqliSettings(){
        $data = DB::table($this->prefix().'sqli-settings')->first();
        if(!empty($data)){
            return ($data->protection == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-danger">OFF</span>';
        }else{
            return '<span class="right badge badge-danger">OFF</span>';
        }
    }

    public function badbotSettings(){
        $data = DB::table($this->prefix().'badbot-settings')->first();
        if(!empty($data)){
            return ($data->protection == 1 || $data->protection2 == 1 || $data->protection3 == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-danger">OFF</span>';
        }else{
            return '<span class="right badge badge-danger">OFF</span>';
        }
    }

    public function proxySettings(){
        $data = DB::table($this->prefix().'proxy-settings')->first();
        if(!empty($data)){
            return ($data->protection > 0 || $data->protection2 == 1 || $data->protection3 == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-danger">OFF</span>';
        }else{
            return '<span class="right badge badge-danger">OFF</span>';
        }
    }

    public function spamSettings(){
        $data = DB::table($this->prefix().'spam-settings')->first();
        if(!empty($data)){
            return ($data->protection == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-danger">OFF</span>';
        }else{
            return '<span class="right badge badge-danger">OFF</span>';
        }
    }

    public function adBlockerSettings(){
        $data = DB::table($this->prefix().'adblocker-settings')->first();
        if(!empty($data)){
            return ($data->detection == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-primary">OFF</span>';
        }else{
            return '<span class="right badge badge-primary">OFF</span>';
        }
    }

    public function badWords(){
        $data = DB::table($this->prefix().'bad-words')->first();
        return (!empty($data)) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-primary">OFF</span>';
    }

    public function anaytics(){
        $data = DB::table($this->prefix().'settings')->where('id', Auth::id())->first();
        if(!empty($data)){
            return ($data->live_traffic == 1) ? '<span class="right badge badge-success">ON</span>' : '<span class="right badge badge-primary">OFF</span>';
        }else{
            return '<span class="right badge badge-primary">OFF</span>';
        }
    }

    public function logsCount(){
        $data = [
            'all' => DB::table($this->prefix().'logs')->count(),
            'sqli' => DB::table($this->prefix().'logs')->where('type', 'SQLi')->count(),
            'badbot' => DB::table($this->prefix().'logs')->where('type', 'Bad Bot')->orWhere('type', 'Fake Bot')->orWhere('type', 'Missing User-Agent header')->orWhere('type', 'Missing header Accept')->orWhere('type', 'Invalid IP Address header')->count(),
            'proxy' => DB::table($this->prefix().'logs')->where('type', 'Proxy')->count(),
            'spam' => DB::table($this->prefix().'logs')->where('type', 'Spammer')->count(),
        ];

        return $data;
    }

    public function bansCount(){
        $data = [
            'ip' => DB::table($this->prefix().'bans')->count(),
            'country' => DB::table($this->prefix().'bans-country')->count(),
            'ranges' => DB::table($this->prefix().'bans-ranges')->count(),
            'other' => DB::table($this->prefix().'bans-other')->count(),
        ];

        return $data;
    }

}