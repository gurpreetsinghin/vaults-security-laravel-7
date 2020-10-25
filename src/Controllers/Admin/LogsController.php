<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class LogsController extends Controller
{

    use Config;

    public function index(){
        $logs = DB::table($this->prefix().'logs')->orderBy('id', 'desc')->get();
        return view('project-security::admin.logs.index', ['logs' => $logs]);
    }

    public function view($id){
        $log = DB::table($this->prefix().'logs')->find($id);
        return view('project-security::admin.logs.view', ['log' => $log]);
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'logs')->where('id', $id)->delete();
            return redirect(route('ps.admin.logs'))->with('success', 'Log has been deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'logs')->truncate();
            return redirect(route('ps.admin.logs'))->with('success', 'Logs has been deleted successfully!');
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


        if (strpos($ip, '&&&') !== false) {
            $data = explode('&&&', $ip);
            $ip = $data[0];
            $type = $data[1];
        }else{
            $type = "Threat";
        }

        $post = [
            'ip'       => addslashes(htmlspecialchars($ip)),
            'date'     => date("d F Y"),
            'time'     => date("H:i"),
            'reason'   => $type,
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