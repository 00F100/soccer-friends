@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Soccer Match</h1>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>
                    <a href="{{ route('soccer_match.index', [
                        'sort' => 'name',
                        'order' => request('sort') == 'name' && request('order', 'asc') == 'asc' ? 'desc' : 'asc',
                        'page' => request('page', 1),
                        'perPage' => request('perPage', 10)
                    ]) }}">
                        Name
                        @if (request('sort') == 'name')
                            @if (request('order', 'asc') == 'asc')
                                <i class="bi bi-arrow-down-short"></i>
                            @else
                                <i class="bi bi-arrow-up-short"></i>
                            @endif
                        @else
                            <i class="bi bi-dash-lg"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('soccer_match.index', [
                        'sort' => 'positions',
                        'order' => request('sort') == 'positions' && request('order', 'asc') == 'asc' ? 'desc' : 'asc',
                        'page' => request('page', 1),
                        'perPage' => request('perPage', 10)
                    ]) }}">
                        Positions
                        @if (request('sort') == 'positions')
                            @if (request('order', 'asc') == 'asc')
                                <i class="bi bi-arrow-down-short"></i>
                            @else
                                <i class="bi bi-arrow-up-short"></i>
                            @endif
                        @else
                            <i class="bi bi-dash-lg"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('soccer_match.index', [
                        'sort' => 'date',
                        'order' => request('sort', 'date') == 'date' && request('order', 'asc') == 'asc' ? 'desc' : 'asc',
                        'page' => request('page', 1),
                        'perPage' => request('perPage', 10)
                    ]) }}">
                        Date
                        @if (request('sort', 'date') == 'date')
                            @if (request('order', 'asc') == 'asc')
                                <i class="bi bi-arrow-down-short"></i>
                            @else
                                <i class="bi bi-arrow-up-short"></i>
                            @endif
                        @else
                            <i class="bi bi-dash-lg"></i>
                        @endif
                    </a>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($soccerMatches) == 0)
            <tr>
                <td colspan="4">No Records</td>
            </tr>
            @endif
            @foreach ($soccerMatches as $soccerMatch)
            <tr>
                <td>{{ $soccerMatch->name }}</td>
                <td>{{ $soccerMatch->players_selected }}/{{ $soccerMatch->positions }}</td>
                <td>{{ $soccerMatch->date }}</td>
                <td>
                    @if(!$soccerMatch->soccerMatchesTeam()->exists())
                    <a href="{{ route('soccer_match.update', $soccerMatch->id) }}" class="me-2"><i class="bi bi-pencil-square"></i></a>
                    @endif

                    @if(!$soccerMatch->soccerMatchesTeam()->exists() && !$soccerMatch->players()->exists())
                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal{{ $soccerMatch->id }}"><i class="bi bi-trash-fill"></i></a>
                    @endif

                    <div class="modal fade" id="deleteConfirmationModal{{ $soccerMatch->id }}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this Soccer Match "{{ $soccerMatch->name }}"?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('soccer_match.destroy', $soccerMatch->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="page" value="{{ request('page') }}">
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                        <input type="hidden" name="order" value="{{ request('order') }}">
                                        <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mb-3">
        <div class="col-11">
        {{ $soccerMatches->appends(['sort' => request('sort'), 'order' => request('order'), 'perPage' => request('perPage')])->links() }}
        {{ $totalSoccerMatches }}  soccer matches
        </div>
        <div class="col-1">
            <form action="{{ route('soccer_match.index') }}" method="GET">
                <select name="perPage" class="form-select" onchange="this.form.submit()">
                    <option value="5"{{ request('perPage') == 5 ? ' selected' : '' }}>5</option>
                    <option value="10"{{ !request('perPage') || request('perPage') == 10 ? ' selected' : '' }}>10</option>
                    <option value="15"{{ request('perPage') == 15 ? ' selected' : '' }}>15</option>
                    <option value="20"{{ request('perPage') == 20 ? ' selected' : '' }}>20</option>
                    <option value="50"{{ request('perPage') == 50 ? ' selected' : '' }}>50</option>
                    <option value="100"{{ request('perPage') == 100 ? ' selected' : '' }}>100</option>
                </select>
                <input type="hidden" name="sort" value="{{ request('sort', 'date') }}">
                <input type="hidden" name="order" value="{{ request('order', 'asc') }}">
            </form>
        </div>
    </div>
</div>

@endsection
