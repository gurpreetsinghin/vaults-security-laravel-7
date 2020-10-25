<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class IprangeBanController extends Controller
{

    use Config;

    public function index(){
        $bans = DB::table($this->prefix().'bans-ranges')->orderBy('id', 'desc')->get();
        return view('project-security::admin.iprange-ban.index', ['bans' => $bans]);
    }

    public function add(Request $request){
        $request->validate([
            'ip_range'                 =>  'required|unique:'.$this->prefix().'bans-ranges,ip_range',
        ],[
            'ip_range.unique'  =>  'This IP range is already added!'
        ]);

        $post = request()->except(['_token']);
        
        try{
            DB::table($this->prefix().'bans-ranges')->insert($post);
            return back()->with('success', 'IP Range Banned!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function edit(Request $request, $id){
        $ban = DB::table($this->prefix().'bans-ranges')->where('id', $id)->first();

        if($request->isMethod('post')){
            $request->validate([
                'ip_range'                 =>  'required|unique:'.$this->prefix().'bans-ranges,ip_range,'.$id,
            ],[
                'ip_range.unique'  =>  'This IP range is already added!'
            ]);

            $post = request()->except(['_token']);
            // dd($request->all());
            try{
                DB::table($this->prefix().'bans-ranges')->where('id', $id)->update($post);
                return redirect(route('ps.admin.iprange-ban'))->with('success', 'IP Range Banned!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        return view('project-security::admin.iprange-ban.edit', ['ban' => $ban]);
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'bans-ranges')->where('id', $id)->delete();
            return back()->with('success', 'Unbanned successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteAll(){
        try{
            DB::table($this->prefix().'bans-ranges')->truncate();
            return back()->with('success', 'Deleted successfully!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

}