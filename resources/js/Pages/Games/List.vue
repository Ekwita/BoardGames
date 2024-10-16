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
            <div v-for="game in games" :key="game.gameId">
                <label><strong>Winner: {{ game.winner }}</strong></label>
                <div class="results grid grid-cols-4 gap-4">
                    <div v-for="statistic in game.statistics" :key="statistic.player_id" class="player">
                        <div>
                            <label>
                                <strong>
                                    Name: <a :href="route('players.show', { player: statistic.player_id })">{{
                                        statistic.player_name.toUpperCase() }}</a>
                                </strong>
                            </label>
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
                <strong><label>Date: {{ game.createdAt }}</label></strong>
            </div>
            <div>
                <Link :href="route('base')"><button>Home</button></Link>
            </div>
            <div class="paginate flex justify-center mt-4">
                <div class="inline-block">
                    <div class="pagination">
                        <!-- Previous Link -->
                        <Link v-if="props.games.prev_page_url" :href="props.games.prev_page_url" class="prev">
                        Previous
                        </Link>

                        <!-- Current Page Number -->
                        <span class="current">
                            Page {{ props.games.current_page }}
                        </span>

                        <!-- Next Link -->
                        <Link v-if="props.games.next_page_url" :href="props.games.next_page_url" class="next">
                        Next
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="results">
                <h3>You have no games yet.</h3>
            </div>
        </div>
    </div>
</template>