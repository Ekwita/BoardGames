@extends('base')
@section('nav')
    <div class="grid grid-cols-3 gap-4 justify-items-center">
        <a href="#">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Home
            </div>
        </a>
        <a href="#">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                New game
            </div>
        </a>
        <a href="#">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Games
            </div>
        </a>
    </div>
@endsection

@section('content')
    <div id="player-list" class="mb-4">
        <div class="grid grid-cols-3 gap-4 justify-items-center bg-black text-gray-300 px-3 py-2 text-base font-bold">
            <div class="">Player</div>
            <div class="">Best score</div>
            <div class="">Victories</div>
        </div>
        @
    </div>

    <div class="new-player flex justify-start mt-4">
        <div class="w-full max-w-xs">
            <div class="form-name bg-black text-gray-300 px-3 py-2 text-base font-bold">
                Add new player
            </div>
            <form action="" method="post" class="bg-gray-800 p-4">
                @csrf
                <div class="mb-4">
                    <input id="name" type="text" name="player_name" placeholder="Player name"
                        class="w-full p-2 bg-gray-700 text-gray-300">
                </div>
                <button class="w-full rounded bg-gray-900 text-gray-300 text-base font-bold py-2 hover:bg-gray-700"
                    type="submit">Create</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="#">
            <button class="rounded bg-gray-800 text-gray-300 text-base font-bold py-2 px-4 hover:bg-gray-700">
                Home
            </button>
        </a>
    </div>
@endsection
