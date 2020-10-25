<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class OtherBanController extends Controller
{

    use Config;

    public function index(){
        $bans['isp'] = DB::table($this->prefix().'bans-other')->where('type', 'isp')->orderBy('id', 'desc')->get();
        $bans['browser'] = DB::table($this->prefix().'bans-other')->where('type', 'browser')->orderBy('id', 'desc')->get();
        $bans['os'] = DB::table($this->prefix().'bans-other')->where('type', 'os')->orderBy('id', 'desc')->get();
        $bans['referrer'] = DB::table($this->prefix().'bans-other')->where('type', 'referrer')->orderBy('id', 'desc')->get();
        return view('project-security::admin.other-ban.index', ['bans' => $bans]);
    }

    public function add(Request $request){
        $request->validate([
            'value'                 =>  'required',
        ]);

        $post = request()->except(['_token']);

        $check = DB::table($this->prefix().'bans-other')->where(['value' => $request->value, 'type' => $request->type])->orderBy('id', 'desc')->count();
        
        if($check > 0){
            return back()->with('error', 'There is already such record in the database.');
        }

        try{
            DB::table($this->prefix().'bans-other')->insert($post);
            return back()->with('success', 'Added!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'bans-other')->where('id', $id)->delete();
            return back()->with('success', 'Unbanned successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'bans-other')->truncate();
            return back()->with('success', 'Deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

}