<?php

namespace Gurpreetsinghin\VaultsSecurity\Traits\Modules;

use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;
use Gurpreetsinghin\VaultsSecurity\Traits\Core;
use Gurpreetsinghin\VaultsSecurity\Traits\Lib\MobileDetect;
use Illuminate\Support\Facades\File;

Trait MainProtection{

    use Config, Core;

    public function ban_system(){

        $ip = $this->getIP();

        $cache_file = public_path("/cache/ip-details/");
        $cache_file2 = public_path("/cache/ip-details/". $ip .".json");

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        /************/

        $querybanned = DB::table($this->prefix().'bans')->where('ip', $ip)->count();
        if ($querybanned > 0) {
            return redirect(route('ps.site.banned'))->send();
        }

        //IP Ranges
        $querybanned = DB::table($this->prefix().'bans-ranges')->where('ip_range', $this->ipRange())->count();
        if ($querybanned > 0) {
            return redirect(route('ps.site.banned'))->send();
        }

        //Blocking Country
        $setting = DB::table($this->prefix().'settings')->select('countryban_blacklist')->first();
        $ban_country = DB::table($this->prefix().'bans-country')->count();
        $ban_other = DB::table($this->prefix().'bans-other')->where('type', 'isp')->count();

        if ($ban_country > 0 && $ban_other > 0) {
            if ($this->psec_getcache($ip, $cache_file2) == 'PSEC_NoCache') {
                $url = 'http://extreme-ip-lookup.com/json/' . $ip;
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                @curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
                @$ipcontent = curl_exec($ch);
                curl_close($ch);
            
                $ip_data = @json_decode($ipcontent);

                $this->makeCacheFile($cache_file, $cache_file2, $ipcontent);

            }else{
                $ip_data = @json_decode($this->psec_getcache($ip, $cache_file));
            }

            if ($ip_data && $ip_data->{'status'} == 'success') {
                $country_check = $ip_data->{'country'};
                $isp_check     = $ip_data->{'isp'};
            } else {
                $country_check = "Unknown";
                $isp_check     = "Unknown";
            }
        } else {
            @$isp_check = "Unknown";
            @$country_check = "Unknown";
        }

        $banned_country = DB::table($this->prefix().'bans-country')->select('id', 'country')->where('country', $country_check)->first();

        if($setting->countryban_blacklist == 1){
            if(!empty($banned_country)){
                return redirect(route('ps.site.banned-country', ['cid' => $banned_country->id]))->send();
            }
        } else {
            if (strpos(strtolower($useragent), "googlebot") !== false OR strpos(strtolower($useragent), "bingbot") !== false OR strpos(strtolower($useragent), "yahoo! slurp") !== false) {
            } else {
                if(empty($banned_country)){
                    return redirect(route('ps.site.banned-country'))->send();
                }
            }
        }

        //Blocking Browser

        $data = $this->userAgentData();
        $ban_browser = DB::table($this->prefix().'bans-other')->where('type', 'browser')->get();
        foreach($ban_browser as $browser) {
            if (strpos(strtolower($data['browser']), strtolower($browser->value)) !== false)
                return redirect(route('ps.site.blocked-browser'))->send();
        }

        //Blocking Operating System

        $ban_os = DB::table($this->prefix().'bans-other')->where('type', 'os')->get();
        foreach($ban_os as $os) {
            if (strpos(strtolower($data['os']), strtolower($os->value)) !== false)
                return redirect(route('ps.site.blocked-os'))->send();
        }

        //Blocking Internet Service Provider

        $ban_isp = DB::table($this->prefix().'bans-other')->where('type', 'isp')->get();
        foreach($ban_isp as $isp) {
            if (strpos(strtolower($isp_check), strtolower($isp->value)) !== false)
                return redirect(route('ps.site.blocked-isp'))->send();
        }

        //Blocking Referrer

        $ban_referer = DB::table($this->prefix().'bans-other')->where('type', 'referrer')->get();
        foreach($ban_referer as $ref) {
            if (strpos(strtolower(@$referer), strtolower($ref->value)) !== false)
                return redirect(route('ps.site.blocked-referer'))->send();
        }

    }

    public function sqli_protection(){
        $sqlisetting = DB::table($this->prefix().'sqli-settings')->first();
        $setting = DB::table($this->prefix().'settings')->first();

        if ($sqlisetting->protection == 1) {
    
            //XSS Protection - Block infected requests
            //@header("X-XSS-Protection: 1; mode=block");
            
            if ($sqlisetting->protection2 == 1) {
                //XSS Protection - Sanitize infected requests
                @header("X-XSS-Protection: 1");
            }
            
            if ($sqlisetting->protection3 == 1) {
                //Clickjacking Protection
                @header("X-Frame-Options: sameorigin");
            }
            
            if ($sqlisetting->protection4 == 1) {
                //Prevents attacks based on MIME-type mismatch
                @header("X-Content-Type-Options: nosniff");
            }
            
            if ($sqlisetting->protection5 == 1) {
                //Force secure connection
                @header("Strict-Transport-Security: max-age=15552000; preload");
            }
            
            if ($sqlisetting->protection6 == 1) {
                //Hide PHP Version
                @header('X-Powered-By: Vaults Security');
            }
            
            if ($sqlisetting->protection7 == 1) {
                //Sanitization of all fields and requests
                $_GET     = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
                $_POST    = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            }

            //Data Sanitization
            if ($sqlisetting->protection8 == 1) {

                $_POST    = $this->sanitize($_POST);
                $_GET     = $this->sanitize($_GET);
                $_REQUEST = $this->sanitize($_REQUEST);
                $_COOKIE  = $this->sanitize($_COOKIE);
                if (isset($_SESSION)) {
                    $_SESSION = $this->sanitize($_SESSION);
                }
            }

            $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    
            //Patterns, used to detect Malicous Request (SQL Injection)
            $patterns = array(
                "+select+",
                "+union+",
                "union+",
                "+or+",
                "**/",
                "/**",
                "0x3a",
                "/*",
                "*/",
                "*",
                ";",
                "||",
                "' #",
                "or 1=1",
                "'1'='1",
                "S@BUN",
                "`",
                "'",
                '"',
                "<",
                ">",
                "1,1",
                "1=1",
                "sleep(",
                "%27",
                "<?",
                "<?php",
                "?>",
                "../",
                "/localhost",
                "127.0.0.1",
                "loopback",
                "%0A",
                "%0D",
                "%3C",
                "%3E",
                "%00",
                "%2e%2e",
                "input_file",
                "path=.",
                "mod=.",
                "eval\(",
                "javascript:",
                "base64_",
                "boot.ini",
                "etc/passwd",
                "self/environ",
                "echo.*kae",
                "=%27$"
            );

            foreach ($patterns as $pattern) {
                if (strpos(strtolower($query_string), strtolower($pattern)) !== false) {
        
                    $querya = strip_tags(addslashes($query_string));
                    $type   = "SQLi";
                    
                    //Logging
                    if ($sqlisetting->logging == 1) {
                        $this->psec_logging($type);
                    }
                    
                    //AutoBan
                    if ($sqlisetting->autoban == 1) {
                        $this->psec_autoban($type);
                    }
                    // dd($sqlisetting);
                    //E-Mail Notification
                    if ($setting->mail_notifications == 1 && $sqlisetting->mail == 1) {
                        $this->psec_mail($type);
                    }
                    
                    if($this->url_match($sqlisetting->redirect)){
                        echo '<meta http-equiv="refresh" content="0;url=' . $sqlisetting->redirect . '" />';
                        exit;
                    }
                }
            }
        }
    }

    public function cleanInput($input){
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
        );
        
        $output = preg_replace($search, '', $input);
        return $output;
    }

    public function sanitize($input){
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = sanitize($val);
            }
        } else {
            $input  = str_replace('"', "", $input);
            $input  = str_replace("'", "", $input);
            $input  = $this->cleanInput($input);
            $output = htmlentities($input, ENT_QUOTES);
        }
        return @$output;
    }

    public function proxy_protection(){
        $proxy = DB::table($this->prefix().'proxy-settings')->first();
        $ip = $this->getIP();
        $cache_file = public_path("/cache/proxy/");
        $cache_file2 = public_path("/cache/proxy/". $ip .".json");
        $setting = $this->getSetting();

        if ($proxy->protection > 0) {
            $proxyv = 0;
            if ($proxy->protection == 1) {
                if ($this->psec_getcache($ip, $cache_file2) == 'PSEC_NoCache') {
                    $key = $proxy->api1;
            
                    $ch  = curl_init();
                    $url = 'http://v2.api.iphub.info/ip/' . $ip . '';
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_CONNECTTIMEOUT => 30,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [ "X-Key: {$key}" ]
                    ]);
                    $choutput = curl_exec($ch);
                    @$block   = json_decode($choutput)->block;
                    curl_close($ch);
                    
                    // Grabs API Response and Caches
                    $this->makeCacheFile($cache_file, $cache_file2, $choutput);
                } else {
                    @$block = json_decode($this->psec_getcache($ip, $cache_file))->block;
                }

                if ($block == 1) {
                    $proxyv = 1;
                }
            } elseif ($proxy->protection == 2) {
        
                if ($this->psec_getcache($ip, $cache_file) == 'PSEC_NoCache') {
                    $key = $proxy->api2;
                    
                    $ch           = curl_init('http://proxycheck.io/v2/' . $ip . '?key=' . $key . '&vpn=1');
                    $curl_options = array(
                        CURLOPT_CONNECTTIMEOUT => 30,
                        CURLOPT_RETURNTRANSFER => true
                    );
                    curl_setopt_array($ch, $curl_options);
                    $response = curl_exec($ch);
                    curl_close($ch);
        
                    $jsonc = json_decode($response);
                    
                    // Grabs API Response and Caches
                    file_put_contents($cache_file, $response);
                } else {
                    $jsonc = json_decode($this->psec_getcache($ip, $cache_file));
                }
                
                if (isset($jsonc->$ip->proxy) && $jsonc->$ip->proxy == "yes") {
                    $proxyv = 1;
                }
                
            } elseif ($proxy->protection == 3) {
        
                if ($this->psec_getcache($ip, $cache_file) == 'PSEC_NoCache') {
                    $key = $proxy->api3;
                    
                    $headers = [
                        'X-Key: '.$key,
                    ];
                    $ch = curl_init("https://www.iphunter.info:8082/v1/ip/" . $ip);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    
                    $choutput    = curl_exec($ch);
                    $output      = json_decode($choutput, 1);
                    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($http_status == 200) {
                        if ($output['data']['block'] == 1) {
                            $proxyv = 1;
                        }
                        
                        // Grabs API Response and Caches
                        file_put_contents($cache_file, $choutput);
                    }
                } else {
                    $output = json_decode($this->psec_getcache($ip, $cache_file), 1);
                    
                    if ($output['data']['block'] == 1) {
                        $proxyv = 1;
                    }
                }
                
            } elseif ($proxy->protection == 4) {
        
                if ($this->psec_getcache($ip, $cache_file) == 'PSEC_NoCache') {
                    
                    $url = 'http://blackbox.ipinfo.app/lookup/' . $ip;
                    $ch  = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
                    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                    curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
                    $proxyresponse = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                    curl_close($ch);
                    
                    if ($proxyresponse == 'Y') {
                        $proxyv = 1;
                        
                    }
                    
                    // Grabs API Response and Caches
                    file_put_contents($cache_file, $proxyresponse);
                } else {
                    $proxyresponse = $this->psec_getcache($ip, $cache_file);
                    
                    if ($proxyresponse == 'Y') {
                        $proxyv = 1;
                    }
                }
                
            }

            if ($proxyv == 1) {
        
                $type = "Proxy";
                
                //Logging
                if ($proxy->logging == 1) {
                    psec_logging($type);
                }
                
                //AutoBan
                if ($proxy->autoban == 1) {
                    psec_autoban($type);
                }
                
                //E-Mail Notification
                if ($setting->mail_notifications == 1 && $proxy->mail == 1) {
                    psec_mail($type);
                }
                
                if($this->url_match($proxy->redirect)){
                    echo '<meta http-equiv="refresh" content="0;url=' . $proxy->redirect . '" />';
                    exit;
                }
            }
        }

        //Method 2
        if ($proxy->protection2 == 1) {
            $proxy_headers = array(
                'HTTP_VIA',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_FORWARDED_HOST',
                'HTTP_FORWARDED',
                'HTTP_FORWARDED_FOR_IP',
                'HTTP_FORWARDED_PROTO',
                'HTTP_PROXY_CONNECTION'
            );
            foreach ($proxy_headers as $x) {
                if (isset($_SERVER[$x])) {
                    
                    $type = "Proxy";
                    
                    //Logging
                    if ($proxy->logging == 1) {
                        $this->psec_logging($type);
                    }
                    
                    //AutoBan
                    if ($proxy->autoban == 1) {
                        $this->psec_autoban($type);
                    }
                    
                    //E-Mail Notification
                    if ($setting->mail_notifications == 1 && $proxy->mail == 1) {
                        $this->psec_mail($type);
                    }
                    
                    if($this->url_match($proxy->redirect)){
                        echo '<meta http-equiv="refresh" content="0;url=' . $proxy->redirect . '" />';
                        exit;
                    }
                }
            }
        }

        //Method 3
        if ($proxy->protection3 == 1) {
            $ports = array(
                8080,
                80,
                1080,
                3128,
                4145,
                32231,
                53281
            );
            foreach ($ports as $port) {
                if (@fsockopen($ip, $port, $errno, $errstr, 30)) {
                    
                    $type = "Proxy";
                    
                    //Logging
                    if ($proxy->logging == 1) {
                        $this->psec_logging($type);
                    }
                    
                    //AutoBan
                    if ($proxy->autoban == 1) {
                        $this->psec_autoban($type);
                    }
                    
                    //E-Mail Notification
                    if ($setting->mail_notifications == 1 && $proxy->mail == 1) {
                        $this->psec_mail($type);
                    }
                    
                    if($this->url_match($proxy->redirect)){
                        echo '<meta http-equiv="refresh" content="0;url=' . $proxy->redirect . '" />';
                        exit;
                    }
                }
            }
        }
    }

    public function spam_protection(){
        $spam = DB::table($this->prefix().'spam-settings')->first();
        $setting = $this->getSetting();

        if(!empty($spam)){
            if($spam->protection == 1){
                $dnsbl_lookup = array();
                $dbs = DB::table($this->prefix().'dnsbl-databases')->get();
                if(!$dbs->isEmpty()){
                    foreach($dbs as $db){
                        $dnsbl_lookup[] = $db->database;
                        $reverse_ip     = implode(".", array_reverse(explode(".", $this->getIP())));

                        foreach ($dnsbl_lookup as $host) {
                            if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
                                
                                $type = "Spammer";
                                
                                //Logging
                                if ($spam->logging == 1) {
                                    $this->psec_logging($type);
                                }
                                
                                //AutoBan
                                if ($spam->autoban == 1) {
                                    $this->psec_autoban($type);
                                }
                                
                                //E-Mail Notification
                                if ($setting->mail_notifications == 1 && $spam->mail == 1) {
                                    $this->psec_mail($type);
                                }
                                
                                if($this->url_match($spam->redirect)){
                                    echo '<meta http-equiv="refresh" content="0;url=' . $spam->redirect . '" />';
                                    exit;
                                }
                            }
                        }

                    }
                }
            }
        }
    }

    public function badbots_protection(){
        $badbot = DB::table($this->prefix().'badbot-settings')->first();
        $setting = $this->getSetting();

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        if(!empty($badbot)){
            if($badbot->protection == 1){
                $bad_bots = array(
                    "Abonti",
                    "aggregator",
                    "almaden",
                    "Anarchie",
                    "ASPSeek",
                    "asterias",
                    "autoemailspider",
                    "Bandit",
                    "BDCbot",
                    "BackWeb",
                    "BatchFTP",
                    "BlackWidow",
                    "BLEXBot",
                    "Bolt",
                    "Buddy",
                    "BuiltBotTough",
                    "Bullseye",
                    "bumblebee",
                    "BunnySlippers",
                    "ca-crawler",
                    "CazoodleBot",
                    "CCBot",
                    "Cegbfeieh",
                    "CheeseBot",
                    "CherryPicker",
                    "ChinaClaw",
                    "CICC",
                    "Collector",
                    "Copier",
                    "CopyRightCheck",
                    "cosmos",
                    "Crescent",
                    "Custo",
                    "DIIbot",
                    "discobot",
                    "DittoSpyder",
                    "DOC",
                    "Download Ninja",
                    "Drip",
                    "DSurf",
                    "EasouSpider",
                    "eCatch",
                    "ecxi",
                    "EmailCollector",
                    "EmailSiphon",
                    "EmailWolf",
                    "EroCrawler",
                    "EirGrabber",
                    "ExtractorPro",
                    "EyeNetIE",
                    "Fasterfox",
                    "FeedBooster",
                    "FlashGet",
                    "Foobot",
                    "FrontPage",
                    "Genieo",
                    "GetRight",
                    "GetSmart",
                    "GetWeb!",
                    "gigabaz",
                    "Go!Zilla",
                    "Go-Ahead-Got-It",
                    "gotit",
                    "Grabber",
                    "GrabNet",
                    "Grafula",
                    "grub-client",
                    "Harvest",
                    "hloader",
                    "httplib",
                    "HMView",
                    "HTTrack",
                    "httpdown",
                    "humanlinks",
                    "IDBot",
                    "id-search",
                    "ieautodiscovery",
                    "InfoNaviRobot",
                    "InterGET",
                    "InternetLinkagent",
                    "IstellaBot",
                    "InternetSeer",
                    "Iria",
                    "IRLbot",
                    "JennyBot",
                    "JetCar",
                    "JustView",
                    "k2spider",
                    "Kenjin Spider",
                    "Keyword Density",
                    "larbin",
                    "LeechFTP",
                    "LexiBot",
                    "lftp",
                    "libWeb",
                    "libwww-perl",
                    "likse",
                    "Link*Sleuth",
                    "LinkextractorPro",
                    "linko",
                    "LinkScan",
                    "LinkWalker",
                    "LNSpiderguy",
                    "lwp-trivial",
                    "Mag-Net",
                    "magpie",
                    "Mata Hari",
                    "MaxPointCrawler",
                    "MegaIndex",
                    "Memo",
                    "MFC_Tear_Sample",
                    "Microsoft URL Control",
                    "MIDown",
                    "MIIxpc",
                    "Mippin",
                    "Missigua Locator",
                    "Mister PiX",
                    "moget",
                    "MSIECrawler",
                    "Navroad",
                    "NearSite",
                    "NetAnts",
                    "NetMechanic",
                    "NetSpider",
                    "NICErsPRO",
                    "Niki-Bot",
                    "Ninja",
                    "NPBot",
                    "Nutch",
                    "Octopus",
                    "Offline Explorer",
                    "Openfind data gathere",
                    "Openfind",
                    "PageGrabber",
                    "panscient.com",
                    "pavuk",
                    "pcBrowser",
                    "PeoplePal",
                    "PHP5.{",
                    "PHPCrawl",
                    "PingALink",
                    "PleaseCrawl",
                    "Pockey",
                    "ProPowerBot",
                    "ProWebWalker",
                    "psbot",
                    "Pump",
                    "Python-urllib",
                    "QueryN Metasearch",
                    "QRVA",
                    "Reaper",
                    "Recorder",
                    "ReGet",
                    "RepoMonkey",
                    "Rippers",
                    "SBIder",
                    "Scooter",
                    "Seeker",
                    "SemrushBot",
                    "SeznamBot",
                    "Siphon",
                    "SISTRIX",
                    "sitecheck.Internetseer.com",
                    "SiteSnagger",
                    "SlySearch",
                    "SmartDownload",
                    "Snake",
                    "SnapPreviewBot",
                    "SpaceBison",
                    "SpankBot",
                    "spanner",
                    "spbot",
                    "Spinn3r",
                    "sproose",
                    "Steeler",
                    "Stripper",
                    "Sucker",
                    "SuperBot",
                    "SuperHTTP",
                    "suzuran",
                    "Szukacz",
                    "tAkeOut",
                    "Teleport",
                    "TeleportPro",
                    "Telesoft",
                    "The Intraformant",
                    "TheNomad",
                    "TightTwatBot",
                    "Titan",
                    "toCrawlUrlDispatcher",
                    "True_Robot",
                    "turingos",
                    "TurnitinBot",
                    "UbiCrawler",
                    "UnisterBot",
                    "URLSpiderPro",
                    "URLy Warning",
                    "Vacuum",
                    "VCI WebViewer VCI WebViewer",
                    "VoidEYE",
                    "webalta",
                    "WebAuto",
                    "Win32",
                    "VCI",
                    "WBSearchBot",
                    "Web Downloader",
                    "Web Image Collector",
                    "WebBandit",
                    "WebCollage",
                    "WebCopier",
                    "WebEMailExtrac",
                    "WebEnhancer",
                    "WebFetch",
                    "WebGo",
                    "WebHook",
                    "WebLeacher",
                    "WebmasterWorldForumBot",
                    "WebMiner",
                    "WebMirror",
                    "WebReaper",
                    "WebSauger",
                    "Website Quester",
                    "Webster Pro",
                    "WebStripper",
                    "WebZip",
                    "Whacker",
                    "Widow",
                    "Wotcard",
                    "Wget",
                    "wsr-agent",
                    "WWW-Collector-E",
                    "WWW-Mechanize",
                    "WWWOFFLE",
                    "x-Tractor",
                    "Xaldon",
                    "Xenu",
                    "Zao",
                    "zermelo",
                    "Zeus",
                    "ZyBORG",
                    "coccoc",
                    "Incutio",
                    "lmspider",
                    "memoryBot",
                    "serf",
                    "uptime files",
                    "craftbot",
                    "Download Demon",
                    "Express WebPictures",
                    "Indy Library",
                    "NetZIP",
                    "Vampire",
                    "Offline",
                    "RealDownload",
                    "Download",
                    "Surfbot",
                    "WebWhacker",
                    "eXtractor",
                    "WebSpider",
                    "archiverloader",
                    "clshttp",
                    "cmswor",
                    "curl",
                    "diavol",
                    "email",
                    "extract",
                    "flicky",
                    "grab",
                    "kmccrew",
                    "miner",
                    "nikto",
                    "planetwork",
                    "pycurl",
                    "python",
                    "scan",
                    "skygrid",
                    "winhttp",
                    "Scanner",
                    "DigExt",
                    "80legs",
                    "Semrush",
                    "Ezooms",
                    "%0A",
                    "%0D",
                    "%27",
                    "%3C",
                    "%3E",
                    "%00",
                    "!susie",
                    "_irc",
                    "_works",
                    "+select+",
                    "+union+",
                    "&lt;?",
                    "3gse",
                    "4all",
                    "4anything",
                    "a1 site",
                    "a_browser",
                    "abac",
                    "abach",
                    "abby",
                    "aberja",
                    "abilon",
                    "abont",
                    "aboutoil",
                    "accept",
                    "accoo",
                    "accoon",
                    "aceftp",
                    "acme",
                    "active",
                    "address",
                    "adopt",
                    "adress",
                    "advisor",
                    "ahead",
                    "aihit",
                    "aipbot",
                    "alarm",
                    "albert",
                    "alek",
                    "alexa toolbar",
                    "alltop",
                    "alma",
                    "alot",
                    "alpha",
                    "america online browser",
                    "amfi",
                    "amfibi",
                    "andit",
                    "anon",
                    "ansearch",
                    "answer",
                    "answerbus",
                    "answerchase",
                    "antivirx",
                    "apollo",
                    "appie",
                    "arach",
                    "arian",
                    "asps",
                    "atari",
                    "atlocal",
                    "atom",
                    "atrax",
                    "atrop",
                    "attrib",
                    "autoh",
                    "autohot",
                    "av fetch",
                    "avsearch",
                    "axod",
                    "axon",
                    "baboom",
                    "baby",
                    "back",
                    "bali",
                    "barry",
                    "basichttp",
                    "batch",
                    "bdfetch",
                    "beat",
                    "beaut",
                    "become",
                    "bee",
                    "beij",
                    "betabot",
                    "biglotron",
                    "bilgi",
                    "binlar",
                    "bison",
                    "bitacle",
                    "bitly",
                    "blaiz",
                    "blitz",
                    "blogl",
                    "blogscope",
                    "blogzice",
                    "bloob",
                    "blow",
                    "bond",
                    "bord",
                    "boris",
                    "bost",
                    "bot.ara",
                    "botje",
                    "botw",
                    "bpimage",
                    "brand",
                    "brok",
                    "broth",
                    "browseabit",
                    "browsex",
                    "bruin",
                    "bsalsa",
                    "bsdseek",
                    "built",
                    "bulls",
                    "bumble",
                    "bunny",
                    "busca",
                    "busi",
                    "buy",
                    "bwh3",
                    "cafek",
                    "cafi",
                    "camel",
                    "cand",
                    "captu",
                    "catch",
                    "ccubee",
                    "cd34",
                    "ceg",
                    "cgichk",
                    "cha0s",
                    "chang",
                    "chaos",
                    "char",
                    "char(",
                    "chase x",
                    "check_http",
                    "checker",
                    "checkonly",
                    "checkpriv",
                    "chek",
                    "chill",
                    "chttpclient",
                    "cipinet",
                    "cisco",
                    "cita",
                    "citeseer",
                    "clam",
                    "claria",
                    "claw",
                    "cloak",
                    "clush",
                    "coast",
                    "code.com",
                    "cogent",
                    "coldfusion",
                    "coll",
                    "collect",
                    "comb",
                    "combine",
                    "commentreader",
                    "common",
                    "comodo",
                    "compan",
                    "conc",
                    "conduc",
                    "contact",
                    "control",
                    "contype",
                    "conv",
                    "copi",
                    "copy",
                    "coral",
                    "corn",
                    "costa",
                    "cowbot",
                    "cr4nk",
                    "craft",
                    "cralwer",
                    "crank",
                    "crap",
                    "crawler0",
                    "crazy",
                    "cres",
                    "cs-cz",
                    "cshttp",
                    "cuill",
                    "curry",
                    "cute",
                    "cz3",
                    "czx",
                    "daily",
                    "daobot",
                    "dark",
                    "daten",
                    "dcbot",
                    "dcs",
                    "dds explorer",
                    "deep",
                    "deps",
                    "detect",
                    "diam",
                    "dillo",
                    "disp",
                    "ditto",
                    "dlc",
                    "doco",
                    "drec",
                    "dsdl",
                    "dsok",
                    "dts",
                    "dumb",
                    "eag",
                    "earn",
                    "earthcom",
                    "easydl",
                    "ebin",
                    "echo",
                    "edco",
                    "egoto",
                    "elnsb5",
                    "emer",
                    "empas",
                    "encyclo",
                    "enfi",
                    "enhan",
                    "enterprise_search",
                    "envolk",
                    "erck",
                    "erocr",
                    "eventax",
                    "evere",
                    "evil",
                    "ewh",
                    "exploit",
                    "expre",
                    "extra",
                    "eyen",
                    "fang",
                    "fastbug",
                    "faxo",
                    "fdse",
                    "feed24",
                    "feeddisc",
                    "feedfinder",
                    "feedhub",
                    "fetch",
                    "filan",
                    "fileboo",
                    "fimap",
                    "find",
                    "firebat",
                    "firedownload",
                    "firefox0",
                    "firs",
                    "flam",
                    "flash",
                    "flexum",
                    "flip",
                    "fly",
                    "fooky",
                    "forum",
                    "forv",
                    "fost",
                    "foto",
                    "foun",
                    "fount",
                    "foxy1;",
                    "free",
                    "friend",
                    "fuck",
                    "fuer",
                    "futile",
                    "fyber",
                    "gais",
                    "galbot",
                    "gbpl",
                    "geni",
                    "geo",
                    "geona",
                    "geth",
                    "getr",
                    "getw",
                    "ggl",
                    "gira",
                    "gluc",
                    "gnome",
                    "goforit",
                    "goldfire",
                    "gonzo",
                    "gosearch",
                    "got-it",
                    "gozilla",
                    "graf",
                    "grub",
                    "grup",
                    "gsa-cra",
                    "gsearch",
                    "gt::www",
                    "guidebot",
                    "guruji",
                    "gyps",
                    "haha",
                    "hailo",
                    "harv",
                    "hash",
                    "hatena",
                    "hax",
                    "head",
                    "helm",
                    "hgre",
                    "hippo",
                    "hmse",
                    "holm",
                    "holy",
                    "hotbar",
                    "hpprint",
                    "httpclient",
                    "httpconnect",
                    "human",
                    "huron",
                    "hverify",
                    "hybrid",
                    "hyper",
                    "iaskspi",
                    "ibm evv",
                    "iccra",
                    "ichiro",
                    "icopy",
                    "ics)",
                    "ie5.0",
                    "ieauto",
                    "iempt",
                    "iexplore.exe",
                    "ilium",
                    "ilse",
                    "iltrov",
                    "indexer",
                    "indy",
                    "ineturl",
                    "infonav",
                    "innerpr",
                    "inspect",
                    "insuran",
                    "intellig",
                    "internet_explorer",
                    "internetx",
                    "intraf",
                    "ip2",
                    "ipsel",
                    "isc_sys",
                    "isilo",
                    "isrccrawler",
                    "isspi",
                    "jady",
                    "jaka",
                    "jam",
                    "jenn",
                    "jiro",
                    "jobo",
                    "joc",
                    "jupit",
                    "just",
                    "jyx",
                    "jyxo",
                    "kash",
                    "kazo",
                    "kbee",
                    "kenjin",
                    "kernel",
                    "keywo",
                    "kfsw",
                    "kkma",
                    "kmc",
                    "know",
                    "kosmix",
                    "krae",
                    "krug",
                    "ksibot",
                    "ktxn",
                    "kum",
                    "labs",
                    "lanshan",
                    "lapo",
                    "leech",
                    "lets",
                    "lexi",
                    "lexxe",
                    "libby",
                    "libcrawl",
                    "libcurl",
                    "libfetch",
                    "linc",
                    "lingue",
                    "linkcheck",
                    "linklint",
                    "linkman",
                    "lint",
                    "list",
                    "litefeeds",
                    "livedoor",
                    "livejournal",
                    "liveup",
                    "lmq",
                    "loader",
                    "locu",
                    "london",
                    "lone",
                    "loop",
                    "lork",
                    "lth_",
                    "lwp",
                    "mac_f",
                    "magi",
                    "magp",
                    "mail.ru",
                    "majest",
                    "mam",
                    "mama",
                    "marketwire",
                    "masc",
                    "mass",
                    "mata",
                    "mcbot",
                    "mecha",
                    "mechanize",
                    "metadata",
                    "metalogger",
                    "metaspin",
                    "metauri",
                    "mete",
                    "mib2.2",
                    "microsoft.url",
                    "microsoft_internet_explorer",
                    "mido",
                    "miggi",
                    "miix",
                    "mindjet",
                    "mindman",
                    "mips",
                    "mira",
                    "mire",
                    "miss",
                    "mist",
                    "mizz",
                    "mlbot",
                    "mlm",
                    "mnog",
                    "moge",
                    "moje",
                    "mooz",
                    "mouse",
                    "mozdex",
                    "mvi",
                    "msie6xpv1",
                    "msproxy",
                    "msrbot",
                    "musc",
                    "mvac",
                    "mwm",
                    "my_age",
                    "myapp",
                    "mydog",
                    "myeng",
                    "myie2",
                    "mysearch",
                    "myurl",
                    "name",
                    "naver",
                    "navr",
                    "near",
                    "netcach",
                    "netcrawl",
                    "netfront",
                    "netinfo",
                    "netmech",
                    "netsp",
                    "netx",
                    "netz",
                    "neural",
                    "neut",
                    "newsbreak",
                    "newsgatorincard",
                    "newsrob",
                    "newt",
                    "ng2",
                    "nice",
                    "nimb",
                    "ninte",
                    "nog",
                    "noko",
                    "nomad",
                    "nuse",
                    "nutex",
                    "nwsp",
                    "obje",
                    "ocel",
                    "octo",
                    "odi3",
                    "oegp",
                    "offby",
                    "omea",
                    "omg",
                    "omhttp",
                    "onfo",
                    "onyx",
                    "openf",
                    "openssl",
                    "openu",
                    "orac",
                    "orbit",
                    "oreg",
                    "osis",
                    "outf",
                    "owl",
                    "p3p_",
                    "page2rss",
                    "pagefet",
                    "pansci",
                    "patw",
                    "pavu",
                    "pb2pb",
                    "pcbrow",
                    "pear",
                    "peer",
                    "pepe",
                    "perfect",
                    "perl",
                    "petit",
                    "phoenix0.",
                    "phras",
                    "picalo",
                    "piff",
                    "pig",
                    "pipe",
                    "pirs",
                    "plag",
                    "planet",
                    "plant",
                    "platform",
                    "playstation",
                    "plesk",
                    "pluck",
                    "plukkie",
                    "poe-com",
                    "poirot",
                    "pomp",
                    "post",
                    "postrank",
                    "powerset",
                    "privoxy",
                    "probe",
                    "program_shareware",
                    "protect",
                    "protocol",
                    "prowl",
                    "proxie",
                    "pubsub",
                    "pulse",
                    "punit",
                    "purebot",
                    "purity",
                    "pyq",
                    "pyth",
                    "query",
                    "quest",
                    "qweer",
                    "radian",
                    "rambler",
                    "ramp",
                    "rapid",
                    "rawdog",
                    "rawgrunt",
                    "reap",
                    "reeder",
                    "refresh",
                    "relevare",
                    "repo",
                    "requ",
                    "request",
                    "rese",
                    "retrieve",
                    "roboz",
                    "rocket",
                    "rogue",
                    "rpt-http",
                    "rsscache",
                    "ruby",
                    "ruff",
                    "rufus",
                    "rv:0.9.7)",
                    "salt",
                    "sample",
                    "sauger",
                    "savvy",
                    "sbcyds",
                    "sblog",
                    "sbp",
                    "scagent",
                    "scej_",
                    "sched",
                    "schizo",
                    "schlong",
                    "schmo",
                    "scorp",
                    "scott",
                    "scout",
                    "scrawl",
                    "screen",
                    "screenshot",
                    "script",
                    "seamonkey",
                    "search17",
                    "searchbot",
                    "searchme",
                    "sega",
                    "semto",
                    "sensis",
                    "seop",
                    "seopro",
                    "sept",
                    "sezn",
                    "seznam",
                    "share",
                    "sharp",
                    "shaz",
                    "shell",
                    "shelo",
                    "sherl",
                    "shim",
                    "shopwiki",
                    "silurian",
                    "simple",
                    "simplepie",
                    "siph",
                    "sitekiosk",
                    "sitescan",
                    "sitevigil",
                    "sitex",
                    "skam",
                    "skimp",
                    "sledink",
                    "slide",
                    "sly",
                    "smag",
                    "smurf",
                    "snag",
                    "snapbot",
                    "snif",
                    "snip",
                    "snoop",
                    "sock",
                    "socsci",
                    "sohu",
                    "solr",
                    "some",
                    "soso",
                    "spad",
                    "span",
                    "sphere",
                    "spin",
                    "spurl",
                    "sputnik",
                    "spyder",
                    "squi",
                    "sqwid",
                    "sqworm",
                    "ssm_ag",
                    "stack",
                    "stamp",
                    "statbot",
                    "state",
                    "steel",
                    "stilo",
                    "strateg",
                    "stress",
                    "strip",
                    "style",
                    "subot",
                    "such",
                    "suck",
                    "sume",
                    "sunos 5.7",
                    "sunrise",
                    "superbro",
                    "supervi",
                    "surf4me",
                    "survey",
                    "susi",
                    "suza",
                    "suzu",
                    "sweep",
                    "swish",
                    "sygol",
                    "synapse",
                    "sync2it",
                    "systems",
                    "tagger",
                    "tagoo",
                    "tagyu",
                    "take",
                    "talkro",
                    "tamu",
                    "tandem",
                    "tarantula",
                    "tcf",
                    "tcs1",
                    "teamsoft",
                    "tecomi",
                    "teesoft",
                    "tencent",
                    "terrawiz",
                    "texnut",
                    "thomas",
                    "tiehttp",
                    "timebot",
                    "timely",
                    "tipp",
                    "tiscali",
                    "tmcrawler",
                    "tmhtload",
                    "tocrawl",
                    "todobr",
                    "tongco",
                    "toolbar; (r1",
                    "topic",
                    "topyx",
                    "torrent",
                    "track",
                    "translate",
                    "traveler",
                    "treeview",
                    "tricus",
                    "trivia",
                    "trivial",
                    "true",
                    "tunnel",
                    "turing",
                    "turnitin",
                    "tutorgig",
                    "twat",
                    "tweak",
                    "twice",
                    "tygo",
                    "ubee",
                    "uchoo",
                    "ultraseek",
                    "unavail",
                    "unf",
                    "universal",
                    "upg1",
                    "urlbase",
                    "urllib",
                    "urly",
                    "user-agent:",
                    "useragent",
                    "usyd",
                    "vagabo",
                    "valet",
                    "vamp",
                    "veri~li",
                    "versus",
                    "vikspi",
                    "virtual",
                    "visual",
                    "void",
                    "voyager",
                    "vsyn",
                    "w0000t",
                    "w3search",
                    "walhello",
                    "walker",
                    "wand",
                    "waol",
                    "watch",
                    "wavefire",
                    "wbdbot",
                    "weather",
                    "web2mal",
                    "web.ima",
                    "webbot",
                    "webcat",
                    "webcor",
                    "webcorp",
                    "webcrawl",
                    "webdat",
                    "webdup",
                    "webind",
                    "webis",
                    "webitpr",
                    "weblea",
                    "webmin",
                    "webmoney",
                    "webp",
                    "webql",
                    "webrobot",
                    "webster",
                    "websurf",
                    "webtre",
                    "webvac",
                    "card card-body bg-lights",
                    "wep_s",
                    "whiz",
                    "win67",
                    "windows-rss",
                    "winht",
                    "winodws",
                    "wish",
                    "wizz",
                    "worio",
                    "works",
                    "worth",
                    "wwwc",
                    "wwwo",
                    "wwwster",
                    "xirq",
                    "y!tunnel",
                    "yacy",
                    "yahoo-mmaudvid",
                    "yahooseeker",
                    "yahooysmcm",
                    "yamm",
                    "yang",
                    "yoono",
                    "yori",
                    "yotta",
                    "yplus ",
                    "ytunnel",
                    "zade",
                    "zagre",
                    "zeal",
                    "zebot",
                    "zerx",
                    "zhuaxia",
                    "zipcode",
                    "zixy",
                    "zmao",
                    "zmeu",
                    "zune",
                    "backdoorbot",
                    "black hole",
                    "blowfish",
                    "botalot",
                    "cherrypicker",
                    "crescent internet toolpak http ole control",
                    "linkscan unix",
                    "mozilla4.0 (compatible; bullseye; windows 95)",
                    "repomonkey bait &amp; tacklev1",
                    "vci webviewer vci webviewer win32",
                    "xenu's",
                    "xenu's link sleuth",
                    "zeus webster pro",
                    "8484_Boston_Project",
                    "#[Ww]eb[Bb]andit",
                    "Abacho",
                    "acontbot",
                    "AdoSpeaker",
                    "ah-ha",
                    "AIBOT",
                    "#almaden",
                    "Amfibibot",
                    "Arachmo",
                    "Arameda",
                    "Arellis",
                    "Argus",
                    "attach",
                    "BecomeBot",
                    "BigCliqueBOT",
                    "Bimbot",
                    "boitho.com-dc",
                    "Bot mailto:craftbot@yahoo.com",
                    "BruinBot",
                    "btbot",
                    "CCGCrawl",
                    "CipinetBot",
                    "citenikbot",
                    "ContextAd Bot",
                    "contextadbot",
                    "ConveraCrawler",
                    "ConveraMultiMediaCrawler",
                    "CostaCider",
                    "CrawlConvera",
                    "CrawlWave",
                    "#Crescent",
                    "CXL-FatAssANT",
                    "DataCha0s",
                    "DataFountains",
                    "Deepindex",
                    "devoll.roscard card-body bg-lightspringcatalog.info/spring-fashion-2003.html8/18/2006",
                    "DiamondBot",
                    "Digger",
                    "DISCo Pump",
                    "DM-Search",
                    "Download Wonder",
                    "Downloader",
                    "Drecombot",
                    "DTAagent",
                    "EnfinBot",
                    "Eule-Robot",
                    "EuripBot",
                    "fantomas",
                    "Favcollector",
                    "Faxobot",
                    "FDM_2.x",
                    "FileHound",
                    "Firefox_1.0.6_kasparek",
                    "Firefox_kastaneta",
                    "First_Browse_of_COnn",
                    "fluffy",
                    "Franklin_Locator",
                    "FyberSpider",
                    "Gaisbot",
                    "GalaxyBot",
                    "gazz",
                    "GenericBot-ax",
                    "genevabot",
                    "GeoBot",
                    "Girafabot",
                    "GOFORITBOT",
                    "GornKer",
                    "GroschoBot",
                    "gsa-crawler",
                    "HappyFunBot",
                    "Healthbot",
                    "holmes",
                    "HooWWWer",
                    "Hotzonu",
                    "htdig",
                    "Html_Link_Validator_",
                    "http_sample",
                    "HttpProxy",
                    "httpunit",
                    "IconSurf",
                    "Iltrovatore-Setaccio",
                    "Image Stripper",
                    "Image Sucker",
                    "#Indy Library",
                    "InfociousBot",
                    "INGRID",
                    "InnerpriseBot",
                    "Internet Ninja",
                    "InternetSeer.com",
                    "intraVnews",
                    "IOneSearch.bot",
                    "ISC_Systems_iRc_Search",
                    "Jakarta_Commons-HttpClient",
                    "Jayde Crawler",
                    "JetBot",
                    "JOC Web Spider",
                    "KakleBot",
                    "Kyluka",
                    "lanshanbot",
                    "LapozzBot",
                    "Link_Valet_Online",
                    "LinkAlarm",
                    "LocalcomBot",
                    "LWP::Simple",
                    "Mac_Finder",
                    "Mackster",
                    "Magnet",
                    "Mass Downloader",
                    "Matrix",
                    "Metaspinner",
                    "Microsoft_URL_Control",
                    "MIDown tool",
                    "Mirago",
                    "Mirror",
                    "Missigua_Locator",
                    "Mnogosearch",
                    "MonkeyCrawl",
                    "Mozilla.*NEWT",
                    "Mozzilla",
                    "MVAClient",
                    "My_WinHTTP_Connection",
                    "NaverBot",
                    "NavissoBot",
                    "Net Vampire",
                    "NetMind-Minder",
                    "NetMonitor",
                    "Networking4all",
                    "Newsgroupreporter_LinkCheck",
                    "NextGenSearchBot",
                    "nicebot",
                    "NimbleCrawler",
                    "NLCrawler",
                    "noxtrumbot",
                    "NuSearch Spider",
                    "NutchCVS",
                    "ObjectsSearch",
                    "Ocelli",
                    "Octora_Beta",
                    "Offline Navigator",
                    "OmniExplorer_Bot",
                    "Omnipelagos",
                    "online link validator",
                    "Openbot",
                    "Orbiter",
                    "OutfoxBot",
                    "page_verifier",
                    "PageBitesHyperBot",
                    "Pajaczek",
                    "Papa Foto",
                    "Patwebbot",
                    "PEAR_HTTP_Request_class",
                    "PEERbot",
                    "PHP_version_tracker",
                    "PhpDig",
                    "pipeLiner",
                    "POE-Component-Client-HTTP",
                    "polybot",
                    "Pompos",
                    "Poodle_predictor",
                    "Pooodle_predictor",
                    "Popdexter",
                    "Port_Huron_Labs",
                    "process",
                    "psbot test for robots.txt",
                    "psycheclone",
                    "PyQuery",
                    "QweeryBot",
                    "RAMPyBot",
                    "Random",
                    "Ranking-Manager",
                    "REL_Link_Checker_Lite",
                    "robschecker",
                    "RRG",
                    "RufusBot",
                    "SandCrawler",
                    "SANSARN",
                    "schibstedsokbot",
                    "#scooter",
                    "Screw-Ball",
                    "Scrubby",
                    "Search-10",
                    "search.ch",
                    "Searchmee!",
                    "SearchSpider",
                    "Seekbot",
                    "Sensis Web Crawler",
                    "Sensis.com.au Web Crawler",
                    "Shim+Bot",
                    "ShunixBot",
                    "shybunnie-engine",
                    "SideWinder",
                    "SiteSpider",
                    "#SlySearch test robots.txt",
                    "sna-",
                    "Snappy",
                    "Snoopy",
                    "sohu-search",
                    "Speed-Meter",
                    "SpeedySpider",
                    "Spinne",
                    "SpokeSpider",
                    "Squid-Prefetch",
                    "SquidClamAV_Redirector",
                    "SquigglebotBot",
                    "StackRambler",
                    "sureseeker",
                    "SurveyBot",
                    "SygolBot",
                    "SynoBot",
                    "Teleport Pro",
                    "TerrawizBot",
                    "ThisIsOurYear_Linkchecker",
                    "thumbshots-de-Bot",
                    "Tkensaku",
                    "topicblogs",
                    "TridentSpider",
                    "troovziBot",
                    "TutorGigBot",
                    "#ua",
                    "unchaos_crawler",
                    "Updated",
                    "URL Spider Pro",
                    "URL Spider SQL",
                    "Vagabondo",
                    "vBSEO_",
                    "VoilaBot",
                    "W3CRobot",
                    "Web Sucker",
                    "Web_Downloader",
                    "webcrawl.net",
                    "WebDataCentreBot",
                    "WebEMailExtrac.*",
                    "WebFindBot",
                    "WebGather",
                    "WebGo IS",
                    "WebIndexer",
                    "Webnavigator",
                    "webPluck",
                    "Website",
                    "Website eXtractor",
                    "card card-body bg-lights_Search_II",
                    "WEP_Search",
                    "WhizBang",
                    "WISEbot",
                    "WWWeasel",
                    "Xaldon WebSpider",
                    "Xenu_Link_Sleuth",
                    "Xombot",
                    "XunBot",
                    "yacybot",
                    "YadowsCrawler",
                    "Yeti",
                    "YodaoBot",
                    "YottaShopping_Bot",
                    "Zatka",
                    "Zealbot",
                    "Zeus.*Webster",
                    "#Zeus_",
                    "ZipppBot",
                    "Alexibot",
                    "Aqua_Products",
                    "b2w",
                    "Bookmark search tool",
                    "Copernic",
                    "dumbot",
                    "FairAd Client",
                    "Flaming AttackBot",
                    "Hatena Antenna",
                    "Iron33",
                    "LinkScan/8.1a Unix",
                    "LinkScan/8.1a Unix User-agent: Kenjin Spider",
                    "Morfeus",
                    "Mozilla/4.0 (compatible; BullsEye; Windows 95)",
                    "Oracle Ultra Search",
                    "PerMan",
                    "Radiation Retriever",
                    "RepoMonkey Bait & Tackle",
                    "searchpreview",
                    "sootle",
                    "toCrawl/UrlDispatcher",
                    "URL Control",
                    "URL_Spider_Pro",
                    "WebmasterWorld Extractor",
                    "Zeus 32297 Webster Pro V2.9 Win32",
                    "Zeus Link Scout",
                    "%",
                    "<?",
                    "1,1,1,",
                    "2icommerce",
                    "ActiveTouristBot",
                    "adressendeutschland",
                    "ADSARobot",
                    "AESOP_com_SpiderMan",
                    "Alligator",
                    "AllSubmitter",
                    "aktuelles",
                    "Akregat",
                    "amzn_assoc",
                    "AnotherBot",
                    "Apexoo",
                    "ASPSe",
                    "ASSORT",
                    "ATHENS",
                    "AtHome",
                    "Atomic_Email_Hunter",
                    "Atomz",
                    "^attach",
                    "autohttp",
                    "BackStreet",
                    "Badass",
                    "BenchMark",
                    "berts",
                    "bew",
                    "big.brother",
                    "Bigfoot",
                    "Biz360",
                    "Black",
                    "Black.Hole",
                    "bladder.fusion",
                    "Blog.Checker",
                    "BlogPeople",
                    "Blogshares.Spiders",
                    "Bloodhound",
                    "bmclient",
                    "Board",
                    "BOI",
                    "boitho",
                    "Bookmark.search.tool",
                    "Boston.Project",
                    "BotRightHere",
                    "Bot.mailto:craftbot@yahoo.com",
                    "botpaidtoclick",
                    "brandwatch",
                    "BravoBrian",
                    "Bropwers",
                    "Browsezilla",
                    "c-spider",
                    "char(32,35)",
                    "charlotte",
                    "Click.Bot",
                    "clipping",
                    "core-project",
                    "cyberalert",
                    "^DA$",
                    "Daum",
                    "Deweb",
                    "Digimarc",
                    "digout4uagent",
                    "DnloadMage",
                    "Doubanbot",
                    "Download.Demon",
                    "Download.Devil",
                    "Download.Wonder",
                    "DreamPassport",
                    "DynaWeb",
                    "e-collector",
                    "EBM-APPLE",
                    "EBrowse",
                    "ecollector",
                    "edgeio",
                    "efp@gmx.net",
                    "Email.Extractor",
                    "EmailSearch",
                    "ESurf",
                    "Eval",
                    "Exact",
                    "EXPLOITER",
                    "FairAd",
                    "Fake",
                    "fastlwspider",
                    "FavOrg",
                    "Favorites.Sweeper",
                    "FDM_1",
                    "FEZhead",
                    "Firefox.2.0",
                    "FlickBot",
                    "flunky",
                    "Foob",
                    "Forex",
                    "Franklin.Locator",
                    "freefind",
                    "FreshDownload",
                    "FSurf",
                    "Gamespy_Arcade",
                    "Get",
                    "Ginxbot",
                    "glx.?v",
                    "Go.Zilla",
                    "^gotit$",
                    "Green.Research",
                    "gvfs",
                    "hack",
                    "hhjhj@yahoo",
                    "HomePageSearch",
                    "HouxouCrawler",
                    "http.generic",
                    "HTTPGet",
                    "HTTPRetriever",
                    "IBM_Planetwide",
                    "iGetter",
                    "Image.Stripper",
                    "Image.Sucker",
                    "imagefetch",
                    "iimds_monitor",
                    "IncyWincy",
                    "Industry.Program",
                    "informant",
                    "InfoTekies",
                    "Ingelin",
                    "InstallShield.DigitalWizard",
                    "Insuran.",
                    "Intelliseek",
                    "Internet.Ninja",
                    "Internet.x",
                    "Irvine",
                    "IUPUI.Research.Bot",
                    "^Java",
                    "java/",
                    "Java(tm)",
                    "JBH.agent",
                    "Jenny",
                    "JetB",
                    "JetC",
                    "jeteye",
                    "Kapere",
                    "KRetrieve",
                    "ksoap",
                    "KWebGet",
                    "Lachesis",
                    "leacher",
                    "LeechGet",
                    "leipzig.de",
                    "libghttp",
                    "libwhisker",
                    "libwww-FM",
                    "LightningDownload",
                    "Link",
                    "Link.Sleuth",
                    "Linkie",
                    "LINKS.ARoMATIZED",
                    "linktiger",
                    "lmcrawler",
                    "looksmart",
                    "lwp-request",
                    "Mac.Finder",
                    "Macintosh;.I;.PPC",
                    "Mail.Sweeper",
                    "MarcoPolo",
                    "mark.blonin",
                    "MarkWatch",
                    "MaSagool",
                    "Mass.Downloader",
                    "mavi",
                    "MCspider",
                    "^Memo",
                    "MEGAUPLOAD",
                    "MetaProducts.Download.Express",
                    "Missauga",
                    "Missigua.Locator",
                    "Missouri.College.Browse",
                    "mkdb",
                    "MMMoCrawl",
                    "Monster",
                    "Monza.Browser",
                    "MOT-MPx220",
                    "mothra/netscan",
                    "MovableType",
                    "Mozi!",
                    "^Mozilla.*Indy",
                    "^Mozilla.*NEWT",
                    "^Mozilla*MSIECrawler",
                    "Mp3Bot",
                    "MS.FrontPage",
                    "MS.?Search",
                    "MSFrontPage",
                    "multithreaddb",
                    "MyFamilyBot",
                    "MyGetRight",
                    "NAMEPROTECT",
                    "NASA.Search",
                    "nationaldirectory",
                    "netattache",
                    "NetCarta",
                    "Netcraft",
                    "netprospector",
                    "NetResearchServer",
                    "Net.Vampire",
                    "newLISP",
                    "NEWT.ActiveX",
                    "^NG",
                    "NIPGCrawler",
                    "Noga",
                    "nogo",
                    "Offline.Explorer",
                    "Offline.Navigator",
                    "OK.Mozilla",
                    "Omni",
                    "OpaL",
                    "OpenTextSiteCrawler",
                    "OrangeBot",
                    "P3P",
                    "PackRat",
                    "PagmIEDownload",
                    "Papa",
                    "Pars",
                    "PECL",
                    "PersonaPilot",
                    "Persuader",
                    "PHP.vers",
                    "PHPot",
                    "Pige",
                    "pigs",
                    "^Ping",
                    "playstarmusic",
                    "Port.Huron",
                    "Program.Shareware",
                    "Progressive.Download",
                    "prospector",
                    "Provider.Protocol.Discover",
                    "Prozilla",
                    "PSurf",
                    "^puf$",
                    "PushSite",
                    "PussyCat",
                    "PuxaRapido",
                    "QuepasaCreep",
                    "Radiation",
                    "RedCarpet",
                    "RedKernel",
                    "relevantnoise",
                    "replacer",
                    "Rover",
                    "Rsync",
                    "RTG30",
                    ".ru)",
                    "SAPO",
                    "ScoutOut",
                    "SearchExpress",
                    "searchhippo",
                    "searchterms",
                    "Second.Street.Research",
                    "Security.Kol",
                    "Serious",
                    "Shai",
                    "Shiretoko",
                    "SickleBot",
                    "sitecheck",
                    "SiteCrawler",
                    "Site.Sniper",
                    "SiteSucker",
                    "Slurpy.Verifier",
                    "So-net",
                    "Spegla",
                    "Sphider",
                    "SpiderBot",
                    "SpiderEngine",
                    "SpiderView",
                    "SQ.Webscanner",
                    "Stamina",
                    "Stanford",
                    "studybot",
                    "sun4m",
                    "SurfWalker",
                    "syncrisis",
                    "TALWinHttpClient",
                    "tarspider",
                    "Tcs/1",
                    "Templeton",
                    "The.Intraformant",
                    "TV33_Mercator",
                    "Twisted.PageGetter",
                    "UCmore",
                    "UdmSearch",
                    "UIowaCrawler",
                    "UMBC",
                    "UniversalFeedParser",
                    "UtilMind",
                    "URL.Control",
                    "urldispatcher",
                    "URLGetFile",
                    "User-Agent",
                    "vayala",
                    "VB_",
                    "Viewer",
                    "visibilitygap",
                    "vobsub",
                    "vspider",
                    "w:PACBHO60",
                    "w3m",
                    "WAPT",
                    "web.by.mail",
                    "Web.Data.Extractor",
                    "Web.Downloader",
                    "Web.Mole",
                    "Web.Sucker",
                    "Web2WAP",
                    "WebaltBot",
                    "WebCapture",
                    "webcraft@bea",
                    "Webclip",
                    "WebCollector",
                    "WebCopy",
                    "WebDav",
                    "webdevil",
                    "webdownloader",
                    "WebEMail",
                    "Webinator",
                    "WebFilter",
                    "WebFountain",
                    "webmole",
                    "webpic",
                    "WebPin",
                    "WebPix",
                    "WebRipper",
                    "Website.eXtractor",
                    "Website.Quester",
                    "WebSnake",
                    "websucker",
                    "webwalk",
                    "WebWasher",
                    "WebWeasel",
                    "WEP.Search.00",
                    "WeRelateBot",
                    "Whack",
                    "WhosTalking",
                    "window.location",
                    "Wildsoft.Surfer",
                    "WinHttpRequest",
                    "WinHTTrack",
                    "Winnie.Poh",
                    "wire",
                    "wisenutbot",
                    "WUMPUS",
                    "Wweb",
                    "WWW-Collector",
                    "WWW.Mechanize",
                    "www.ranks.nl",
                    "^x$",
                    "X12R1",
                    "XGET",
                    "Y!OASIS",
                    "YaDirectBot",
                    "ZBot",
                    "Zyborg",
                    "choppy",
                    "g00g1e",
                    "seekerspider",
                    "siclab",
                    "sqlmap",
                    "turnit",
                    "xxxyy",
                    "youda",
                    "finder",
                    "acapbot",
                    "semalt",
                    "AITCSRobot",
                    "Arachnophilia",
                    "aspider",
                    "AURESYS",
                    "BackRub",
                    "Big Brother",
                    "BizBot",
                    "BSpider",
                    "linklooker",
                    "SafetyNet Robot",
                    "CACTVS Chemistry Spider",
                    "EnigmaBot",
                    "Checkbot"
                );
                
                foreach ($bad_bots as $bot) {
                    if (strpos(strtolower($useragent), strtolower($bot)) !== false) {
                        
                        $type = "Bad Bot";
                        
                        //Logging
                        if ($badbot->logging == 1) {
                            $this->psec_logging($type);
                        }
                        
                        //AutoBan
                        if ($badbot->autoban == 1) {
                            $this->psec_autoban($type);
                        }
                        
                        //E-Mail Notification
                        if ($setting->mail_notifications == 1 && $badbot->mail == 1) {
                            $this->psec_mail($type);
                        }
                        
                        return redirect(route('ps.site.badbot-detected'))->send();
                        exit;
                    }
                }  
            }
        }
    }

    public function fakebots_protection(){
        $badbot = DB::table($this->prefix().'badbot-settings')->first();
        $setting = $this->getSetting();

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        if(!empty($badbot)){
            if($badbot->protection2 == 1){
                @$hostname = strtolower(gethostbyaddr($this->getIP()));

                if (strpos(strtolower($useragent), "googlebot") !== false) {
                    if (strpos($hostname, "googlebot.com") !== false OR strpos($hostname, "google.com") !== false) {
                    } else {
                        
                        $type = "Fake Bot";
                        
                        //Logging
                        if ($badbot->logging == 1) {
                            $this->psec_logging($type);
                        }
                        
                        //AutoBan
                        if ($badbot->autoban == 1) {
                            $this->psec_autoban($type);
                        }
                        
                        //E-Mail Notification
                        if ($setting->mail_notifications == 1 && $badbot->mail == 1) {
                            $this->psec_mail($type);
                        }
                        
                        return redirect(route('ps.site.fakebot-detected'))->send();
                        exit;
                    }
                }

            }
        }
    }

    public function bad_words(){
        $words = DB::table($this->prefix().'bad-words')->get();

        if($words->count() > 0){
            ob_start(function($buffer) use($words){
                return $this->buffer_bad_words($buffer);
            });
            
            $_POST = $this->badwords_checker($_POST);
        }
    }

    public function buffer_bad_words($buffer){
        $words = DB::table($this->prefix().'bad-words')->get();
        $setting = $this->getSetting();

        if($words->count() > 0){
            foreach($words as $word){
                $buffer = str_replace($word->word, $setting->badword_replace, $buffer);
            }

            return $buffer;
        }
    }

    public function badwords_checker($input){
        $words = DB::table($this->prefix().'bad-words')->get();
        $setting = $this->getSetting();
        
        foreach($words as $word){
            $badwords2[] = $word->word;
        }
        
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = badwords_checker($val);
            }
        } else {
            foreach($words as $word){
                $input = str_replace($word->word, $setting->badword_replace, $input);
            }
            $output = $input;
        }
        return @$output;
    }

    public function headers_check(){
        $badbot = DB::table($this->prefix().'badbot-settings')->first();
        $setting = $this->getSetting();

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        if(!empty($badbot)){
            if($badbot->protection3 == 1){
                if (empty($useragent)) {
        
                    $type = "Missing User-Agent header";
                    
                    //Logging
                    if ($badbot->logging == 1) {
                        $this->psec_logging($type);
                    }
                    
                    //AutoBan
                    if ($badbot->autoban == 1) {
                        $this->psec_autoban($type);
                    }
                    
                    //E-Mail Notification
                    if ($setting->mail_notifications == 1 && $badbot->mail == 1) {
                        $this->psec_mail($type);
                    }
                    
                    return redirect(route('ps.site.missing-useragent'))->send();
                }

                if (!filter_var($this->getIP(), FILTER_VALIDATE_IP)) {
        
                    $type = "Invalid IP Address header";
                    
                    //Logging
                    if ($badbot->logging == 1) {
                        $this->psec_logging($type);
                    }
                    
                    //AutoBan
                    if ($badbot->autoban == 1) {
                        $this->psec_autoban($type);
                    }
                    
                    //E-Mail Notification
                    if ($setting->mail_notifications == 1 && $badbot->mail == 1) {
                        $this->psec_mail($type);
                    }
                    
                    return redirect(route('ps.site.invalid-ip'))->send();
                    
                }
            }
        }
    }

    public function adblocker_detector(){
        $adblocker = DB::table($this->prefix().'adblocker-settings')->first();

        if(!empty($adblocker)){
            if($adblocker->detection == 1){
                echo '
                <script type="text/javascript">
                function adBlockDetected() {
                    window.location = "' . $adblocker->redirect . '";
                }

                if(typeof fuckAdBlock !== "undefined" || typeof FuckAdBlock !== "undefined") {
                    adBlockDetected();
                } else {
                    var importFAB = document.createElement("script");
                    importFAB.onload = function() {
                        fuckAdBlock.onDetected(adBlockDetected)
                    };
                    importFAB.onerror = function() {
                        adBlockDetected(); 
                    };
                    importFAB.integrity = "sha256-xjwKUY/NgkPjZZBOtOxRYtK20GaqTwUCf7WYCJ1z69w=";
                    importFAB.crossOrigin = "anonymous";
                    importFAB.src = "https://cdnjs.cloudflare.com/ajax/libs/fuckadblock/3.2.1/fuckadblock.min.js";
                    document.head.appendChild(importFAB);
                }
                </script>
                    ';
            }
        }
    }

    public function live_traffic(){
        
        $setting = $this->getSetting();
        $ip = $this->getIP();

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = '';
        }

        $date = date("d F Y");
        $time = @date("H:i");
        
        if ($setting->live_traffic == 1) {
            
            $cache_file = public_path("/cache/live-traffic/");
            $cache_file2 = public_path("/cache/live-traffic/". $ip .".json");


            if ($this->psec_getcache($ip, $cache_file2) == 'PSEC_NoCache') {
                $url = 'http://api.wipmania.com/' . $ip;
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                @curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
                @$country_code = curl_exec($ch);
                curl_close($ch);
                // Grabs API Response and Caches

                $this->makeCacheFile($cache_file, $cache_file2, $country_code);

            } else {
                @$country_code = $this->psec_getcache($ip, $cache_file);
            }

            $country     = $this->code_to_country($country_code);
            $bot         = 0;
            $uniquev     = 0;
            $request_uri = str_replace("'", '', $_SERVER['REQUEST_URI']);

            $professions = array('.css','.js','.png', '.jpg', '.jpeg', '.js.map', '.json');
        
            $regexp = '/' . implode($professions, '|') . '/';
            
            if (!preg_match($regexp, $request_uri, $matches)) {

                $live_traffic = DB::table($this->prefix().'live-traffic')->select('ip')->where(['ip' => $ip, 'useragent' => $useragent, 'date' => $date])->count();
                if($live_traffic <= 0){
                    $uniquev = 1;
                }

                if (preg_match('/bot|crawl|slurp|spider|rambler|lycos|facebookexternalhit|mediapartners/i', strtolower($useragent))) {
                    $bot = 1;
                }

                $detect = new MobileDetect;
        
                if ($detect->isTablet()) {
                    $device_type = 'Tablet';
                } else if ($detect->isMobile()) {
                    $device_type = 'Mobile';
                } else {
                    $device_type = 'Computer';
                }

                $live_traffic_check = DB::table($this->prefix().'live-traffic')->select('ip')->where(['ip' => $ip, 'useragent' => $useragent, 'request_uri' => $request_uri, 'date' => $date, 'time' => $time])->first();
                if(empty($live_traffic_check)){
                    $agent_data = $this->userAgentData();

                    $data = [
                        'ip'    => $ip,
                        'useragent' =>  $useragent,
                        'browser'   =>  $agent_data['browser'],
                        'browser_code'  =>  $agent_data['browser_code'],
                        'os'    =>  $agent_data['os'],
                        'os_code'    =>  $agent_data['os_code'],
                        'country'   =>  $country,
                        'country_code'  =>  $country_code,
                        'device_type'   =>  $device_type,
                        'request_uri'   =>  $request_uri,
                        'referer'   =>  $agent_data['referer'],
                        'bot'    => $bot,
                        'date'    =>   $date,
                        'time'    => $time,
                        'uniquev' => $uniquev
                    ];

                    DB::table($this->prefix().'live-traffic')->insert($data);
                }
            }
        }


    }

    public function code_to_country($code){
        $code = strtoupper($code);
        
        $countryList = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas the',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island (Bouvetoya)',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros the',
            'CD' => 'Congo',
            'CG' => 'Congo the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FO' => 'Faroe Islands',
            'FK' => 'Falkland Islands (Malvinas)',
            'FJ' => 'Fiji the Fiji Islands',
            'FI' => 'Finland',
            'FR' => 'France, French Republic',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia the',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyz Republic',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'AN' => 'Netherlands Antilles',
            'NL' => 'Netherlands the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal, Portuguese Republic',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia (Slovak Republic)',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia, Somali Republic',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland, Swiss Confederation',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States of America',
            'UM' => 'United States Minor Outlying Islands',
            'VI' => 'United States Virgin Islands',
            'UY' => 'Uruguay, Eastern Republic of',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );
        
        if (!isset($countryList[$code]))
            return "Unknown";
        else
            return $countryList[$code];
    }

    public function makeCacheFile($folder, $file, $content){
        if (! File::exists($folder)) {
            File::makeDirectory($folder);
        }

        try{
            if(!file_exists($file)){
                $myfile = fopen($file, "w+");
                fwrite($myfile, $content);
                fclose($myfile);
            }
        }catch(\Exception $e){

        }
    }

}