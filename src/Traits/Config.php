<?php
 
namespace Gurpreetsinghin\VaultsSecurity\Traits;
 
trait Config {

    public function prefix(){
        return "gsps_";
    }

    public function site_url(){
        return url('/');
    }

    public function vaultssecurity_path(){
        return url('/vaults-security');
    }

    public function current_url(){
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
        return $actual_link;
    }

    public function url_match($url){

        $current_url = $this->current_url();

        if (strpos($url, 'http://') !== false) {
            return ($url != $current_url) ? true : false;
        }elseif(strpos($url, 'https://') !== false){
            return ($url != $current_url) ? true : false;
        }elseif(strpos($current_url, url('/'.$url)) !== false){
            return false;
        }else{
            return true;
        }
    }
 
}