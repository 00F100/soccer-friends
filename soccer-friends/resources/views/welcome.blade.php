@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center">Next Match</h2>
            <div class="card shadow-sm">
                <h5 class="card-header text-center bg-success text-black"></h5>
                <div class="card-body">
                    <h2 class="card-title">{{ $soccerMatch->name }}</h2>
                    <p class="card-text">{{ $soccerMatch->date }}</p>
                    <div class="text-center">
                        <h3>Goalkeeper: {{ $soccerMatch->players_confirmed_goalkeeper_count }} / 2</h3>
                        <h3>Players: {{ $soccerMatch->players_confirmed_count }} / {{ $soccerMatch->positions - 2 }}</h3>

                        @if(!$disableCreateTeam)
                        <form action="{{ route('soccer_match_team.create', ['soccerMatch' => $soccerMatch->id]) }}" method="POST">
                            @csrf
                        @endif
                            <button class="btn btn-success" {{ $disableCreateTeam ? 'disabled' : '' }}>Create Teams</button>
                        @if(!$disableCreateTeam)
                        </form>
                        @endif
                        
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    Available Players
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Goalkeeper</th>
                                <th>Confirmed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($soccerMatch->players as $player)
                                <tr>
                                    <td>{{ $player->name }}</td>
                                    <td>{!! $player->goalkeeper ? '&#9917;' : '' !!}</td>
                                    <td>
                                        @if($player->pivot->confirm)
                                        <span><i class="bi bi-check-lg"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$player->pivot->confirm)
                                        <form action="{{ route('soccer_match.confirm', ['soccerMatch' => $soccerMatch->id, 'player' => $player->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-lg"></i> Confirm
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(count($soccerMatchHistories) > 0)
        <div class="col-md-8 mt-5">
            <h2 class="text-center">Previous Matchs</h2>
            @foreach($soccerMatchHistories as $soccerMatchHistory)
            <div class="card shadow-sm">
                <h5 class="card-header text-center bg-primary text-white"></h5>
                <div class="card-body">
                    <h2 class="card-title">{{ $soccerMatchHistory->name }}</h2>
                    <p class="card-text">{{ $soccerMatchHistory->date }}</p>
                    <div class="text-center">
                        <h3>Goalkeeper: {{ $soccerMatchHistory->players_confirmed_goalkeeper_count }} / 2</h3>
                        <h3>Players: {{ $soccerMatchHistory->players_confirmed_count }} / {{ $soccerMatchHistory->positions - 2 }}</h3>
                    </div>
                </div>
                <div class="row container">
                    <div class="col-6">
                        <h5 class="text-center">Team A</h5>
                        <table class="table table-hover">
                            <tbody>
                                @foreach($soccerMatchHistory->teams as $team)
                                    @if($team->side == 'A')
                                    <tr>
                                        <td>{{ $team->player->name }}</td>
                                        <td>{{ $team->level }}</td>
                                        <td>{!! $team->player->goalkeeper ? '&#9917;' : '' !!}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <h5 class="text-center">Team B</h5>
                        <table class="table table-hover">
                            <tbody>
                                @foreach($soccerMatchHistory->teams as $team)
                                    @if($team->side == 'B')
                                    <tr>
                                        <td>{{ $team->player->name }}</td>
                                        <td>{{ $team->level }}</td>
                                        <td>{!! $team->player->goalkeeper ? '&#9917;' : '' !!}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($soccerMatchHistory->teams->filter(function($team) {
                        return $team->side == 'R';
                    })->first())
                    <div class="col-12">
                        <h5 class="text-center">Reserve</h5>
                        <table class="table table-hover">
                            <tbody>
                                @foreach($soccerMatchHistory->teams as $team)
                                    @if($team->side == 'R')
                                    <tr>
                                        <td>{{ $team->player->name }}</td>
                                        <td>{{ $team->level }}</td>
                                        <td>{!! $team->player->goalkeeper ? '&#9917;' : '' !!}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
