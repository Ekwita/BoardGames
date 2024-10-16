<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    game: {
        type: Array,
        default: () => null,
    },
    results: {
        type: Array,
        default: () => [],
    },
});

const points = [5, 7, 10, 12, 15, 17, 20, 25, 30];

function formatDate(date) {
    if (!date) return '';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString(undefined, options);
}

</script>

<template>

    <Head title="Welcome" />

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-3 gap-4 justify-items-center py-10">
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Dashboard
                        </Link>
                        <Link v-if="$page.props.auth.user" :href="route('games.newGame')"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                        New Game
                        </Link>
                        <Link v-if="$page.props.auth.user" :href="route('players.index')"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                        Players
                        </Link>
                        <Link v-if="$page.props.auth.user" :href="route('games.index')"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                        Games
                        </Link>

                        <template v-else>
                            <Link :href="route('login')"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                            Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 text-base font-medium">
                            Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div v-if="game && results">
                        <div class="title">Last game result: <br></div>
                        <div id="winner-name">Winner: {{ game.winner }}</div>

                        <!-- Display each player's results -->
                        <div v-for="(result, index) in results" :key="index" name="results">
                            <label><strong>Name: {{ result.player_name }}</strong></label><br>
                            <label>Status:
                                <template v-if="result.status === 1">Dead</template>
                                <template v-else-if="result.status === 2">Survived</template>
                                <template v-else-if="result.status === 3">Escaped</template>
                            </label><br>

                            <!-- Artifacts, gold, tokens, and total points -->
                            <div v-if="result.status !== 1">
                                <label>Artifacts: <br>
                                    <template v-for="point in points" :key="point">
                                        <span v-if="result[`art${point}`] !== 0">{{ point }} points<br></span>
                                    </template>
                                </label>

                                <label>Gold: {{ result.gold }}</label><br>
                                <label>Tokens: {{ result.tokens }}</label><br>
                                <label>Cards: {{ result.cards }}</label><br>
                                <label>Total: {{ result.total_points }}</label><br>
                            </div>
                        </div>

                        <strong><label>Date: {{ formatDate(game.created_at) }}</label></strong>
                    </div>
                    <div v-else>
                        You have no games yet.
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                </footer>
            </div>
        </div>
    </div>
</template>
