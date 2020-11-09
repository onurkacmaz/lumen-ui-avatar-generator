<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Http\ResponseFactory;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{

    public function index(Request $request){
        try {
            $validate = $this->validate($request, ["name" => "required"]);
        } catch (ValidationException $e) {
            $res = new ResponseFactory();
            return $res->json($e->errors());
        }
    }

}
