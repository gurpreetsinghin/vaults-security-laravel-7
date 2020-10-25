<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class SpammerLogsController extends Controller
{

    use Config;

    public function index(){
        $logs = DB::table($this->prefix().'logs')->where('type', 'Spammer')->orderBy('id', 'desc')->get();
        return view('project-security::admin.spammer-logs.index', ['logs' => $logs]);
    }

    public function view($id){
        $log = DB::table($this->prefix().'logs')->find($id);
        return view('project-security::admin.spammer-logs.view', ['log' => $log]);
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'logs')->where('id', $id)->delete();
            return redirect(route('ps.admin.spammer-logs'))->with('success', 'Log has been deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'logs')->where('type', 'Spammer')->delete();
            return redirect(route('ps.admin.spammer-logs'))->with('success', 'Logs has been deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }


    public function unbanIp($ip){
        try{
            DB::table($this->prefix().'bans')->where('ip', $ip)->delete();
            return back()->with('success', 'IP has unbanned successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function banIp($ip){
        $post = [
            'ip'       => addslashes(htmlspecialchars($ip)),
            'date'     => date("d F Y"),
            'time'     => date("H:i"),
            'reason'   => "Threat",
            'redirect' => 0,
            'url'      => ""
        ];
    
        if (filter_var($ip, FILTER_VALIDATE_IP)) {

            $check = DB::table($this->prefix().'bans')->where('ip', $ip)->count();
            if($check != 0){
                return back()->with('error', 'IP address is already added!');
            }

            try{
                DB::table($this->prefix().'bans')->insert($post);
                return back()->with('success', 'IP has banned successfully!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }else{
            return back()->with('error', 'IP address is invalid!');
        }
    }
}