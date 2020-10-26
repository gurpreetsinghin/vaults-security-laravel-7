<?php

namespace Gurpreetsinghin\VaultsSecurity\Traits;

use Gurpreetsinghin\VaultsSecurity\Traits\Config;
use DB;
use Auth;
use \Gurpreetsinghin\VaultsSecurity\Traits\Lib\UserAgentFactoryPSec;
use Illuminate\Support\Facades\Mail;

trait Core {

    use Config;

    public $browser;

    public function ipDetails($useragent){
        //Getting Country, City, Region, Map Location and Internet Service Provider
        $url = 'http://extreme-ip-lookup.com/json/' . $this->getIP();
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
        $ipcontent = curl_exec($ch);
        curl_close($ch);

        $ip_data = @json_decode($ipcontent);
        if ($ip_data && $ip_data->{'status'} == 'success') {
            $data = [
                'country'      => $ip_data->{'country'},
                'country_code' => $ip_data->{'countryCode'},
                'region'       => $ip_data->{'region'},
                'city'         => $ip_data->{'city'},
                'latitude'     => $ip_data->{'lat'},
                'longitude'    => $ip_data->{'lon'},
                'isp'          => $ip_data->{'isp'}
            ];
        } else {
            $data = [
                'country'      => "Unknown",
                'country_code' => "XX",
                'region'       => "Unknown",
                'city'         => "Unknown",
                'latitude'     => "0",
                'longitude'    => "0",
                'isp'          => "Unknown"
            ];
        }

        return $data;
    }

    public function getIP(){

        $setting = DB::table($this->prefix().'settings')->first();

        if ($setting->ip_detection == 2) {
            $ip = $this->psec_get_realip();
        } else {
            $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
        }
        if ($ip == "::1") {
            $ip = "127.0.0.1";
        }

        return $ip;
    }

    public function userAgentData(){
        //Getting Browser and Operating System
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        $useragent_data = UserAgentFactoryPSec::analyze($useragent);

        $ipnums = explode(".", $this->getIP());

        $data = [
            'ipnums'       => explode(".", $this->getIP()),
            'ip_range'     => $ipnums[0] . "." . $ipnums[1] . "." . $ipnums[2],
            'page'         => $_SERVER['PHP_SELF'],
            'browser'      => $useragent_data->browser['title'],
            'browsersh'    => $useragent_data->browser['name'],
            'browser_code' => $useragent_data->browser['code'],
            'os'           => $useragent_data->os['title'],
            'ossh'         => $useragent_data->os['name'] . " " . $useragent_data->os['version'],
            'os_code'      => $useragent_data->os['code']
        ];

        $this->browser = $useragent_data->browser['title'];
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            $data['referer'] = $_SERVER["HTTP_REFERER"];
        } else {
            $data['referer'] = '';
        }

        $data['useragent'] = $useragent;

        $data['script_name'] = ltrim($_SERVER["SCRIPT_NAME"], '/');
        @$data['date'] = @date("d F Y");
        @$data['time'] = @date("H:i");

        return $data;
    }

    public function psec_get_realip(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        return $ipaddress;
    }

    public function psec_getcache($ip, $cache_file){
        
        if (file_exists($cache_file)) {
            
            $current_time = time();
            $expire_time  = 1 * 60 * 60;
            $file_time    = filemtime($cache_file);
            
            if ($current_time - $expire_time < $file_time) {
                return file_get_contents($cache_file);
            } else {
                return 'PSEC_NoCache';
            }
        } else {
            return 'PSEC_NoCache';
        }
    }

    function psec_logging($type){
        
        $data = $this->userAgentData();
        
        $ltable     = $this->prefix() . 'logs';

        $check = DB::table($ltable)->where(['ip' => $this->getIP(), 'page' => $data['page'], 'type' => $type, 'date' => $data['date']])->count();


        
        if($check <= 0){
            $data2 = $this->ipDetails($data['useragent']);

            $insertion = [
                'ip'    =>  $this->getIP(),
                'date'    =>    $data['date'],
                'time'    =>  $data['time'],
                'page'    =>    $data['page'],
                'query'   =>  '',
                'type'   => $type,
                'browser'    =>    $data['browser'],
                'browser_code'    =>  $data['browser_code'],
                'os'  =>    $data['os'],
                'os_code'   =>    $data['os_code'],
                'country'    =>   $data2['country'],
                'country_code'    => $data2['country_code'],
                'region'  =>   $data2['region'],
                'city'  =>   $data2['city'],
                'latitude'  =>   $data2['latitude'] ? $data2['latitude'] : 0,
                'longitude' =>   $data2['longitude'] ? $data2['longitude'] : 0,
                'isp'  =>    $data2['isp'],
                'useragent' =>    $data['useragent'],
                'referer_url'  =>$data['referer'] 
           ];

        //    dd($insertion);

            DB::table($ltable)->insert($insertion);
        }
    }

    public function psec_autoban($type){

        $ip = $this->getIP();
        @$date = @date("d F Y");
        @$time = @date("H:i");
    
        $table    = $this->prefix() . 'bans';
        $check = DB::table($table)->where('ip', $ip)->count();
        if ($check <= 0) {
            $data = [
                'ip'        =>  $ip,
                'date'      =>  $date,
                'time'      =>  $time,
                'reason'    =>  $type,
                'autoban'   =>  1
            ];

            DB::table($table)->insert($data);
        }
    }

    public function ipRange(){
        $ipnums       = explode(".", $this->getIP());
        $ip_range     = $ipnums[0] . "." . $ipnums[1] . "." . $ipnums[2];

        return $ip_range;
    }

    public function getSetting(){
        $setting = DB::table($this->prefix() . 'settings')->first();
        return $setting;
    }

    public function psec_mail($type){
// dd('dfdf');
        $useragent = $this->userAgentData();
        $setting = $this->getSetting();
        // dd($setting);
        $email   = 'notifications@' . $_SERVER['SERVER_NAME'] . '';
        $to      = $setting->email;
        $subject = 'Vaults Security - ' . $type . '';
        $messagee = '
                        <p style="padding:0; margin:0 0 11pt 0;line-height:160%; font-size:18px;">Details of Log - ' . $type . '</p>
                        <p>IP Address: <strong>' . $this->getIP() . '</strong></p>
                        <p>Date: <strong>' . $useragent['date'] . '</strong> at <strong>' . $useragent['time'] . '</strong></p>
                        <p>Browser:  <strong>' . $useragent['browser'] . '</strong></p>
                        <p>Operating System:  <strong>' . $useragent['os'] . '</strong></p>
                        <p>Threat Type:  <strong>' . $type . '</strong> </p>
                        <p>Page:  <strong>' . $useragent['page'] . '</strong> </p>
                        <p>Referer URL:  <strong>' . $useragent['referer'] . '</strong> </p>
                        <p>Site URL:  <strong>' . url('/') . '</strong> </p>
                        <p>Vaults Security URL:  <strong>' . route('ps.admin.login') . '</strong> </p>
                    ';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $to . ' <' . $to . '>' . "\r\n";
        $headers .= 'From: ' . $email . ' <' . $email . '>' . "\r\n";
        // @mail($to, $subject, $message, $headers);
        try{
            Mail::send([], [], function ($message) use ($to, $email, $subject, $messagee){
                $message->to($to)
                ->from($email)
                ->subject($subject)
                ->setBody($messagee, 'text/html'); // for HTML rich messages
            });
            dd('Mail sent to admin!');
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

}