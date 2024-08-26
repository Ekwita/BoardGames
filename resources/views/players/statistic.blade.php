@extends('base')

@section('nav')
    <div class="text-gray-200">
        <a href="{{ route('base') }}">
            <div>Home</div>
        </a>
        <a href="{{ route('players.index') }}">
            <div>Back</div>
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-gray-800 text-gray-200 px-4">
        <div class="font-semibold">{{ $player->player_name }}</div>
        <div>Games: {{ $player->games }} <a href="#"><button>Your
                    games</button></a></p>
            <div>Victories: {{ $player->wins }}</div>
            <div>Deaths: {{ $player->deaths }}</div>
            <div>Best score: {{ $player->best }}</div>
            <div>Total gold: {{ $player->totalgold }}</div>
            <div>Artifact 5: {{ $player->art5 }}</div>
            <div>Artifact 7: {{ $player->art7 }}</div>
            <div>Artifact 10: {{ $player->art10 }}</div>
            <div>Artifact 12: {{ $player->art12 }}</div>
            <div>Artifact 15: {{ $player->art15 }}</div>
            <div>Artifact 17: {{ $player->art17 }}</div>
            <div>Artifact 20: {{ $player->art20 }}</div>
            <div>Artifact 25: {{ $player->art25 }}</div>
            <div>Artifact 30: {{ $player->art30 }}</div>
        </div>
    @endsection
