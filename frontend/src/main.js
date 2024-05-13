import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

//Axios Config
axios.defaults.withCredentials = true
axios.defaults.baseURL = import.meta.VITE_BACKEND_URL
axios.defaults.headers.common['Access-Control-Origin'] = '*';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';
// axios.defaults.headers.common['X-Authorization'] = '';

// Vuetify
const vuetify = createVuetify({
                        components,
                        directives,
                    })

const app = createApp(App)

app.use(createPinia())
app.use(router)
    .use(VueAxios, axios)
    .use(vuetify)

app.mount('#app')
