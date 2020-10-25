<?php
namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class WarningPagesController extends Controller
{
    use Config;

    public function index(Request $request){

        if($request->isMethod('post')){

            try{
                DB::table($this->prefix().'pages-layolt')->truncate();
                DB::table($this->prefix().'pages-layolt')->insert($request->pages);
                return back()->with('success', 'Warning Pages has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $pages = DB::table($this->prefix().'pages-layolt')->get()->keyBy('page');

        if($pages->isEmpty()){
            $pages = $this->addPages();
        }

        return view('project-security::admin.warning-pages.list', ['pages' => $pages]);
    }

    public function addPages(){
        $pages = [
            ['page' => 'Blocked', 'text' => 'Malicious request was detected'],
            ['page' => 'Banned', 'text' => 'You are banned and you cannot continue to the website'],
            ['page' => 'Proxy', 'text' => 'Access to the website via Proxy, VPN, TOR is not allowed (Disable Browser Data Compression if you have it enabled)'],
            ['page' => 'Spam', 'text' => 'You are in the Blacklist of Spammers and you cannot continue to the website'],
            ['page' => 'Banned_Country', 'text' => 'Sorry, but your country is banned and you cannot continue to the website'],
            ['page' => 'Blocked_Browser', 'text' => 'Access to the website through your Browser is not allowed, please use another Internet Browser'],
            ['page' => 'Blocked_OS', 'text' => 'Access to the website through your Operating System is not allowed'],
            ['page' => 'Blocked_ISP', 'text' => 'Your Internet Service Provider is blacklisted and you cannot continue to the website'],
            ['page' => 'Blocked_RFR', 'text' => 'Your referrer url is blocked and you cannot continue to the website'],
            ['page' => 'AdBlocker', 'text' => 'AdBlocker detected. Please support this website by disabling your AdBlocker'],
        ];

        DB::table($this->prefix().'pages-layolt')->insert($pages);

        $pages = DB::table($this->prefix().'pages-layolt')->get()->keyBy('page');

        return $pages;
    }
}
