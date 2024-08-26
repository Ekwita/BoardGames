@extends('base')


@section('nav')
    <div class="grid grid-cols-3 gap-4 justify-items-center">
        <a href="{{ route('base') }}">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Home
            </div>
        </a>
        <a href="{{ route('games.newGame') }}">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                New game
            </div>
        </a>
        <a href="{{ route('players.index') }}">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Players
            </div>
        </a>
    </div>
@endsection


@section('content')
    @if (1 == 1)
        @if (isset($games))
            <div>
            </div>
            <strong><label for="">Date: </label></strong>
        @else
            <strong>Error! No data!</strong>
        @endif
        <div class="">
            <a href="#"><button>Home</button></a>
        </div>
        <div class="paginate flex justify-center mt-4">
            <div class="inline-block">
                <div class="pagination">
                    Pagination
                </div>
            </div>
        </div>
    @else
        <div class="results">
            <h3>You have any game yet.</h3>
        </div>
    @endif
@endsection
