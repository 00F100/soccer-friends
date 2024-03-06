@extends('layouts.app')

@section('content')
<div class="container">
    @if($player)
        <h1>Edit Player</h1>
    @else
        <h1>Create Player</h1>
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
    @if($player)
        <form action="{{ route('players.update', $player->id) }}" method="POST">
    @else
        <form action="{{ route('players.store') }}" method="POST">
    @endif
        @csrf
        @if($player)
            @method('PUT')
        @else
            @method('POST')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $player ? $player->name : '' }}" required>
        </div>
        <div class="mb-3 row">
            <div class="col-4">
                <label for="goalkeeper" class="form-label">Goalkeeper</label>
                <select class="form-control" id="goalkeeper" name="goalkeeper">
                    <option value="0" {{ $player && $player->goalkeeper ? '' : 'selected' }}>No</option>
                    <option value="1" {{ $player && $player->goalkeeper ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <div class="col-4">
                <label for="goalkeeper" class="form-label">Level</label>
                <div class="d-flex justify-content-between">
                    <span class="me-2">Bad</span>
                    <input type="range" class="form-range" id="level" name="level" min="1" max="5" value="{{ $player ? $player->level : 3 }}" required style="flex-grow: 1;">
                    <span class="ms-2">Good</span>
                </div>
            </div>
        </div>
        <a href="{{ route('players.index') }}" class="btn">Cancel</a>
        <button type="submit" class="btn btn-primary">
            @if($player)
                Update
            @else
                Create
            @endif
        </button>
    </form>
</div>
@endsection
