<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Session;
use App\User;
use Illuminate\Support\Facades\Auth;

class StreamController extends Controller
{
    protected $nbPerPage = 4;

    public function __construct(){

    }
    
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(){
		$users = User::all();
        return view('stream.index', compact('users'));
    }

    /**
     * Display the specified resource
     * 
     * @param string $pseudo
     * @return \Illuminate\Http\Response
     */
    public function show($pseudo){
        $user = User::where('pseudo',$pseudo)
                    ->where('status',1)
                    ->first();
        if(!$user)
            abort(404);
        return view('stream.show', compact('user'));
    }

    /**
     * Store a message
     * 
     */
    public function storeMessage($message){
        $user = Auth::user();
        if($user){
            
        }
    }
}