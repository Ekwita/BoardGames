<script setup>
import { usePage, Link } from '@inertiajs/vue3';

// Pobierz dane z kontekstu strony
const { props } = usePage();

// Zmienne dostępne w szablonie
const player = props.player; // Przypisujemy dane gracza do zmiennej
</script>

<template>
    <div>
        <!-- Nawigacja -->
        <nav class="text-gray-800 mb-4 flex space-x-4">
            <Link :href="route('base')" class="hover:text-white">
            Home
            </Link>
            <Link :href="route('players.index')" class="hover:text-white">
            Back
            </Link>
        </nav>

        <!-- Zawartość gracza -->
        <section class="bg-gray-800 text-gray-200 p-4 rounded-lg shadow-md">
            <h1 class="font-semibold text-xl mb-2">{{ player.player_name }}</h1>
            <div class="mb-2">
                <strong>Games:</strong> {{ player.games }}
                <a href="#" class="text-gray-200 hover:underline">
                    <button class="ml-2 px-2 py-1 bg-gray-700 rounded hover:bg-gray-600">
                        Your games
                    </button>
                </a>
            </div>
            <div class="mb-2"><strong>Victories:</strong> {{ player.wins }}</div>
            <div class="mb-2"><strong>Deaths:</strong> {{ player.deaths }}</div>
            <div class="mb-2"><strong>Best score:</strong> {{ player.best }}</div>
            <div class="mb-2"><strong>Total gold:</strong> {{ player.totalGold }}</div>
            <div class="mb-2"><strong>Artifact 5:</strong> {{ player.art5 }}</div>
            <div class="mb-2"><strong>Artifact 7:</strong> {{ player.art7 }}</div>
            <div class="mb-2"><strong>Artifact 10:</strong> {{ player.art10 }}</div>
            <div class="mb-2"><strong>Artifact 12:</strong> {{ player.art12 }}</div>
            <div class="mb-2"><strong>Artifact 15:</strong> {{ player.art15 }}</div>
            <div class="mb-2"><strong>Artifact 17:</strong> {{ player.art17 }}</div>
            <div class="mb-2"><strong>Artifact 20:</strong> {{ player.art20 }}</div>
            <div class="mb-2"><strong>Artifact 25:</strong> {{ player.art25 }}</div>
            <div class="mb-2"><strong>Artifact 30:</strong> {{ player.art30 }}</div>
        </section>
    </div>
</template>