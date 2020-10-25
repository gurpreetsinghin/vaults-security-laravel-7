<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class BadWordsController extends Controller
{

    use Config;

    public function index(){

        $words = DB::table($this->prefix().'bad-words')->orderBy('id', 'desc')->get();

        return view('project-security::admin.bad-words.index', ['words' => $words]);
    }

    public function add(Request $request){

        $request->validate([
            'word'             => 'required|max:255|unique:'.$this->prefix().'bad-words,word,NULL,id'
        ]);

        try{
            DB::table($this->prefix().'bad-words')->insert(request()->except(['_token']));
            return back()->with('success', 'Bad word has been added!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function replace(Request $request){
        try{
            DB::table($this->prefix().'settings')->where('id', Auth::id())->update(request()->except(['_token']));
            return back()->with('success', 'Bad word has been replaced!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }

    public function delete($id){
        try{
            DB::table($this->prefix().'bad-words')->where('id', $id)->delete();
            return back()->with('success', 'Bad word has been deleted!');
        }catch(\Illuminate\Database\QueryException $ex){
            return back()->with('error', $ex->getMessage());
        }
    }
}
