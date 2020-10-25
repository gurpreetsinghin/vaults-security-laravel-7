<?php

namespace Gurpreetsinghin\VaultsSecurity\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Gurpreetsinghin\VaultsSecurity\Traits\Config;

class PhpConfigCheckController extends Controller
{
    use Config;

    public function index(){
        return view('project-security::admin.php-config-check.index');
    }
}
