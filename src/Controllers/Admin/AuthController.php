<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use DB;
use Auth;

use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class AuthController extends Controller{

    public function __construct(){
        $this->middleware('guest:project-security-admin', ['except' => ['logout']]);
    }

    use Config;

    public function login(Request $request){
        
        if($request->isMethod('post')){

            $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            $ip    = addslashes(htmlentities($_SERVER['REMOTE_ADDR']));
            if ($ip == "::1") {
                $ip = "127.0.0.1";
            }
            @$date = @date("d F Y");
            @$time = @date("H:i");

            $username = $request->username;
            $password = hash('sha256', $request->password);

            if (Auth::guard('project-security-admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $check_login = DB::table($this->prefix().'logins')->where(['username' => $request->username, 'ip' => $ip, 'date' => $date, 'time' => $time, 'successful' => 1])->get();
                if($check_login->isEmpty()){
                    DB::table($this->prefix().'logins')->insert(['username' => $request->username, 'ip' => $ip, 'date' => $date, 'time' => $time, 'successful' => 1]);
                }
                return redirect()->intended(route('ps.admin.dashboard'));
            }else{
                $check_login = DB::table($this->prefix().'logins')->where(['username' => $request->username, 'ip' => $ip, 'date' => $date, 'time' => $time, 'successful' => 0])->get();
                if($check_login->isEmpty()){
                    DB::table($this->prefix().'logins')->insert(['username' => $request->username, 'ip' => $ip, 'date' => $date, 'time' => $time, 'successful' => 0]);
                }
                return redirect()->back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => Lang::get('auth.failed'),
                ]);
            }
        }else{

            $table = $this->prefix()."settings";
            if(\Schema::hasTable($table) == false){
                die("- <strong>Vaults Security</strong> : Please run 'php artisan migrate' first!<br>");
            }

            $users = DB::table($this->prefix()."settings")->get();

            if($users->isEmpty()){
                return redirect(route('ps.admin.install'));
            }
        }

        return view('project-security::admin.auth.login');
    }

    public function addUser(Request $request){

        
        if($request->isMethod('post')){

            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:'.$this->prefix().'settings'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            $id = DB::table($this->prefix()."settings")->insertGetId([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            if($id){
                $user = DB::table($this->prefix()."settings")->find($id);
                return redirect(route('ps.admin.login'))->with('success', 'User added successfully. You can login now.');
            }else{
                return redirect()->back()->with('error', 'Server error occurred. Please try again later!.');
            }
        }

        $table = $this->prefix()."settings";
        if(\Schema::hasTable($table) == false){
            die("- <strong>Vaults Security</strong> : Please run 'php artisan migrate' first!<br>");
        }

        return view('project-security::admin.auth.add-user');
    }

    public function logout(Request $request){
        Auth::guard('project-security-admin')->logout();
        return redirect()->intended(route('ps.admin.login'));
    }
}
