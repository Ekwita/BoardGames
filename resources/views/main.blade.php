@extends('base')

@section('nav')
    <div class="grid grid-cols-3 gap-4 justify-items-center">
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
        <a href="{{ route('games.index') }}">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Games
            </div>
        </a>
    </div>
@endsection
@section('content')
    @if (isset($game) && isset($results))
        <div class="title">Last game result: <br></div>
        <div id="winner-name">Winner: {{ $game->winner }}</div>
        @foreach ($results as $result)
            <div name="results">
                <label for=""><strong>Name: {{ $result->player_name }}</strong></label><br>
                <label for="">Status:
                    @switch($result->status)
                        @case(1)
                            Dead
                        @break

                        @case(2)
                            Survived
                        @break

                        @case(3)
                            Escaped
                        @break

                        @default
                    @endswitch
                </label><br>

                @php
                    $points = [5, 7, 10, 12, 15, 17, 20, 25, 30];
                @endphp
                @if ($result->status != 1)
                    <label for="">Artifacts: <br>
                        @foreach ($points as $point)
                            @php
                                $artifact = 'art' . $point;
                            @endphp
                            @if ($result->$artifact != 0)
                                {{ $point }} points <br>
                            @endif
                        @endforeach
                    </label>
                    <label for="">Gold: {{ $result->gold }}</label><br>
                    <label for="">Tokens: {{ $result->tokens }}</label><br>
                    <label for="">Cards:{{ $result->cards }}</label><br>
                    <label for="">Total: {{ $result->total_points }}</label><br>
                @endif
            </div>
        @endforeach
        <strong><label for="">Date: {{ date('d.m.Y', strtotime($game->created_at)) }}</label></strong>
    @else
        You have no games yet.
    @endif
@endsection

@section('footer')
    Footer
@endsection
