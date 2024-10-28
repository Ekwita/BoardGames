<script setup>
import { ref } from 'vue';
import { useForm, usePage, Link } from '@inertiajs/vue3';

const { props } = usePage();

// Dane pobierane z serwera (players i errors)
const players = ref(props.players);
const errors = ref(props.errors || []);

// Formularz z wykorzystaniem useForm z Inertia.js
const form = useForm({
    player_name: '',
});

// Funkcja obsługująca wysłanie formularza
const submit = () => {
    form.post(route('players.store'), {
        onError: (errorMessages) => {
            errors.value = Object.values(errorMessages);
        },
        onSuccess: (response) => { // Zmiana na response
            players.value = response.props.players; // Zaktualizuj listę graczy z odpowiedzi
            form.reset(); // Resetowanie formularza po sukcesie
            errors.value = []; // Resetowanie błędów
        },
    });
};
</script>

<template>
    <div>
        <!-- Nawigacja -->
        <div class="grid grid-cols-3 gap-4 justify-items-center">
            <Link :href="route('base')"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
            Home
            </Link>
            <Link :href="route('games.newGame')"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
            New game
            </Link>
            <Link :href="route('games.index')"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
            Games
            </Link>
        </div>

        <!-- Lista graczy -->
        <div id="player-list" class="mb-4">
            <div
                class="grid grid-cols-3 gap-4 justify-items-center bg-black text-gray-300 px-3 py-2 text-base font-bold">
                <div>Player</div>
                <div>Best score</div>
                <div>Victories</div>
            </div>
            <div v-if="players.length === 0"
                class="bg-gray-800 text-gray-300 text-base font-bold flex justify-center p-2">
                <div>You have not created any player yet.</div>
            </div>
            <template v-else>
                <Link v-for="player in players" :key="player.id" :href="route('players.show', player.id)"
                    class="grid grid-cols-3 gap-4 justify-items-center bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                <div>{{ player.player_name }}</div>
                <div>{{ player.best }}</div>
                <div>{{ player.wins }}</div>
                </Link>
            </template>
        </div>

        <!-- Formularz dodawania nowego gracza -->
        <div class="new-player flex justify-start mt-4">
            <div class="w-full max-w-xs">
                <div class="form-name bg-black text-gray-300 px-3 py-2 text-base font-bold">
                    Add new player
                </div>
                <form @submit.prevent="submit" class="bg-gray-800 p-4">
                    <div class="mb-4">
                        <input id="name" v-model="form.player_name" type="text" placeholder="Player name"
                            class="w-full p-2 bg-gray-700 text-gray-300">
                    </div>
                    <button class="w-full rounded bg-gray-900 text-gray-300 text-base font-bold py-2 hover:bg-gray-700"
                        type="submit">
                        Create
                    </button>
                </form>
            </div>
        </div>

        <!-- Wyświetlanie błędów -->
        <div v-if="errors.length" class="alert alert-danger bg-gray-700 text-gray-300 mt-4 p-4 rounded-md">
            <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
        </div>

        <!-- Powrót do Home -->
        <div class="mt-4">
            <Link :href="route('base')">
            <button class="rounded bg-gray-800 text-gray-300 text-base font-bold py-2 px-4 hover:bg-gray-700">
                Home
            </button>
            </Link>
        </div>
    </div>
</template>