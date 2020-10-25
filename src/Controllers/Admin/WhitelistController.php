<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class WhitelistController extends Controller
{

    use Config;

    public function ip(){
        $ips = DB::table($this->prefix().'ip-whitelist')->orderBy('id', 'desc')->get();
        return view('project-security::admin.whitelist.ip', ['ips' => $ips]);
    }

    public function ipAdd(Request $request){
        $request->validate([
            'ip'                 =>  'required|ipv4|unique:'.$this->prefix().'ip-whitelist,ip',
        ],[
            'ip.unique'  =>  'This IP address id already whitelisted!'
        ]);

        try{
            DB::table($this->prefix().'ip-whitelist')->insert(request()->except(['_token']));
            return back()->with('success', 'IP Whitelisted!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function ipEdit(Request $request, $id){
        $ip = DB::table($this->prefix().'ip-whitelist')->find($id);

        if($request->isMethod('post')){
            $request->validate([
                'ip'                 =>  'required|ipv4|unique:'.$this->prefix().'ip-whitelist,ip,'.$id,
            ],[
                'ip.unique'  =>  'This IP address id already whitelisted!'
            ]);

            try{
                DB::table($this->prefix().'ip-whitelist')->where('id', $id)->update(request()->except(['_token']));
                return redirect(route('ps.admin.whitelist.ip'))->with('success', 'IP whitelist updated Successfully!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }

        }

        return view('project-security::admin.whitelist.ip-edit', ['ip' => $ip]);
    }

    public function ipDelete($id){
        try{
            DB::table($this->prefix().'ip-whitelist')->where('id', $id)->delete();
            return back()->with('success', 'IP Deleted Successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function files(){
        $files = DB::table($this->prefix().'file-whitelist')->orderBy('id', 'desc')->get();
        return view('project-security::admin.whitelist.file', ['files' => $files]);
    }

    public function fileAdd(Request $request){
        $request->validate([
            'path'                 =>  'required|unique:'.$this->prefix().'file-whitelist,path',
        ],[
            'path.unique'  =>  'This path already whitelisted!'
        ]);

        try{
            DB::table($this->prefix().'file-whitelist')->insert(request()->except(['_token']));
            return back()->with('success', 'File Whitelisted!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function fileEdit(Request $request, $id){
        $file = DB::table($this->prefix().'file-whitelist')->find($id);

        if($request->isMethod('post')){
            $request->validate([
                'path'                 =>  'required|unique:'.$this->prefix().'file-whitelist,path,'.$id,
            ],[
                'path.unique'  =>  'This path already whitelisted!'
            ]);

            try{
                DB::table($this->prefix().'file-whitelist')->where('id', $id)->update(request()->except(['_token']));
                return redirect(route('ps.admin.whitelist.file'))->with('success', 'File whitelist updated Successfully!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }

        }

        return view('project-security::admin.whitelist.file-edit', ['file' => $file]);
    }

    public function fileDelete($id){
        try{
            DB::table($this->prefix().'file-whitelist')->where('id', $id)->delete();
            return back()->with('success', 'IP Deleted Successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
}
