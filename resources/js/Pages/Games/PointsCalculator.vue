<script setup>
import { reactive, watch, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    players: {
        type: Object,
        required: true,
    },
});

const artifactValues = {
    art5: 5,
    art7: 7,
    art10: 10,
    art12: 12,
    art15: 15,
    art17: 17,
    art20: 20,
    art25: 25,
    art30: 30,
};

const playerData = reactive({});
const form = useForm({ players: {} });

onMounted(() => {
    Object.keys(props.players).forEach(playerNumber => {
        const playerId = props.players[playerNumber].playerId;
        const playerName = props.players[playerNumber].playerName;

        playerData[playerId] = {
            status: '',
            artifacts: {
                art5: false,
                art7: false,
                art10: false,
                art12: false,
                art15: false,
                art17: false,
                art20: false,
                art25: false,
                art30: false,
            },
            gold: 0,
            tokens: 0,
            cards: 0,
            points: 0,
            artifactsPoints: 0,
            statusPoints: 0,
            hasAnyTrueArtifact: false,
            playerName: playerName,
        };
    });

    form.players = playerData;

    watch(
        () => Object.values(form.players),
        () => {
            Object.keys(form.players).forEach(playerId => {
                const artifacts = form.players[playerId].artifacts;

                // Update hasAnyTrueArtifact
                form.players[playerId].hasAnyTrueArtifact = Object.values(artifacts).some(value => value);

                if (!form.players[playerId].hasAnyTrueArtifact) {
                    form.players[playerId].gold = 0;
                    form.players[playerId].tokens = 0;
                    form.players[playerId].cards = 0;
                }

                // Calculate artifact points
                form.players[playerId].artifactsPoints = Object.entries(artifacts)
                    .filter(([key, value]) => value)
                    .reduce((total, [key]) => total + artifactValues[key], 0);

                // Update points
                form.players[playerId].points = totalPointsCalculator(playerId);
            });
        },
        { deep: true }
    );
});

function totalPointsCalculator(playerId) {
    const { status, artifactsPoints, gold, tokens, cards } = form.players[playerId];
    const statusPoints = status == 3 ? 20 : 0;
    const goldPoints = parseFloat(gold) || 0;
    const tokenPoints = parseFloat(tokens) || 0;
    const cardPoints = parseFloat(cards) || 0;

    return statusPoints + artifactsPoints + goldPoints + tokenPoints + cardPoints;
}

function submitForm() {
    form.post(route('games.pointsCalculate')); // Route name example
}
</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="(player, playerNumber) in players" :key="player.playerId"
                class="player_points bg-gray-800 text-gray-300 p-4 rounded-md">
                <div v-if="form.players[player.playerId]">
                    <div class="bg-gray-900 p-2 mb-3 rounded">
                        <span class="font-semibold">Name: {{ form.players[player.playerId].playerName }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="block mb-1">Total points: </div>
                        {{ form.players[player.playerId].points }}
                    </div>
                    <div class="mb-3">
                        <label :for="'status_' + player.playerId" class="block mb-1">Status</label>
                        <select :id="'status_' + player.playerId" v-model="form.players[player.playerId].status"
                            class="w-full p-2 bg-gray-600 text-gray-300 rounded" required>
                            <option value="" disabled>Select a status</option>
                            <option value="3">Escape</option>
                            <option value="2">Alive</option>
                            <option value="1">Dead</option>
                        </select>
                    </div>
                    <div
                        v-if="form.players[player.playerId].status && Number(form.players[player.playerId].status) !== 1">
                        <div class="mb-3">
                            <div class="artifacts">
                                <div v-for="(value, key) in artifactValues" :key="key" class="mb-2">
                                    <label :for="key + '_' + player.playerId" class="block mb-1">
                                        Artifact - {{ value }} points
                                    </label>
                                    <input type="checkbox" :id="key + '_' + player.playerId"
                                        v-model="form.players[player.playerId].artifacts[key]" :value="1">
                                </div>
                            </div>
                        </div>
                        <div v-if="form.players[player.playerId].hasAnyTrueArtifact">
                            <div class="mb-3 gold">
                                <label :for="'gold_' + player.playerId" class="block mb-1">Gold: </label>
                                <input type="number" :id="'gold_' + player.playerId"
                                    v-model="form.players[player.playerId].gold"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                            <div class="mb-3 tokens">
                                <label :for="'tokens_' + player.playerId" class="block mb-1">Tokens: </label>
                                <input type="number" :id="'tokens_' + player.playerId"
                                    v-model="form.players[player.playerId].tokens"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                            <div class="mb-3 cards">
                                <label :for="'cards_' + player.playerId" class="block mb-1">Cards: </label>
                                <input type="number" :id="'cards_' + player.playerId"
                                    v-model="form.players[player.playerId].cards"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                        </div>
                    </div>
                    <div v-if="form.players[player.playerId].status == 1" class="text-red-500">
                        You are dead!
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="bg-gray-800 text-gray-300 rounded-md py-2 mt-4 hover:bg-gray-700">Confirm</button>
        <Link :href="route('base')">
        <button type="button" class="bg-gray-800 text-gray-300 rounded-md py-2 mt-4 hover:bg-gray-700">Home</button>
        </Link>
    </form>
</template>
