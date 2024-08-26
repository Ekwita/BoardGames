@extends('base')

@section('title')
    <h2>Liczenie punktów</h2>
@endsection

@section('content')
    <points-calculator></points-calculator>
    <form action="{{ route('games.pointsCalculate') }}" method="post">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @csrf
            @foreach ($players as $player)
                @if ($player !== null)
                    <div class="player_points bg-gray-800 text-gray-200 p-4 rounded-md">
                        <div class="bg-gray-900 p-2 mb-3 rounded">
                            <span class="font-semibold">Name: {{ $player }}</span>
                        </div>
                        <div class="mb-3">
                            <label for="status_{{ $player }}" class="block mb-1">Status</label>
                            <select name="status_{{ $player }}" id="status_{{ $player }}"
                                class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                                <option value="3">Escape</option>
                                <option value="2">Alive</option>
                                <option value="1">Dead</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="artifacts">
                                <label for="art5_{{ $player }}" class="block mb-1">Artifact - 5 points</label>
                                <input type="checkbox" id="art5_{{ $player }}" name="art5_{{ $player }}"
                                    class="mb-2">
                                <label for="art7_{{ $player }}" class="block mb-1">Artifact - 7 points</label>
                                <input type="checkbox" id="art7_{{ $player }}" name="art7_{{ $player }}"
                                    class="mb-2">
                                <label for="art10_{{ $player }}" class="block mb-1">Artifact - 10 points</label>
                                <input type="checkbox" id="art10_{{ $player }}" name="art10_{{ $player }}"
                                    class="mb-2">
                                <label for="art12_{{ $player }}" class="block mb-1">Artifact - 12 points</label>
                                <input type="checkbox" id="art12_{{ $player }}" name="art12_{{ $player }}"
                                    class="mb-2">
                                <label for="art15_{{ $player }}" class="block mb-1">Artifact - 15 points</label>
                                <input type="checkbox" id="art15_{{ $player }}" name="art15_{{ $player }}"
                                    class="mb-2">
                                <label for="art17_{{ $player }}" class="block mb-1">Artifact - 17 points</label>
                                <input type="checkbox" id="art17_{{ $player }}" name="art17_{{ $player }}"
                                    class="mb-2">
                                <label for="art20_{{ $player }}" class="block mb-1">Artifact - 20 points</label>
                                <input type="checkbox" id="art20_{{ $player }}" name="art20_{{ $player }}"
                                    class="mb-2">
                                <label for="art25_{{ $player }}" class="block mb-1">Artifact - 25 points</label>
                                <input type="checkbox" id="art25_{{ $player }}" name="art25_{{ $player }}"
                                    class="mb-2">
                                <label for="art30_{{ $player }}" class="block mb-1">Artifact - 30 points</label>
                                <input type="checkbox" id="art30_{{ $player }}" name="art30_{{ $player }}"
                                    class="mb-2">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gold_{{ $player }}" class="block mb-1">Gold: </label>
                            <input type="number" id="gold_{{ $player }}" name="gold_{{ $player }}"
                                value="0" min="0" max="600"
                                class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                        </div>
                        <div class="mb-3">
                            <label for="tokens_{{ $player }}" class="block mb-1">Tokens: </label>
                            <input type="number" id="tokens_{{ $player }}" name="tokens_{{ $player }}"
                                value="0" min="0" max="600"
                                class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                        </div>
                        <div class="mb-3">
                            <label for="cards_{{ $player }}" class="block mb-1">Cards: </label>
                            <input type="number" id="cards_{{ $player }}" name="cards_{{ $player }}"
                                value="0" min="0" max="600"
                                class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                        </div>
                        <div class="mb-3">
                            <label for="total" class="block mb-1">Total points: </label>
                            <!-- Assuming total points input is for display only -->
                            <input type="number" id="total_{{ $player }}" name="total_{{ $player }}"
                                value="0" readonly class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <button type="submit" class="bg-gray-800 text-gray-200 rounded-md p-2 mt-4">Confirm</button>
    </form>

    <a href="{{ route('base') }}"><button class="bg-gray-800 text-gray-200 rounded-md p-2 mt-4">Cancel</button></a>
@endsection
