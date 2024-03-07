@extends('layouts.app')

@section('content')
<div class="container">
    @if(isset($soccerMatch) && $soccerMatch)
        <h1>Edit Soccer Match</h1>
    @else
        <h1>Create Soccer Match</h1>
    @endif
    @if(isset($soccerMatch) && $soccerMatch)
        <form action="{{ route('soccer_match.update', $soccerMatch->id) }}" method="POST">
    @else
        <form action="{{ route('soccer_match.store') }}" method="POST">
    @endif
        @csrf
        @if(isset($soccerMatch) && $soccerMatch)
            @method('PUT')
        @else
            @method('POST')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Match Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ isset($soccerMatch) && $soccerMatch ? $soccerMatch->name : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Match Date</label>
            <input type="datetime-local" class="form-control" id="date" name="date" value="{{ isset($soccerMatch) && $soccerMatch ? $soccerMatch->date : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="positions" class="form-label">Positions</label>
            <input type="number" class="form-control" id="positions" name="positions" value="{{ isset($soccerMatch) && $soccerMatch ? $soccerMatch->positions : '' }}" required>
        </div>
        <div class="row">
            <h4 class="form-label">Select Players</h4>
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <input type="text" id="allPlayersSearch" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="bi bi-x-circle-fill" id="clearAllPlayersSearchSearchInput" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
                <label for="allPlayers" class="form-label">Available</label>
                <ul id="allPlayers" class="list-group custom-scroll">
                    @foreach ($players as $player)
                        @if(!isset($selectedPlayers) || !in_array($player->id, $selectedPlayers))
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $player->id }}" data-goalkeeper="{{ $player->goalkeeper ? 'true' : 'false' }}">
                            {{ $player->name }}
                            @if ($player->goalkeeper)
                                <span class="ball">&#9917;</span>
                            @endif
                            <button type="button" class="btn btn-primary btn-sm move" data-target="selectedPlayers">Add</button>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <input type="text" id="selectedPlayersSearch" class="form-control mb-2" placeholder="Search">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="bi bi-x-circle-fill" id="clearSelectedPlayersSearchInput" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
                <label for="selectedPlayers" class="form-label">Selected</label>
                <ul id="selectedPlayers" class="list-group custom-scroll">
                    @foreach ($players as $player)
                        @if(isset($selectedPlayers) && in_array($player->id, $selectedPlayers))
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $player->id }}" data-goalkeeper="{{ $player->goalkeeper ? 'true' : 'false' }}">
                            {{ $player->name }}
                            @if ($player->goalkeeper)
                                <span class="ball">&#9917;</span>
                            @endif
                            <button type="button" class="btn btn-danger btn-sm move" data-target="allPlayers">Remove</button>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <input type="hidden" id="selectedPlayerIds" name="players[]" value="">
        </div>

        <a href="{{ route('soccer_match.index') }}" class="btn">Cancel</a>
        <button type="submit" class="btn btn-primary">
            @if(isset($soccerMatch) && $soccerMatch)
                Update
            @else
                Create
            @endif
        </button>
    </form>
</div>

@endsection
