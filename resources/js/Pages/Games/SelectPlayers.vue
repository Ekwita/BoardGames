<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    players: {
        type: Object,
        default: () => ({
            players: [],
        }),
    },
});

const form = useForm({
    player1: '',
    player2: '',
    player3: '',
    player4: '',
    player5: '',
    player6: '',
});

const submitPlayers = () => {
    form.post(route('games.selectPlayers'), {
        preserveScroll: true,
    });
};

const newPlayerForm = useForm({
    player_name: '',
});

const submitNewPlayer = () => {
    newPlayerForm.post(route('players.store'), {
        onSuccess: () => {
            newPlayerForm.reset();
        },
        preserveScroll: true,
    });
};

// Computed property to track selected players
const selectedPlayers = computed(() => {
    // Create an array of selected player names
    return Object.values(form).filter(player => player);
});

// Computed function to filter players based on selected ones
const availablePlayers = (currentPlayer) => {
    return props.players.players.filter(
        player => !selectedPlayers.value.includes(player.player_name) || player.player_name === currentPlayer
    );
};

</script>

<template>

    <Head title="Select Players" />

    <div class="flex flex-wrap">
        <!-- Player select section  -->
        <div class="basis-1/2 p-4">
            <h3>Player select</h3>
            <form @submit.prevent="submitPlayers">
                <!-- Loop for players select -->
                <div v-for="i in 6" :key="i" class="mb-4">
                    <label :for="'player' + i" class="bg-gray-800 text-white font-medium p-1 block">
                        Player {{ i }}
                    </label>
                    <select v-model="form['player' + i]" :id="'player' + i"
                        class="w-full mt-2 p-2 border border-gray-300 rounded-md">
                        <option value="" selected>none</option>
                        <!-- Filter available players to exclude selected ones -->
                        <option v-for="player in availablePlayers(form['player' + i])" :key="player.player_name"
                            :value="player.player_name">
                            {{ player.player_name }}
                        </option>
                    </select>
                </div>

                <button type="submit"
                    class="rounded-md bg-gray-800 px-4 py-2 text-gray-300 font-medium hover:bg-gray-700 hover:text-white">
                    Next
                </button>
            </form>
        </div>

        <!-- Add new player section -->
        <div class="basis-1/2 p-4">
            <h2>New player</h2>
            <div class="newplayer">
                <h3>Create new player</h3>
                <form @submit.prevent="submitNewPlayer">
                    <div class="mb-4">
                        <label for="name" class="block font-medium">Name</label>
                        <input v-model="newPlayerForm.player_name" id="name" type="text" placeholder="Add player name"
                            class="w-full mt-2 p-2 border border-gray-300 rounded-md" />
                    </div>

                    <button type="submit"
                        class="rounded-md bg-gray-800 px-4 py-2 text-gray-300 font-medium hover:bg-gray-700 hover:text-white">
                        Create
                    </button>
                </form>

                <!-- Validation errors -->
                <div v-if="newPlayerForm.errors.length" class="alert alert-danger mt-4">
                    <ul>
                        <li v-for="(error, index) in Object.values(newPlayerForm.errors)" :key="index">
                            {{ error }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
