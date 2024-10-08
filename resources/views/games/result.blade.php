@extends('base')

@section('title')
    <label for="">
        Winner: {{ $winner }}</label>
@endsection

@section('content')
    <div class="grid grid-cols-4">
        @foreach ($results as $result)
            <div>
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
                    <label for="">Artifacts:
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
                    <label for="">Cards: {{ $result->cards }}</label><br>
                    <label for="">Total: {{ $result->total_points }}</label><br>
                @endif
            </div>
        @endforeach
    </div>
    <br>
    <a href="{{ route('base') }}"><button>End</button></a>
@endsection
