<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class CountryBanController extends Controller
{

    use Config;

    public function index(Request $request){

        $type = $request->has('type') && $request->get('type') != '' ? $request->get('type') : '';

        if($type == 'blacklist'){
            DB::table($this->prefix().'settings')->where('id', Auth::id())->update(['countryban_blacklist' => 1]);
        }elseif($type == 'whitelist'){
            DB::table($this->prefix().'settings')->where('id', Auth::id())->update(['countryban_blacklist' => 0]);
        }

        $setting = DB::table($this->prefix().'settings')->where('id', Auth::id())->first();
        $countries = DB::table($this->prefix().'bans-country')->orderBy('id', 'desc')->get();
        return view('project-security::admin.country-ban.index', ['countries' => $countries, 'type' => $type, 'setting' => $setting]);
    }

    public function add(Request $request){
        $request->validate([
            'country'                 =>  'required|unique:'.$this->prefix().'bans-country,country',
        ],[
            'country.unique'  =>  'This country is already added!'
        ]);

        $post = request()->except(['_token']);
        $post['url']      = strip_tags(addslashes($request->url));

        try{
            DB::table($this->prefix().'bans-country')->insert($post);
            return back()->with('success', 'Country has been added!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function edit(Request $request, $id){
        $country = DB::table($this->prefix().'bans-country')->where('id', $id)->first();

        if($request->isMethod('post')){
            $request->validate([
                'country'                 =>  'required|unique:'.$this->prefix().'bans-country,country,'.$id,
            ],[
                'country.unique'  =>  'This country is already added!'
            ]);

            $post = request()->except(['_token']);
            $post['url']      = strip_tags(addslashes($request->url));
            // dd($request->all());
            try{
                DB::table($this->prefix().'bans-country')->where('id', $id)->update($post);
                return redirect(route('ps.admin.country-ban'))->with('success', 'IP Banned!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        return view('project-security::admin.country-ban.edit', ['country' => $country]);
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'bans-country')->where('id', $id)->delete();
            return back()->with('success', 'Country Deleted!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

}