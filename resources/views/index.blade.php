@extends('base')

@section('nav')
    <div class="grid grid-cols-3 gap-4 justify-items-center">
        <a href="#">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                New game
            </div>
        </a>
        <a href="#">
            <div class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                Players
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
    Result of the last game
@endsection

@section('footer')
    Footer
@endsection
