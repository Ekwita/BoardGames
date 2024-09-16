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

    @if (isset($games))
        <div>
            @foreach ($games as $game)
                <label for=""><strong>Winner: {{ $game->winner }}</strong></label>
                <div class="results grid grid-cols-4 gap-4">

                    @foreach ($game->statistics as $statistic)
                        <div class="player">
                            <div>
                                <label for=""> <strong>Name: <a
                                            href="{{ route('players.show', ['player' => $statistic->player_id]) }}">{{ strToUpper($statistic->player_name) }}</a></strong></label>
                            </div>
                            <div class="status">
                                <label for="">Status:
                                    @switch($statistic->status)
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
                                </label>
                            </div>
                            @if ($statistic->status != 1)
                                <div>
                                    <label for="">Artifacts:
                                        <br>
                                        @foreach (range(1, 30) as $number)
                                            @if ($statistic->{'art' . $number} == true)
                                                Artifact for {{ $number }} points.
                                                <br>
                                            @endif
                                        @endforeach
                                    </label>
                                </div>
                                <div>
                                    <label for="">Gold: {{ $statistic->gold }}</label>
                                </div>
                                <div>
                                    <label for="">Tokens: {{ $statistic->tokens }}</label>
                                </div>
                                <div>
                                    <label for="">Cards:{{ $statistic->cards }}</label>
                                </div>
                                <div>
                                    <label for="">Total: {{ $statistic->total_points }}</label>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
                <strong><label for="">Date:
                        {{ $game->createdAt }}</label></strong>
            @endforeach
        </div>
        <div class="">
            <a href="{{ route('base') }}"><button>Home</button></a>
        </div>
        <div class="paginate flex justify-center mt-4">
            <div class="inline-block">
                <div class="pagination">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="results">
            <h3>You have any game yet.</h3>
        </div>
    @endif
@endsection
