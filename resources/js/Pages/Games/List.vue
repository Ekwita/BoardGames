<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    games: {
        type: Object,
    },
})

console.log(props);

const games = ref(props.games.data);
const gamesPagination = ref(props.games);

const statusText = (status) => {
    switch (status) {
        case 1:
            return 'Dead';
        case 2:
            return 'Survived';
        case 3:
            return 'Escaped';
        default:
            return '';
    }
};

const artifactNumbers = [5, 7, 10, 12, 15, 17, 20, 25, 30];



</script>

<template>
    <div>
        <div v-if="games && games.length > 0">
            <div v-for="game in games" :key="game.gameId" class="mb-4">
                <label class="text-gray-300 font-bold">Winner: {{ game.winner }}</label>
                <div class="results grid grid-cols-4 gap-4 bg-gray-800 p-4 rounded-md">
                    <div v-for="statistic in game.statistics" :key="statistic.player_id" class="player text-gray-300">
                        <div class="font-bold">
                            <label>Name: <a :href="route('players.show', { player: statistic.player_id })"
                                    class="hover:underline">{{ statistic.player_name.toUpperCase() }}</a></label>
                        </div>
                        <div class="status">
                            <label>Status: {{ statusText(statistic.status) }}</label>
                        </div>
                        <div v-if="statistic.status !== 1">
                            <div>
                                <label>Artifacts:<br>
                                    <span v-for="number in artifactNumbers" :key="number">
                                        <template v-if="statistic['art' + number]">
                                            Artifact for {{ number }} points.<br>
                                        </template>
                                    </span>
                                </label>
                            </div>
                            <div>
                                <label>Gold: {{ statistic.gold }}</label>
                            </div>
                            <div>
                                <label>Tokens: {{ statistic.tokens }}</label>
                            </div>
                            <div>
                                <label>Cards: {{ statistic.cards }}</label>
                            </div>
                            <div>
                                <label>Total: {{ statistic.total_points }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <label class="text-gray-300 font-bold">Date: {{ game.createdAt }}</label>
            </div>
            <div class="mt-4">
                <Link :href="route('base')">
                <button class="rounded bg-gray-800 text-gray-300 py-2 px-4 hover:bg-gray-700">Home</button>
                </Link>
            </div>
            <div class="paginate flex justify-center mt-4">
                <div class="pagination flex items-center space-x-4">
                    <Link v-if="props.games.prev_page_url" :href="props.games.prev_page_url"
                        class="prev text-gray-300 hover:text-white">
                    Previous
                    </Link>
                    <span class="current text-gray-300">Page {{ props.games.current_page }}</span>
                    <Link v-if="props.games.next_page_url" :href="props.games.next_page_url"
                        class="next text-gray-300 hover:text-white">
                    Next
                    </Link>
                </div>
            </div>
        </div>
        <div v-else class="results text-gray-300">
            <h3>You have no games yet.</h3>
        </div>
    </div>
</template>
