<?php

namespace Gurpreetsinghin\VaultsSecurity\Traits;

use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;
use Gurpreetsinghin\VaultsSecurity\Traits\Core;
use Gurpreetsinghin\VaultsSecurity\Traits\Modules\MainProtection;
use Illuminate\Support\Facades\File;

trait VaultsSecurity{

    use Config, Core, MainProtection;

    public function securityCheck(){

        $ip_whitelist = DB::table($this->prefix().'ip-whitelist')->where('ip', $this->getIP())->count();
        $file_whitelist = DB::table($this->prefix().'file-whitelist')->where('path', ltrim($_SERVER["SCRIPT_NAME"], '/'))->count();

        if($ip_whitelist <= 0 && $file_whitelist <= 0){
            $setting = DB::table($this->prefix().'settings')->first();
            //Error Reporting
            if ($setting->error_reporting == 1) {
                @error_reporting(0);
            }
            if ($setting->error_reporting == 2) {
                @error_reporting(E_ERROR | E_WARNING | E_PARSE);
            }
            if ($setting->error_reporting == 3) {
                @error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            }
            if ($setting->error_reporting == 4) {
                @error_reporting(E_ALL & ~E_NOTICE);
            }
            if ($setting->error_reporting == 5) {
                @error_reporting(E_ALL);
            }
            
            //Displaying Errors
            if ($setting->display_errors == 1) {
                @ini_set('display_errors', '1');
            } else {
                @ini_set('display_errors', '0');
            }

            if ($setting->project_security == 1) {

                $cache_file = public_path("/cache/");

                if (! File::exists($cache_file)) {
                    File::makeDirectory($cache_file);
                }

                $this->ban_system();
                $this->sqli_protection();
                $this->proxy_protection();
                $this->spam_protection();
                $this->badbots_protection();
                $this->fakebots_protection();
                $this->bad_words();
                $this->headers_check();
                $this->adblocker_detector();
                $this->live_traffic();
                
            }

        }

    }

}