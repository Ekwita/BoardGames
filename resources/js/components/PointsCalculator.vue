<script setup>
import { ref, onMounted, watch, reactive } from 'vue';

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
    Object.keys(props.players).forEach(playerId => {
        const player = props.players[playerId];
        playerData[player] = {
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
            gold: null,
            tokens: null,
            cards: null,
            points: 0,
            artifactsPoints: 0,
            statusPoints: 0,
            hasAnyTrueArtifact: false,
        }
    });

    watch(
        () => Object.values(playerData),
        () => {
            Object.keys(playerData).forEach(player => {
                const artifacts = playerData[player].artifacts;


                // Update hasAnyTrueArtifact
                playerData[player].hasAnyTrueArtifact = Object.values(artifacts).some(value => value);

                //
                if (!playerData[player].hasAnyTrueArtifact) {
                    playerData[player].gold = null;
                    playerData[player].tokens = null;
                    playerData[player].cards = null;
                }
                // Calculate artifact points
                playerData[player].artifactsPoints = Object.entries(artifacts)
                    .filter(([key, value]) => value)
                    .reduce((total, [key]) => total + artifactValues[key], 0);

                // Update points
                playerData[player].points = totalPointsCalculator(player);
            });
        },
        { deep: true });
});

// Function to calculate total points for a player
function totalPointsCalculator(player) {
    const { status, artifactsPoints, gold, tokens, cards } = playerData[player];
    const statusPoints = status == 3 ? 20 : 0;
    const goldPoints = parseFloat(gold) || 0;
    const tokenPoints = parseFloat(tokens) || 0;
    const cardPoints = parseFloat(cards) || 0;

    return statusPoints + artifactsPoints + goldPoints + tokenPoints + cardPoints;
}

// Function to send data to controller
function submitForm() {
    document.querySelector('form').submit();
}

</script>

<template>
    <form :action="actionUrl" method="post" @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="hidden" name="_token" :value="csrfToken">
            <div v-for="player in players" :key="player" class="player_points bg-gray-800 text-gray-200 p-4 rounded-md">
                <div v-if="playerData[player]">
                    <div class="bg-gray-900 p-2 mb-3 rounded">
                        <span class="font-semibold">Name: {{ player }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="block mb-1">Total points: </div>
                        {{ playerData[player].points }}
                    </div>
                    <div class="mb-3">
                        <label :for="'status_' + player" class="block mb-1">Status</label>
                        <select :name="'status_' + player" :id="'status_' + player" v-model="playerData[player].status"
                            class="w-full p-2 bg-gray-600 text-gray-200 rounded" required>
                            <option value="" disabled>Select a status</option>
                            <option value="3">Escape</option>
                            <option value="2">Alive</option>
                            <option value="1">Dead</option>
                        </select>
                    </div>
                    <div v-if="playerData[player].status && Number(playerData[player].status) !== 1">
                        <div class="mb-3">
                            <div class="artifacts">
                                <label :for="'art5_' + player" class="block mb-1">Artifact - 5 points</label>
                                <input type="checkbox" :id="'art5_' + player" :name="'art5_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art5">
                                <label :for="'art7_' + player" class="block mb-1">Artifact - 7 points</label>
                                <input type="checkbox" :id="'art7_' + player" :name="'art7_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art7">
                                <label :for="'art10_' + player" class="block mb-1">Artifact - 10 points</label>
                                <input type="checkbox" :id="'art10_' + player" :name="'art10_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art10">
                                <label :for="'art12_' + player" class="block mb-1">Artifact - 12 points</label>
                                <input type="checkbox" :id="'art12_' + player" :name="'art12_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art12">
                                <label :for="'art15_' + player" class="block mb-1">Artifact - 15 points</label>
                                <input type="checkbox" :id="'art15_' + player" :name="'art15_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art15">
                                <label :for="'art17_' + player" class="block mb-1">Artifact - 17 points</label>
                                <input type="checkbox" :id="'art17_' + player" :name="'art17_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art17">
                                <label :for="'art20_' + player" class="block mb-1">Artifact - 20 points</label>
                                <input type="checkbox" :id="'art20_' + player" :name="'art20_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art20">
                                <label :for="'art25_' + player" class="block mb-1">Artifact - 25 points</label>
                                <input type="checkbox" :id="'art25_' + player" :name="'art25_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art25">
                                <label :for="'art30_' + player" class="block mb-1">Artifact - 30 points</label>
                                <input type="checkbox" :id="'art30_' + player" :name="'art30_' + player" class="mb-2"
                                    v-model="playerData[player].artifacts.art30">
                            </div>
                        </div>
                        <div v-if="playerData[player].hasAnyTrueArtifact">
                            <div class="mb-3 gold">
                                <label :for="'gold_' + player" class="block mb-1">Gold: </label>
                                <input type="number" :id="'gold_' + player" :name="'gold_' + player"
                                    v-model="playerData[player].gold" min="0" max="600"
                                    class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                            </div>
                            <div v-if="playerData[player].gold !== null">
                                <div class="mb-3 tokens">
                                    <label :for="'tokens_' + player" class="block mb-1">Tokens: </label>
                                    <input type="number" :id="'tokens_' + player" :name="'tokens_' + player"
                                        v-model="playerData[player].tokens" min="0" max="600"
                                        class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                                </div>
                                <div v-if="playerData[player].tokens !== null">
                                    <div class="mb-3 cards">
                                        <label :for="'cards_' + player" class="block mb-1">Cards: </label>
                                        <input type="number" :id="'cards_' + player" :name="'cards_' + player"
                                            v-model="playerData[player].cards" min="0" max="600"
                                            class="w-full p-2 bg-gray-600 text-gray-200 rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="playerData[player].status == 1">
                        You are dead!
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="bg-gray-800 text-gray-200 rounded-md p-2 mt-4">Confirm</button>
    </form>
    <a :href="baseUrl"><button class="bg-gray-800 text-gray-200 rounded-md p-2 mt-4">Home</button></a>
</template>