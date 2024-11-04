<script setup>
import { onMounted, watch, reactive } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    players: {
        type: Object,
        required: true,
    },
    csrfToken: {
        type: String,
        required: true
    },
    actionUrl: {
        type: String,
        required: true
    },
    baseUrl: {
        type: String,
        required: true
    }
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
            playerName: playerName
        };
    });

    watch(
        () => Object.values(playerData),
        () => {
            Object.keys(playerData).forEach(playerId => {
                const artifacts = playerData[playerId].artifacts;

                // Update hasAnyTrueArtifact
                playerData[playerId].hasAnyTrueArtifact = Object.values(artifacts).some(value => value);

                if (!playerData[playerId].hasAnyTrueArtifact) {
                    playerData[playerId].gold = 0;
                    playerData[playerId].tokens = 0;
                    playerData[playerId].cards = 0;
                }

                // Calculate artifact points
                playerData[playerId].artifactsPoints = Object.entries(artifacts)
                    .filter(([key, value]) => value)
                    .reduce((total, [key]) => total + artifactValues[key], 0);

                // Update points
                playerData[playerId].points = totalPointsCalculator(playerId);
            });
        },
        { deep: true }
    );
});

// Function to calculate total points for a player
function totalPointsCalculator(playerId) {
    const { status, artifactsPoints, gold, tokens, cards } = playerData[playerId];
    const statusPoints = status == 3 ? 20 : 0;
    const goldPoints = parseFloat(gold) || 0;
    const tokenPoints = parseFloat(tokens) || 0;
    const cardPoints = parseFloat(cards) || 0;

    return statusPoints + artifactsPoints + goldPoints + tokenPoints + cardPoints;
}

// Function to send data to controller
function submitForm() {
    console.log(playerData);
    document.querySelector('form').submit();
}
</script>

<template>
    <form :action="actionUrl" method="post" @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="hidden" name="_token" :value="csrfToken">
            <div v-for="(player, playerNumber) in players" :key="player.playerId"
                class="player_points bg-gray-800 text-gray-300 p-4 rounded-md">
                <div v-if="playerData[player.playerId]">
                    <div class="bg-gray-900 p-2 mb-3 rounded">
                        <span class="font-semibold">Name: {{ playerData[player.playerId].playerName }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="block mb-1">Total points: </div>
                        {{ playerData[player.playerId].points }}
                    </div>
                    <div class="mb-3">
                        <label :for="'status_' + player.playerId" class="block mb-1">Status</label>
                        <select :name="'status_' + player.playerId" :id="'status_' + player.playerId"
                            v-model="playerData[player.playerId].status"
                            class="w-full p-2 bg-gray-600 text-gray-300 rounded" required>
                            <option value="" disabled>Select a status</option>
                            <option value="3">Escape</option>
                            <option value="2">Alive</option>
                            <option value="1">Dead</option>
                        </select>
                    </div>
                    <div v-if="playerData[player.playerId].status && Number(playerData[player.playerId].status) !== 1">
                        <div class="mb-3">
                            <div class="artifacts">
                                <!-- Artifact - 5 points -->
                                <label :for="'art5_' + player.playerId" class="block mb-1">Artifact - 5 points</label>
                                <input type="checkbox" :id="'art5_' + player.playerId" :name="'art5_' + player.playerId"
                                    class="mb-2" v-model="playerData[player.playerId].artifacts.art5" :value="1">

                                <!-- Artifact - 7 points -->
                                <label :for="'art7_' + player.playerId" class="block mb-1">Artifact - 7 points</label>
                                <input type="checkbox" :id="'art7_' + player.playerId" :name="'art7_' + player.playerId"
                                    class="mb-2" v-model="playerData[player.playerId].artifacts.art7" :value="1">

                                <!-- Artifact - 10 points -->
                                <label :for="'art10_' + player.playerId" class="block mb-1">Artifact - 10 points</label>
                                <input type="checkbox" :id="'art10_' + player.playerId"
                                    :name="'art10_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art10" :value="1">

                                <!-- Artifact - 12 points -->
                                <label :for="'art12_' + player.playerId" class="block mb-1">Artifact - 12 points</label>
                                <input type="checkbox" :id="'art12_' + player.playerId"
                                    :name="'art12_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art12" :value="1">

                                <!-- Artifact - 15 points -->
                                <label :for="'art15_' + player.playerId" class="block mb-1">Artifact - 15 points</label>
                                <input type="checkbox" :id="'art15_' + player.playerId"
                                    :name="'art15_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art15" :value="1">

                                <!-- Artifact - 17 points -->
                                <label :for="'art17_' + player.playerId" class="block mb-1">Artifact - 17 points</label>
                                <input type="checkbox" :id="'art17_' + player.playerId"
                                    :name="'art17_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art17" :value="1">

                                <!-- Artifact - 20 points -->
                                <label :for="'art20_' + player.playerId" class="block mb-1">Artifact - 20 points</label>
                                <input type="checkbox" :id="'art20_' + player.playerId"
                                    :name="'art20_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art20" :value="1">

                                <!-- Artifact - 25 points -->
                                <label :for="'art25_' + player.playerId" class="block mb-1">Artifact - 25 points</label>
                                <input type="checkbox" :id="'art25_' + player.playerId"
                                    :name="'art25_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art25" :value="1">

                                <!-- Artifact - 30 points -->
                                <label :for="'art30_' + player.playerId" class="block mb-1">Artifact - 30 points</label>
                                <input type="checkbox" :id="'art30_' + player.playerId"
                                    :name="'art30_' + player.playerId" class="mb-2"
                                    v-model="playerData[player.playerId].artifacts.art30" :value="1">
                            </div>
                        </div>
                        <div v-if="playerData[player.playerId].hasAnyTrueArtifact">
                            <div class="mb-3 gold">
                                <label :for="'gold_' + player.playerId" class="block mb-1">Gold: </label>
                                <input type="number" :id="'gold_' + player.playerId" :name="'gold_' + player.playerId"
                                    v-model="playerData[player.playerId].gold"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                            <div class="mb-3 tokens">
                                <label :for="'tokens_' + player.playerId" class="block mb-1">Tokens: </label>
                                <input type="number" :id="'tokens_' + player.playerId"
                                    :name="'tokens_' + player.playerId" v-model="playerData[player.playerId].tokens"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                            <div class="mb-3 cards">
                                <label :for="'cards_' + player.playerId" class="block mb-1">Cards: </label>
                                <input type="number" :id="'cards_' + player.playerId" :name="'cards_' + player.playerId"
                                    v-model="playerData[player.playerId].cards"
                                    class="w-full p-2 bg-gray-600 text-gray-300 rounded">
                            </div>
                        </div>
                    </div>
                    <div v-if="playerData[player.playerId].status == 1" class="text-red-500">
                        You are dead!
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="bg-gray-800 text-gray-300 rounded-md py-2 mt-4 hover:bg-gray-700">Confirm</button>
        <Link :href="route('base')"><button
            class="bg-gray-800 text-gray-300 rounded-md py-2 mt-4 hover:bg-gray-700">Home</button></Link>
    </form>
</template>
