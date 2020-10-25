<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class LiveTrafficController extends Controller
{

    use Config;

    public function index(){

//         $string = " array.js";
//         $professions = array('.css','.js','.png', '.jpg', '.jpeg', '.js.map', '.json');
        
//         $regexp = '/' . implode($professions, '|') . '/';
        
//         if (preg_match($regexp, $string, $matches)) {
//           print "Found this profession: " . $matches[0] . "\n";
//         }
//         else {
//           print "No matching profession found.\n";
//         }
// exit;
        $setting = DB::table($this->prefix().'settings')->where('id', Auth::id())->first();
        $traffic = DB::table($this->prefix().'live-traffic')->orderBy('id', 'desc')->paginate(10);
        return view('project-security::admin.live-traffic.index', ['traffic' => $traffic, 'setting' => $setting]);
    }

    public function view($id){
        $log = DB::table($this->prefix().'live-traffic')->find($id);
        return view('project-security::admin.live-traffic.view', ['log' => $log]);
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'live-traffic')->truncate();
            return redirect(route('ps.admin.live-traffic'))->with('success', 'Traffic has been deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function changeStatus(Request $request){
        try{
            $live_traffic = $request->has('live_traffic') ? 1 : 0;
            DB::table($this->prefix().'settings')->where('id', Auth::id())->update(['live_traffic' => $live_traffic]);
            return back()->with('success', 'Live Traffic status has been updated successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
}