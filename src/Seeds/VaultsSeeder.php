<?php

namespace Gurpreetsinghin\VaultsSecurity\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class VaultsSeeder extends Seeder
{

    use Config;

    public function run()
    {
        $this->sqli_settings();
        $this->badbot_settings();
        $this->proxy_settings();
        $this->spam_settings();
        $this->dnsbl_databases();
        $this->adblocker_settings();
        $this->pages_layolt();
    }

    public function sqli_settings(){
        $check = $this->check($this->prefix().'sqli-settings');

        if($check == 0){
            DB::table($this->prefix().'sqli-settings')->insert([
                'protection'    => 1,
                'protection2'   => 1,
                'protection3'   => 1,
                'protection4'   => 1,
                'protection5'   => 0,
                'protection6'   => 1,
                'protection7'   => 0,
                'protection8'   => 0,
                'logging'       => 1,
                'redirect'      => '',
                'autoban'       => 0,
                'mail'          => 0,
            ]);
        }
    }

    public function badbot_settings(){
        $check = $this->check($this->prefix().'badbot-settings');

        if($check == 0){
            DB::table($this->prefix().'badbot-settings')->insert([
                'protection'    => 1,
                'protection2'   => 1,
                'protection3'   => 1,
                'logging'       => 1,
                'autoban'       => 0,
                'mail'          => 0,
            ]);
        }
    }

    public function proxy_settings(){
        $check = $this->check($this->prefix().'proxy-settings');

        if($check == 0){
            DB::table($this->prefix().'proxy-settings')->insert([
                'protection'    => 1,
                'protection2'   => 1,
                'protection3'   => 1,
                'api1'          => '',
                'api2'          => '',
                'api3'          => '',
                'logging'       => 1,
                'autoban'       => 0,
                'redirect'      => '',
                'mail'          => 0,
            ]);
        }
    }

    public function spam_settings(){
        $check = $this->check($this->prefix().'spam-settings');

        if($check == 0){
            DB::table($this->prefix().'spam-settings')->insert([
                'protection'    => 0,
                'logging'       => 1,
                'autoban'       => 0,
                'redirect'      => '',
                'mail'          => 0,
            ]);
        }
    }

    public function dnsbl_databases(){
        DB::table($this->prefix().'dnsbl-databases')->insert([
            [
                'database'    => 'sbl.spamhaus.org'
            ],[
                'database'    => 'xbl.spamhaus.org'
            ]
        ]);
    }

    public function adblocker_settings(){
        $check = $this->check($this->prefix().'adblocker-settings');

        if($check == 0){
            DB::table($this->prefix().'adblocker-settings')->insert([
                'detection'    => 0,
                'redirect'      => ''
            ]);
        }
    }

    public function pages_layolt(){
        $check = $this->check($this->prefix().'pages-layolt');

        if($check == 0){
            $data = [
                [
                    'page'  =>      'Blocked',
                    'text'  =>      'Malicious request was detected'
                ],
                [
                    'page'  =>      'Proxy',
                    'text'  =>      'Access to the website via Proxy, VPN, TOR is not allowed (Disable Browser Data Compression if you have it enabled)'
                ],
                [
                    'page'  =>      'Spam',
                    'text'  =>      'You are in the Blacklist of Spammers and you cannot continue to the website'
                ],
                [
                    'page'  =>      'Banned',
                    'text'  =>      'You are banned and you cannot continue to the website'
                ],
                [
                    'page'  =>      'Banned_Country',
                    'text'  =>      'Sorry, but your country is banned and you cannot continue to the website'
                ],
                [
                    'page'  =>      'Blocked_Browser',
                    'text'  =>      'Access to the website through your Browser is not allowed, please use another Internet Browser'
                ],
                [
                    'page'  =>      'Blocked_OS',
                    'text'  =>      'Access to the website through your Operating System is not allowed'
                ],
                [
                    'page'  =>      'Blocked_ISP',
                    'text'  =>      'Your Internet Service Provider is blacklisted and you cannot continue to the website'
                ],
                [
                    'page'  =>      'Blocked_RFR',
                    'text'  =>      'Your referrer url is blocked and you cannot continue to the website'
                ],
                [
                    'page'  =>      'AdBlocker',
                    'text'  =>      'AdBlocker detected. Please support this website by disabling your AdBlocker'
                ],

            ];


            DB::table($this->prefix().'pages-layolt')->insert($data);
        }
    }

    public function check($db){
        $data = DB::table($db)->count();
        return $data;
    }

}

?>