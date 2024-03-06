@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <h5 class="card-header text-center bg-warning text-black">Próxima Partida</h5>
                <div class="card-body">
                    <h2 class="card-title">{{ $soccerMatch->name }}</h2>
                    <p class="card-text">{{ $soccerMatch->date }}</p>
                    <div class="text-center">
                        <button class="btn btn-success">Gerar Time</button>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    Lista de participantes
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Confirmed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                                @php
                                    $soccerMatches = $player->soccerMatches->first()
                                @endphp
                                <tr>
                                    <td>{{ $player->name }}</td>
                                    <td>{{ $player->level }}</td>
                                    <td>{{ $soccerMatches->pivot->confirm ? 'Yes' : '--' }}</td>
                                    <td>
                                        <form action="{{ route('soccer_match.confirm', ['soccerMatch' => $soccerMatches->id, 'player' => $player->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-lg"></i> Compareceu
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
