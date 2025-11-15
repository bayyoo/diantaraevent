import { createApp } from 'vue'
import BannerCarousel from './components/BannerCarousel.vue'

// Create Vue app
const app = createApp({})

// Register components
app.component('BannerCarousel', BannerCarousel)

// Mount Vue components
app.mount('#app')
