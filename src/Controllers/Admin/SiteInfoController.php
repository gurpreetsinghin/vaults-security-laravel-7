<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RecursiveIteratorIterator;
use \FilesystemIterator;

class SiteInfoController extends Controller
{

    public $siteurl;
    public $response_time;

    public function __construct(){
        $this->siteurl = url('/');
    }

    public function index(){
        
        // if($this->siteCheck($this->siteurl)){
        //     $meta = $this->getMetaData($this->siteurl);
        //     dd($meta);
        // }

        $data['site'] = url('/');

        $inipath = php_ini_loaded_file();

        if ($inipath) {
            $data['iniflp'] = $inipath;
        } else {
            $data['iniflp'] = 'A php.ini file is not loaded';
        }

        $data['zend_version'] = zend_version();
        $data['errorlog_path'] = ini_get('error_log');
        $data['host'] = $this->hostInfo();

        $data['files']  =  $data['folders'] =  $data['images']  =  $data['php']     =  $data['html']    =  $data['css']     =  $data['js']       = 0;
        $dir     = glob("../");
        foreach ($dir as $obj) {
            if (is_dir($obj)) {
                $data['folders']++;
                $scan = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($obj, FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($scan as $file) {
                    if (is_file($file)) {
                        $data['files']++;
                        $exp = explode(".", $file);
                        if (@array_search("png", $exp) || @array_search("jpg", $exp) || @array_search("svg", $exp) || @array_search("jpeg", $exp || @array_search("gif", $exp))) {
                            $data['images']++;
                        } else if (array_search("php", $exp)) {
                            $data['php']++;
                        } else if (array_search("html", $exp) || array_search("htm", $exp)) {
                            $data['html']++;
                        } else if (array_search("css", $exp)) {
                            $data['css']++;
                        } else if (array_search("js", $exp)) {
                            $data['js']++;
                        }
                    } else {
                        $data['folders']++;
                    }
                }
            } else {
                $files++;
            }
        }

        $data['disk_size'] = $this->disk_size();
        $data['extensions'] = [
            'list' => get_loaded_extensions(),
            'count' => count(get_loaded_extensions())
        ];
        $data['serverip']     = getHostByName(getHostName());

        @$version = explode("/", $_SERVER['SERVER_SOFTWARE']);
        @$softNum = explode(" ", $version[1]);
        $data['soft'] = $version[0] . '/' . $softNum[0];

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $data['os'] = 'Windows';
        } elseif (in_array(PHP_OS, ['Linux','FreeBSD', 'OpenBSD', 'NetBSD', 'SunOS', 'Unix', 'Darwin', 'HP-UX', 'IRIX64', 'CYGWIN_NT-5.1', 'GNU', 'DragonFly', 'MSYS_NT-6.1'])){
            $data['os'] = PHP_OS; 
        } else {
            $data['os'] = 'Unknown';
        }

        // dd($data);

        return view('project-security::admin.site-info', ['data' => $data]);
    }

    public function hostInfo(){

        $host_info = [];

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $useragent = 'Mozilla/5.0';
        }
        
        
        $ip  = getHostByName(getHostName());
        $url = 'http://extreme-ip-lookup.com/json/' . $ip;
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        @curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
        @$ipcontent = curl_exec($ch);
        curl_close($ch);
        
        $ip_data = @json_decode($ipcontent);
        if ($ip_data && $ip_data->{'status'} == 'success') {
            $host_info['country'] = $ip_data->{'country'};
            $host_info['isp']     = $ip_data->{'isp'};
        } else {
            $host_info['country'] = "Unknown";
            $host_info['isp']     = "Unknown";
        }
        
        if ($host_info['country'] == '') {
            $host_info['country'] = "Unknown";
        }
        
        if ($host_info['isp'] == '') {
            $host_info['isp'] = "Unknown";
        }

        $host_info['ip'] = $ip;
        
        // $data = $ip . "::" . $country . "::" . $isp . "::";
        return $host_info;
    }

    public function siteCheck($site){
        $curlInit = curl_init($site);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        
        $response         = curl_exec($curlInit);
        $this->response_time = curl_getinfo($curlInit);
        curl_close($curlInit);
        if ($response)
            return true;
        return false;
    }

    public function getMetaData($site_url){

        $metaData = [];

        $vtime = $this->response_time['total_time'];
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_URL, $site_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60');
        curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
        $html = curl_exec($ch);
        curl_close($ch);
        $html = str_ireplace(array(
            "Title",
            "TITLE"
        ), "title", $html);
        $html = str_ireplace(array(
            "Description",
            "DESCRIPTION"
        ), "description", $html);
        $html = str_ireplace(array(
            "Keywords",
            "KEYWORDS"
        ), "keywords", $html);
        $html = str_ireplace(array(
            "Author",
            "AUTHOR"
        ), "author", $html);
        $html = str_ireplace(array(
            "Content",
            "CONTENT"
        ), "content", $html);
        $html = str_ireplace(array(
            "Meta",
            "META"
        ), "meta", $html);
        $html = str_ireplace(array(
            "Name",
            "NAME"
        ), "name", $html);
        
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('title');
        
        $metaData['title'] = $nodes->item(0)->nodeValue;
        
        $metas = $doc->getElementsByTagName('meta');
        
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'description')
                $metaData['description'] = $meta->getAttribute('content');
            if ($meta->getAttribute('name') == 'keywords')
                $metaData['keywords'] = $meta->getAttribute('content');
            if ($meta->getAttribute('name') == 'author')
                $metaData['author'] = $meta->getAttribute('content');
        }
        if ($metaData['title'] == '')
            $metaData['title'] = '<h5><span class="badge badge-secondary">No Title</span></h5>';
        if ($metaData['description'] == '')
            $metaData['description'] = '<h5><span class="badge badge-secondary">No Description</span></h5>';
        if ($metaData['keywords'] == '')
            $metaData['keywords'] = '<h5><span class="badge badge-secondary">No Keywords</span></h5>';
        if ($metaData['author'] == '')
            $metaData['author'] = '<h5><span class="badge badge-secondary">No Author</span></h5>';

        return $metaData;
    }

    public function view_size($size){
        if (!is_numeric($size)) {
            return FALSE;
        } else {
            if ($size >= 1073741824) {
                $size = round($size / 1073741824 * 100) / 100 . " GB";
            } elseif ($size >= 1048576) {
                $size = round($size / 1048576 * 100) / 100 . " MB";
            } elseif ($size >= 1024) {
                $size = round($size / 1024 * 100) / 100 . " KB";
            } else {
                $size = $size . " B";
            }
            return $size;
        }
    }

    public function disk_size(){

        $data = [];

        $directory = '/';
        $data['free'] = disk_free_space($directory);
        $data['total'] = disk_total_space($directory);
        if ($data['free'] === FALSE) {
            $data['free'] = 0;
        }
        if ($data['total'] === FALSE) {
            $data['total'] = 0;
        }
        if ($data['free'] < 0) {
            $data['free'] = 0;
        }
        if ($data['total'] < 0) {
            $data['total'] = 0;
        }
        $data['used'] = $data['total'] - $data['free'];
        $data['free_percent'] = round(100 / ($data['total'] / $data['free']), 2);
        $data['used_percent'] = round(100 / ($data['total'] / $data['used']), 2);

        $data['total'] = $this->view_size($data['total']);
        $data['used'] = $this->view_size($data['used']);
        $data['free'] = $this->view_size($data['free']);

        return $data;
    }
}