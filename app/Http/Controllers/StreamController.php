<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Session;
use Response;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Theme;
use App\Type;
use App\Stream;
use App\Viewer;
use App\ReportCat;
use App\Report;
use App\Countries;
use Illuminate\Support\Facades\Auth;

class StreamController extends Controller
{
    public function __construct(){

    }
    
    /**
     * Display a listing of the actives streams
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->all()){
           if(strlen($request->input("country"))>0 && strlen($request->input("name"))>0 && strlen($request->input("theme"))>0){
                $streamer = User::where('pseudo','=',$request->input("name"))
                            ->where('id_countries','=',$request->input("country"))
                            ->first();
                $streams = Stream::where('streamer_id','=',$streamer->id)
                            ->where('type_id','=',$request->input("theme"))
                            ->where('status','=',1);
                dd($streams);
           }
            
        }else{
            $streams = Stream::where('status', 1)->get();
        }
        
        if(Auth::user()){
            $favorites = Viewer::where('user_id', Auth::user()->id)
                                ->where('is_follower',1)
                                ->get();
            $followed = [];
            foreach($favorites as $favorite)
                $followed[] = $favorite->stream;
        }
        $themes = Theme::all();
        $countries = Countries::all();

        return view('stream.index', compact('streams', 'followed', 'themes','countries'));
    }

    /**
     * Display the specified resource
     * 
     * @param string $pseudo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $pseudo){
        $streamer = User::where('pseudo',$pseudo)
                    ->where('status',1)
                    ->first();        
        if(!$streamer)
            abort(404);
        
        $themes = Theme::all();
        $user = Auth::user();
        if($user){
            $user->token = $request->session()->get('_token');
            $reportCat = ReportCat::all();
            $report = Report::where('victim_id','=',$user->id)
                            ->where('guilty_id','=',$streamer->id)
                            ->where('status','=',1)
                            ->first();
        }
        return view('stream.show', compact('themes','streamer', 'user','reportCat','report'));
    }

    /**
     * Update the stream's title
     * 
     * @param \Illuminate\http\Request $request
     * @return \Illuminate\http\Response
     */
    public function updateStream(Request $request){
        $stream = Stream::where('streamer_id', '=', Auth::user()->id)->first();

        if($stream){
            //Title
            if($request->get('config') == 'title')
                $stream->title = $request->get('value');
            
            //Status
            if($request->get('config') == 'status')
                $stream->status = ($request->get('value') == "true") ? 1 : 0;
            
            //Type
            if($request->get('config') == 'type'){
                $type = Type::where('name', '=', $request->get('value'))->first();
                if($type)
                    $stream->type_id = $type->id;
                else
                    return ["erreur" => "Type selectionné introuvable"];
            }

            $stream->save();
            return ["ok" => "Modification enregistrée"];
        }
        return ["erreur" => "Stream non trouvé"];
    }

    /**
     * Recherche automatique des streams
     */
    public function autocomplete(){
        $term = Input::get('term');
        $results = array();
        
        $queries = User::where('pseudo', 'LIKE', '%'.$term.'%')->take(5)->get();
        
        foreach ($queries as $query)
            $results[] = [ 
                    'avatar' => asset('storage/'.$query->avatar),
                    'value' => $query->pseudo
                ];

        return Response::json($results);
    }
}