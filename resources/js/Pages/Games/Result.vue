<script setup>

import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    winner: {
        type: String,
        required: true
    },
    results: {
        type: Array,
        required: true
    }
});

const points = [5, 7, 10, 12, 15, 17, 20, 25, 30];

const statusText = (status) => {
    switch (status) {
        case 1:
            return 'Dead';
        case 2:
            return 'Survived';
        case 3:
            return 'Escaped';
        default:
            return 'Unknown';
    }
};

</script>

<template>
    <div>
        <h1>Winner: {{ winner }}</h1>
        <div class="grid grid-cols-4">
            <div v-for="result in results" :key="result.id">
                <label>
                    <strong>Name: {{ result.player_name }}</strong>
                </label><br />
                <label>Status: {{ statusText(result.status) }}</label><br />

                <template v-if="result.status !== 1">
                    <label>Artifacts:</label><br />
                    <template v-for="point in points" :key="point">
                        <template v-if="result[`art${point}`] !== 0">
                            {{ point }} points <br />
                        </template>
                    </template>

                    <label>Gold: {{ result.gold }}</label><br />
                    <label>Tokens: {{ result.tokens }}</label><br />
                    <label>Cards: {{ result.cards }}</label><br />
                    <label>Total: {{ result.total_points }}</label><br />
                </template>
            </div>
        </div>
        <br />
        <Link :href="route('base')"><button>End</button></Link>
    </div>
</template>