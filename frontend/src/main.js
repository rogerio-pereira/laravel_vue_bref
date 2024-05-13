import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'

//Axios Config
axios.defaults.withCredentials = true
axios.defaults.baseURL = import.meta.VITE_BACKEND_URL
axios.defaults.headers.common['Access-Control-Origin'] = '*';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';
// axios.defaults.headers.common['X-Authorization'] = '';

const app = createApp(App)

app.use(createPinia())
app.use(router)
    .use(VueAxios, axios)

app.mount('#app')
