@extends('layouts.template')

@section('content')
<div class="row">
    <div class="col-sm-3 gold">
            <div class="top bottom">
                <a href="{{ route('home.index') }}" class="right" style="margin-top: 0px;"> 
                    <i class="far fa-edit text-white"></i>
                </a>
                <br>
                <div class="cadre-style">
                    <center>
                        <img class="resize-img" src="<?php echo asset('storage/'.Auth::user()->avatar); ?>" alt="Image de profil" title="Image de profil">
                    </center> 
                </div>
                <p>
                    <center>{{ Auth::user()->pseudo }}</center>
                    <center>
                        <i class="material-icons" style="font-size: 16px;">location_on</i>{{ Auth::user()->country->name }}
                        <img style="width:10%" src="{{ Auth::user()->country->svg }}">
                    </center>
                </p>
                <center>
                    <ul class="navbar-nav">
                        <li  class="nav-item"><a class="text-white"  href="#">Mes abonnés</a></li>
                        <li  class="nav-item"><a class="text-white"  href="#">Mes revenus</a></li>
                        <li  class="nav-item"><a class="text-white"  href="#">Mes activités</a></li>
                    </ul>
                    <a class="machaine active" href="{{ route('stream.show', ['user' => Auth::user()->pseudo]) }}">Ma chaine                    
                                        <i class="far fa-play-circle text-white"></i>
                    </a>

                </center>
            </div>
    </div>
    <div class="col-sm-9 pull-right top bottom">
    <thead>
        <tr>
            <th class="text-center" colspan="4">Dons reçus</th>
        </tr>
        <tr>
            <th>Utilisateur</th>
            <th>Montant</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @if(count($donations) > 0)
            @foreach($donations as $donation)
                <tr>
                    <td>{{$donation->viewer->stream->user->pseudo}}</td>
                    <td>{{$donation->amount}} €</td>
                    <td>{{$donation->message}}</td>
                    <td>{{ Carbon\Carbon::parse($donation->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">
                    <i>Vous n'avez reçu aucun don pour l'instant.</i>
                </td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>
@endsection