<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Articulo;
class DasboardController extends Controller
{
    public function __invoke(Request $request){
        $mayorStock =  Articulo::select('nombre','stock')->orderBy('stock','desc')->get()->take(10);
        $masCaros =  Articulo::select('nombre','precio')->orderBy('precio','desc')->get()->take(10);
        return['mayorStock'=>$mayorStock,'masCaros'=>$masCaros];
    }
}
