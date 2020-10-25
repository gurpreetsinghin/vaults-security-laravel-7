<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;
use Gurpreetsinghin\VaultsSecurity\Traits\Core;

class PagesController extends Controller
{
    use Config, Core;

    protected $setting;

    public function __construct(){
        $this->setting = $this->getSetting();
    }

    public function banned(){
        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Banned')->first();
        $bans = DB::table($this->prefix().'bans')->where('ip', $this->getIP())->first();
        return view('project-security::site.pages.banned', ['page' => $page, 'bans' => $bans, 'setting' => $this->setting]);
    }

    public function bannedCountry(Request $request){

        if(request()->cid){
            $bans_country = DB::table($this->prefix().'bans-country')->where('id', $this->getIP())->first();
        }else{
            $bans_country = [];
        }

        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Banned_Country')->first();
        return view('project-security::site.pages.banned-country', ['page' => $page, 'setting' => $this->setting, 'bans_country' => $bans_country]);
    }

    public function blockedBrowser(){
        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Blocked_Browser')->first();
        return view('project-security::site.pages.blocked-browser', ['page' => $page, 'setting' => $this->setting]);
    }

    public function blockedOs(){
        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Blocked_OS')->first();
        return view('project-security::site.pages.blocked-os', ['page' => $page, 'setting' => $this->setting]);
    }

    public function blockedIsp(){
        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Blocked_ISP')->first();
        return view('project-security::site.pages.blocked-isp', ['page' => $page, 'setting' => $this->setting]);
    }

    public function blockedReferer(){
        $page = DB::table($this->prefix().'pages-layolt')->where('page', 'Blocked_RFR')->first();
        return view('project-security::site.pages.blocked-referer', ['page' => $page, 'setting' => $this->setting]);
    }

    public function badbotDetected(){
        return view('project-security::site.pages.badbot-detected', ['setting' => $this->setting]);
    }

    public function fakebotDetected(){
        return view('project-security::site.pages.fakebot-detected', ['setting' => $this->setting]);
    }

    public function missingUseragent(){
        return view('project-security::site.pages.missing-useragent', ['setting' => $this->setting]);
    }

    public function invalidIp(){
        return view('project-security::site.pages.invalid-ip', ['setting' => $this->setting]);
    }
}
