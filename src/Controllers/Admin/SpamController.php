<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class SpamController extends Controller
{

    use Config;

    public function spam(Request $request){
        if($request->isMethod('post')){
            $fields = ['protection', 'logging', 'autoban', 'mail'];
            $post = request()->except(['_token']);

            foreach($fields as $field){
                if($request->has($field)){
                    $post[$field] = 1;
                }else{
                    $post[$field] = 0;
                }
            }

            try{
                DB::table($this->prefix().'spam-settings')->where('id', 1)->update($post);

                return back()->with('success', 'Spam settings has been updated!');
            }catch(\Illuminate\Database\QueryException $ex){
                return back()->with('error', $ex->getMessage());
            }
        }

        $spam = DB::table($this->prefix().'spam-settings')->first();
        $databases = DB::table($this->prefix().'dnsbl-databases')->get();

        return view('project-security::admin.spam.spam', ['spam' => $spam, 'databases' => $databases]);
    }

    public function addDb(Request $request){
        try{
            DB::table($this->prefix().'dnsbl-databases')->insert(request()->except(['_token']));
            return back()->with('success', 'Database has been added!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteDb($id){
        try{
            DB::table($this->prefix().'dnsbl-databases')->where('id', $id)->delete();
            return back()->with('success', 'Database has been deleted!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
}
