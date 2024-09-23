import './bootstrap';

import {
    createApp
} from 'vue';


import PointsCalculator from './components/PointsCalculator.vue';

createApp({})
    .component('PointsCalculator', PointsCalculator)
    .mount('#app')
