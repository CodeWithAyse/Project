import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';
import { ChevronDown, ChevronRight ,SquareArrowRight,SquareArrowLeft} from 'lucide-vue-next';
const app = createApp(App);
app.use(router);
app.use(store);  // Vuex Store'u kullan
app.component('ChevronDown', ChevronDown);
app.component('ChevronRight', ChevronRight);
app.component('SquareArrowRight', SquareArrowRight); 
app.component('SquareArrowLeft', SquareArrowLeft);
app.mount('#app');

export default axios;