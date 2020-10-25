<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class MainController extends Controller
{

    use Config;

    public function dashboard(){


        $date   = date('d F Y');
        $table  = $this->prefix() . 'logs';

        $data['count1'] = DB::table($table)->where(['date' => $date, 'type' => 'SQLi'])->count();
        $data['count2'] = DB::table($table)->where(['date' => $date])->where(function($query){
            $query->where('type', 'Bad Bot');
            $query->orWhere('type', 'Fake Bot');
            $query->orWhere('type', 'Missing User-Agent header');
            $query->orWhere('type', 'Missing header Accept');
            $query->orWhere('type', 'Invalid IP Address header');
        })->count();
        $data['count3'] = DB::table($table)->where(['date' => $date, 'type' => 'Proxy'])->count();
        $data['count4'] = DB::table($table)->where(['date' => $date, 'type' => 'Spammer'])->count();

        $data['countm1'] = DB::table($table)->where(['type' => 'SQLi'])->count();
        $data['countm2'] = DB::table($table)->where('type', 'Bad Bot')->orWhere('type', 'Fake Bot')->orWhere('type', 'Missing User-Agent header')->orWhere('type', 'Missing header Accept')->orWhere('type', 'Invalid IP Address header')->count();
        $data['countm3'] = DB::table($table)->where(['type' => 'Proxy'])->count();
        $data['countm4'] = DB::table($table)->where(['type' => 'Spammer'])->count();

        $setting['sqli'] = DB::table($this->prefix().'sqli-settings')->first();
        $setting['badbot'] = DB::table($this->prefix().'badbot-settings')->first();
        $setting['proxy'] = DB::table($this->prefix().'proxy-settings')->first();
        $setting['spam'] = DB::table($this->prefix().'spam-settings')->first();

        /****************/

        $url = 'http://extreme-ip-lookup.com/json/127.0.0.1';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60');
        curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
        $ipcontent = curl_exec($ch);
        curl_close($ch);

        $ip_data = @json_decode($ipcontent);
        if ($ip_data && $ip_data->{'status'} == 'success') {
            $data['gstatus'] = 'online';
        } else {
            $data['gstatus'] = 'offline';
        }

        /*****************/

        $data['proxy_check'] = 0;

        if ($setting['proxy']->protection > 0 && $setting['proxy']->protection != 4) {
            $apik = 'api' . $setting['proxy']->protection;
            $key  = $setting['proxy']->$apik;
        }

        if ($setting['proxy']->protection == 1) {
            //Invalid API Key ==> Offline
            $ch  = curl_init();
            $url = "http://v2.api.iphub.info";
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $data['proxy_check'] = 1;
            }
            
        } else if ($setting['proxy']->protection == 2) {
            
            $ch = curl_init('http://proxycheck.io/v2/8.8.8.8');
            $curl_options = array(
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true
            );
            curl_setopt_array($ch, $curl_options);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $data['proxy_check'] = 1;
            }
            
        } else if ($setting['proxy']->protection == 3) {
            //Invalid API Key ==> Offline
            $headers = [
                'X-Key: '.$key.'',
            ];
            $ch = curl_init("https://www.iphunter.info:8082/v1/ip/8.8.8.8");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $data['proxy_check'] = 1;
            }

        } else if ($setting['proxy']->protection == 4) {
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://blackbox.ipinfo.app/lookup/8.8.8.8');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
            $proxyresponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $data['proxy_check'] = 1;
            }
            
        } else {
            $data['proxy_check'] = -1;
        }

        /************/

        $data['logs'] = DB::table($table)->orderBy('id', 'desc')->skip(0)->take(2)->get();
        $data['bans'] = DB::table($this->prefix().'bans')->orderBy('id', 'desc')->skip(0)->take(2)->get();

        /*****************/

        $data['all_logs'] = [
            'total' => DB::table($table)->count(),
            'today' => DB::table($table)->where('date', date("d F Y"))->count(),
            'this_month' => DB::table($table)->where('date', 'LIKE', '% '.date("F Y"))->count(),
            'this_year' => DB::table($table)->where('date', 'LIKE', '% '.date("Y"))->count(),
        ];

        $data['all_bans'] = [
            'total' => DB::table($this->prefix().'bans')->count(),
            'today' => DB::table($this->prefix().'bans')->where('date', date("d F Y"))->count(),
            'this_month' => DB::table($this->prefix().'bans')->where('date', 'LIKE', '% '.date("F Y"))->count(),
            'this_year' => DB::table($this->prefix().'bans')->where('date', 'LIKE', '% '.date("Y"))->count(),
        ];

        /*************/

        $data['countries'] = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombi", "Comoros", "Congo (Brazzaville)", "Congo", "Costa Rica", "Cote d\'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor (Timor Timur)", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia, The", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia and Montenegro", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];


        // dd($data);
        return view('project-security::admin.main.dashboard', ['data' => $data, 'table' => $table, 'setting' => $setting]);
    }

    public function loginHistory(){
        $logins = DB::table($this->prefix().'logins')->orderBy('id', 'desc')->get();
        return view('project-security::admin.login-history', ['logins' => $logins]);
    }

    public function sqlInjection(Request $request){

        if($request->isMethod('post')){

            $fields = ['protection', 'protection2', 'protection3', 'protection4', 'protection5', 'protection6', 'protection7', 'protection8', 'logging', 'autoban', 'mail'];

            $post = request()->except(['_token']);

            foreach($fields as $field){
                if($request->has($field)){
                    $post[$field] = 1;
                }else{
                    $post[$field] = 0;
                }
            }

            try{
                DB::table($this->prefix().'sqli-settings')->where('id', 1)->update($post);
                return back()->with('success', 'Sqli injection settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $sql = DB::table($this->prefix().'sqli-settings')->first();
        return view('project-security::admin.sql-injection', ['sql' => $sql]);
    }

    public function badbots(Request $request){
        if($request->isMethod('post')){
            $fields = ['protection', 'protection2', 'protection3', 'logging', 'autoban', 'mail'];
            $post = request()->except(['_token']);

            foreach($fields as $field){
                if($request->has($field)){
                    $post[$field] = 1;
                }else{
                    $post[$field] = 0;
                }
            }

            try{
                DB::table($this->prefix().'badbot-settings')->where('id', 1)->update($post);
                return back()->with('success', 'Badbot settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $bots = DB::table($this->prefix().'badbot-settings')->first();
        return view('project-security::admin.main.badbots', ['bots' => $bots]);
    }

    public function proxy(Request $request){

        $filter_api = $request->has('api') && $request->get('api') != '' ? $request->get('api') : '';
        if($filter_api != ''){
            if(in_array($filter_api, range(0,4))){
                DB::table($this->prefix().'proxy-settings')->where('id', 1)->update(['protection' => $filter_api]);
                return back()->with('success', 'Proxy settings has been updated!');
            }
        }

        if($request->isMethod('post')){

            // dd($request->all());
            
            $fields = ['protection2', 'protection3', 'logging', 'autoban', 'mail'];
            $post = request()->except(['_token', 'apikey']);

            foreach($fields as $field){
                if($request->has($field)){
                    $post[$field] = 1;
                }else{
                    $post[$field] = 0;
                }
            }

            try{
                DB::table($this->prefix().'proxy-settings')->where('id', 1)->update($post);

                $proxy = DB::table($this->prefix().'proxy-settings')->first();
                if ($proxy->protection > 0 && $proxy->protection != 4) {
                    $api_key = $request->apikey;
                    DB::table($this->prefix().'proxy-settings')->where('id', 1)->update(['api'.$proxy->protection => $api_key]);
                }

                return back()->with('success', 'Proxy settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $proxy = DB::table($this->prefix().'proxy-settings')->first();
        $data = $this->proxy2($proxy->protection, $proxy);

        return view('project-security::admin.main.proxy', ['proxy' => $proxy, 'data' => $data]);
    }

    public function proxy2($protection, $proxy){

        $data = [];

        if ($protection > 0 && $protection != 4) {
            $apik = 'api' . $protection;
            $key  = $proxy->$apik;
            $data['api_key'] = $key;
            
            $data['proxy_check'] = 0;
            
            if ($protection == 1) {
                //Invalid API Key ==> Offline
                $ch  = curl_init();
                $url = "http://v2.api.iphub.info/ip/8.8.8.8";
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_CONNECTTIMEOUT => 30,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [ "X-Key: {$key}" ]
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                curl_close($ch);
                
                if ($httpCode >= 200 && $httpCode < 300) {
                    $data['proxy_check'] = 1;
                }
            } else if ($protection == 2) {
                
                $data['proxy_check'] = 1;
                
            } else if ($protection == 3) {
                //Invalid API Key ==> Offline
                $headers = [
                    'X-Key: '.$key.'',
                ];
                $ch = curl_init("https://www.iphunter.info:8082/v1/ip/8.8.8.8");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                curl_close($ch);
                
                if ($httpCode >= 200 && $httpCode < 300) {
                    $data['proxy_check'] = 1;
                }
                
            }

            if ($key == NULL OR $data['proxy_check'] == 0) {
                if ($protection == 1) {
                    $data['apik_url'] = 'https://iphub.info/pricing';
                } else if ($protection == 2) {
                    $data['apik_url'] = 'https://proxycheck.io/pricing';
                } else if ($protection == 3) {
                    $data['apik_url'] = 'https://www.iphunter.info/prices';
                }else{
                    $data['apik_url'] = '';
                }
            }else{
                $data['apik_url'] = '';
            }

            return $data;
        }
    }

    public function adblockerDetection(Request $request){
        if($request->isMethod('post')){
            $fields = ['detection'];
            $post = request()->except(['_token']);

            foreach($fields as $field){
                if($request->has($field)){
                    $post[$field] = 1;
                }else{
                    $post[$field] = 0;
                }
            }

            try{
                DB::table($this->prefix().'adblocker-settings')->where('id', 1)->update($post);
                return back()->with('success', 'Adblocker Detection settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $ads = DB::table($this->prefix().'adblocker-settings')->first();

        return view('project-security::admin.main.adblocker-detection', ['ads' => $ads]);
    }

    public function phpFunctionsCheck(){
        return view('project-security::admin.main.php-functions-check');
    }

    public function phpConfigCheck(){
        return view('project-security::admin.main.php-config-check');
    }

    public function errorMonitoring(Request $request){

        if($request->isMethod('post')){
            try{
                DB::table($this->prefix().'settings')->where('id', Auth::id())->update(request()->except(['_token']));
                return back()->with('success', 'Settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $setting = DB::table($this->prefix().'settings')->where('id', Auth::id())->first();
        return view('project-security::admin.main.error-monitoring', ['setting' => $setting]);
    }

    public function portScanner(){
        @set_time_limit(360);
        ini_set('max_execution_time', 300); //300 Seconds = 5 Minutes
        ini_set('memory_limit', '512M');

        $ports = array(
            20,
            21,
            22,
            23,
            25,
            53,
            79,
            80,
            110,
            115,
            119,
            135,
            137,
            138,
            139,
            143,
            194,
            389,
            443,
            445,
            465,
            520,
            548,
            515,
            587,
            631,
            993,
            995,
            1080,
            1433,
            1434,
            1521,
            1701,
            1723,
            2082,
            2086,
            2095,
            3306,
            3389,
            5432,
            5900,
            8000,
            8080,
            11211
        );
            
        $results = array();
        foreach ($ports as $port) {
            if ($pf = @fsockopen($_SERVER['SERVER_NAME'], $port, $err, $err_string, 1)) {
                $results[$port] = true;
                fclose($pf);
            } else {
                $results[$port] = false;
            }
        }

        return view('project-security::admin.main.port-scanner', ['results' => $results]);
    }

    public function blacklistChecker(Request $request){

        $data = [];

        if($request->isMethod('post')){
            $request->validate([
                'ip'                 =>  'required|ipv4',
            ]);

            @set_time_limit(360);
            ini_set('max_execution_time', 300); //300 Seconds = 5 Minutes
            ini_set('memory_limit', '512M');
            
            $data['dnsbl_lookup'] = array(
                "all.s5h.net",
                "b.barracudacentral.org",
                "bl.spamcannibal.org",
                "bl.spamcop.net",
                "blacklist.woody.ch",
                "bogons.cymru.com",
                "cbl.abuseat.org",
                "cdl.anti-spam.org.cn",
                "combined.abuse.ch",
                "db.wpbl.info",
                "dnsbl-1.uceprotect.net",
                "dnsbl-2.uceprotect.net",
                "dnsbl-3.uceprotect.net",
                "dnsbl.anticaptcha.net",
                "dnsbl.cyberlogic.net",
                "dnsbl.dronebl.org",
                "dnsbl.inps.de",
                "dnsbl.sorbs.net",
                "drone.abuse.ch",
                "drone.abuse.ch",
                "duinv.aupads.org",
                "dul.dnsbl.sorbs.net",
                "dyna.spamrats.com",
                "dynip.rothen.com",
                "exitnodes.tor.dnsbl.sectoor.de",
                "http.dnsbl.sorbs.net",
                "ips.backscatterer.org",
                "ix.dnsbl.manitu.net",
                "korea.services.net",
                "misc.dnsbl.sorbs.net",
                "noptr.spamrats.com",
                "orvedb.aupads.org",
                "pbl.spamhaus.org",
                "proxy.bl.gweep.ca",
                "psbl.surriel.com",
                "relays.bl.gweep.ca",
                "relays.nether.net",
                "sbl.spamhaus.org",
                "short.rbl.jp",
                "singular.ttk.pte.hu",
                "smtp.dnsbl.sorbs.net",
                "socks.dnsbl.sorbs.net",
                "spam.abuse.ch",
                "spam.dnsbl.anonmails.de",
                "spam.dnsbl.sorbs.net",
                "spam.spamrats.com",
                "spambot.bls.digibase.ca",
                "spamrbl.imp.ch",
                "spamsources.fabel.dk",
                "ubl.lashback.com",
                "ubl.unsubscore.com",
                "virus.rbl.jp",
                "web.dnsbl.sorbs.net",
                "wormrbl.imp.ch",
                "xbl.spamhaus.org",
                "z.mailspike.net",
                "zen.spamhaus.org",
                "zombie.dnsbl.sorbs.net"
            );
            
            $data['AllCount'] = count($data['dnsbl_lookup']);
            $data['BadCount'] = 0;
            
            $data['reverse_ip'] = implode(".", array_reverse(explode(".", $request->ip)));
        }

        return view('project-security::admin.main.blacklist-checker', ['data' => $data]);

    }

    public function hashing(){
        return view('project-security::admin.main.hashing');
    }

    public function settings(Request $request){
        
        $setting = DB::table($this->prefix() . 'settings')->first();

        if($request->isMethod('post')){

            $post = request()->except(['_token']);

            $post['project_security'] = ($request->has('project_security')) ? 1 : 0;
            $post['mail_notifications'] = ($request->has('mail_notifications')) ? 1 : 0;
            $post['ip_detection'] = ($request->has('ip_detection')) ? 2 : 1;

            try{
                DB::table($this->prefix() . 'settings')->where('id', $setting->id)->update($post);
                return back()->with('success', 'Settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }

        }

        $setting = DB::table($this->prefix() . 'settings')->first();

        return view('project-security::admin.main.settings', ['setting' => $setting]);
    }

    public function account(Request $request){
        if($request->isMethod('post')){

            $request->validate([
                'username'             => 'required|max:255|unique:'.$this->prefix().'settings,username,'.Auth::id(),
            ]);

            $post = request()->except(['_token', 'password']);

            if($request->password != null){
                $post['password'] = Hash::make($request->password);
            }

            try{
                DB::table($this->prefix().'settings')->where('id', Auth::id())->update($post);
                return back()->with('success', 'Account has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $setting = DB::table($this->prefix() . 'settings')->select('username')->first();
        return view('project-security::admin.main.account', ['setting' => $setting]);
    }

    public function visitAnalytics(){

        $table = $this->prefix().'live-traffic';

        //Today Stats
        @$date = @date('d F Y');
        @$ctime = @date("H:i", strtotime('-30 seconds'));

        $data['tscount1'] = DB::table($table)->where('date', $date)->where('time','>=',$ctime)->count();
        $data['tscount2'] = DB::table($table)->where(['date' => $date, 'uniquev' => 1])->count();
        $data['tscount3'] = DB::table($table)->where(['date' => $date])->count();
        $data['tscount4'] = DB::table($table)->where(['date' => $date, 'uniquev' => 1, 'bot' => 1])->count();

        //Month Stats
        @$mdate = @date('F Y');
        $data['mscount1'] = DB::table($table)->where('date','LIKE','%'.$mdate)->where('uniquev', 1)->count();
        $data['mscount2'] = DB::table($table)->where('date','LIKE','%'.$mdate)->count();
        $data['mscount3'] = DB::table($table)->where('date','LIKE','%'.$mdate)->where(['uniquev' => 1, 'bot' => 1])->count();

        //Browser Stats
        $data['bcount1'] = DB::table($table)->where('browser', 'Google Chrome')->count();
        $data['bcount2'] = DB::table($table)->where('browser', 'LIKE', '%Firefox%')->count();
        $data['bcount3'] = DB::table($table)->where('browser', 'Opera')->count();
        $data['bcount4'] = DB::table($table)->where('browser', 'LIKE', '%Edge%')->count();
        $data['bcount5'] = DB::table($table)->where('browser', 'Internet Explorer')->count();
        $data['bcount6'] = DB::table($table)->where('browser', 'LIKE', '%Safari%')->count();
        $data['bcount7'] = DB::table($table)->where('browser', '!=', 'Google Chrome')->where('browser', 'not like', '%Firefox%')->where('browser', '!=', 'Opera')->where('browser', 'not like', '%Edge%')->where('browser', '!=', 'Internet Explorer')->where('browser', 'not like', '%Safari%')->count();

        //OS Stats
        $data['ocount1'] = DB::table($table)->where('os', 'LIKE', '%Windows%')->count();
        $data['ocount2'] = DB::table($table)->where('os', 'LIKE', '%Linux%')->count();
        $data['ocount3'] = DB::table($table)->where('os', 'LIKE', '%Android%')->count();
        $data['ocount4'] = DB::table($table)->where('os', 'LIKE', '%iOS%')->count();
        $data['ocount5'] = DB::table($table)->where('os', 'LIKE', '%Mac OS X%')->count();
        $data['ocount6'] = DB::table($table)->where('os', 'not like', '%Windows%')->where('os', 'not like', '%Linux%')->where('os', 'not like', '%Android%')->where('os', 'not like', '%iOS%')->where('os', 'not like', '%Mac OS X%')->count();

        //Platform Stats
        $data['pcount1'] = DB::table($table)->where('device_type', 'Computer')->count();
        $data['pcount2'] = DB::table($table)->where('device_type', 'Mobile')->count();
        $data['pcount3'] = DB::table($table)->where('device_type', 'Tablet')->count();

        $countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombi", "Comoros", "Congo (Brazzaville)", "Congo", "Costa Rica", "Cote d\'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor (Timor Timur)", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia, The", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia and Montenegro", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];
        
        $config4_label = '';

        $i    = 1;
        @$days = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
        while ($i <= $days) {
            $config4_label .="'$i'";
            
            if ($i != $days) {
                $config4_label .= ',';
            }
            
            $i++;
        }

        $config4_data = '';
        $i = 1;
        while ($i <= $days) {
            @$mdatef = sprintf("%02d", $i) . ' ' . date("F Y");
            $mcount1 = DB::table($table)->where(['date' => $mdatef])->count();
            $config4_data .= "'$mcount1'";
            
            if ($i != $days) {
                $config4_data .= ',';
            }
            
            $i++;
        }

        $data['config4_label'] = $config4_label;
        $data['config4_data'] = $config4_data;

        return view('project-security::admin.main.visit-analytics', ['data' => $data, 'countries' => $countries, 'table' => $table]);
    }

    public function ipLookup(Request $request){

        $ip = $request->has('ip') && $request->get('ip') != '' ? $request->get('ip') : '';

        if($ip == ''){
            return redirect(route('ps.admin.dashboard'))->with('error', 'Please enter IP Address');
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return redirect(route('ps.admin.dashboard'))->with('error', 'IP Address is not valid');
        }

        $url = 'http://extreme-ip-lookup.com/json/' . $ip;
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60');
        curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
        $ipcontent = curl_exec($ch);
        curl_close($ch);
        
        $ip_data = @json_decode($ipcontent);
        if ($ip_data && $ip_data->{'status'} == 'success') {
            $data = [
                'country'     => $ip_data->{'country'},
                'countrycode' => $ip_data->{'countryCode'},
                'region'      => $ip_data->{'region'},
                'city'        => $ip_data->{'city'},
                'latitude'    => $ip_data->{'lat'},
                'longitude'   => $ip_data->{'lon'},
                'isp'         => $ip_data->{'isp'},
            ];
        } else {
            $data = [
                'country'     => "Unknown",
                'countrycode' => "XX",
                'region'      => "Unknown",
                'city'        => "Unknown",
                'latitude'    => "0",
                'longitude'   => "0",
                'isp'         => "Unknown",
            ];
        }

        $logs = DB::table($this->prefix().'logs')->where('ip', $ip)->get();
        $bans = DB::table($this->prefix().'bans')->where('ip', $ip)->get();

        return view('project-security::admin.main.ip-lookup', ['ip' => $ip, 'data' => $data, 'logs' => $logs, 'bans' => $bans]);
    }
}
