<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class ipBanController extends Controller
{

    use Config;

    public function index(){
        $bans = DB::table($this->prefix().'bans')->orderBy('id', 'desc')->get();
        return view('project-security::admin.ip-ban.index', ['bans' => $bans]);
    }

    public function add(Request $request){
        $request->validate([
            'ip'                 =>  'required|ipv4|unique:'.$this->prefix().'bans,ip',
        ],[
            'ip.unique'  =>  'This IP address is already banned!'
        ]);

        $post = request()->except(['_token']);
        $post['date']     = date("d F Y");
        $post['time']     = date("H:i");
        $post['reason']   = addslashes(htmlspecialchars($request->reason));
        $post['url']      = addslashes(htmlspecialchars($request->url));
        
        try{
            DB::table($this->prefix().'bans')->insert($post);
            return back()->with('success', 'IP Banned!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function edit(Request $request, $id){
        $ban = DB::table($this->prefix().'bans')->where('id', $id)->first();

        if($request->isMethod('post')){
            $request->validate([
                'ip'                 =>  'required|ipv4|unique:'.$this->prefix().'bans,ip,'.$id,
            ],[
                'ip.unique'  =>  'This IP address is already banned!'
            ]);

            $post = request()->except(['_token', 'date', 'time', 'autoban']);
            $post['date']     = date("d F Y");
            $post['time']     = date("H:i");
            $post['reason']   = addslashes(htmlspecialchars($request->reason));
            $post['url']      = addslashes(htmlspecialchars($request->url));
            // dd($request->all());
            try{
                DB::table($this->prefix().'bans')->where('id', $id)->update($post);
                return redirect(route('ps.admin.ip-ban'))->with('success', 'IP Banned!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        return view('project-security::admin.ip-ban.edit', ['ban' => $ban]);
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'bans')->where('id', $id)->delete();
            return back()->with('success', 'Unbanned successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'bans')->truncate();
            return back()->with('success', 'Deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

}