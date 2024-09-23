@extends('base')

@section('title')
    <h2>Liczenie punktów</h2>
@endsection

@section('content')
    <div id="app">
        <points-calculator :players="{{ json_encode($players) }}" :csrf-token="'{{ csrf_token() }}'"
            action-url="{{ route('games.pointsCalculate') }}">
        </points-calculator>
    </div>
@endsection
