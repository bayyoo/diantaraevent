<template>
  <div class="banner-carousel w-full max-w-7xl mx-auto px-0">
    <div class="relative border-2 border-gray-200 rounded-lg overflow-hidden">
      <!-- Slides -->
      <div class="relative" style="height: 300px">
        <img 
          v-for="(slide, index) in slides" 
          :key="index"
          :src="slide.image" 
          :alt="slide.title"
          class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
          :class="{ 'opacity-0': currentSlide !== index, 'opacity-100': currentSlide === index }"
        >
      </div>
      
      <!-- Navigation Dots -->
      <div class="flex justify-center items-center p-3 space-x-2 bg-gray-50 border-t border-gray-100">
        <button 
          v-for="(slide, index) in slides" 
          :key="index"
          @click="goToSlide(index)"
          class="w-3 h-3 rounded-full transition-all duration-300"
          :class="currentSlide === index ? 'bg-blue-600 w-8' : 'bg-gray-300 hover:bg-gray-400'"
          :aria-label="`Go to slide ${index + 1}`"
        ></button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BannerCarousel',
  data() {
    return {
      currentSlide: 0,
      autoSlideInterval: null,
      slides: [
        {
          image: 'https://images.unsplash.com/photo-1505373876331-7d3ec0f35492?w=1200&h=600&fit=crop&crop=center',
          title: 'Event 1'
        },
        {
          image: 'https://images.unsplash.com/photo-1505373876331-7d3ec0f35492?w=1200&h=600&fit=crop&crop=center',
          title: 'Event 2'
        },
        {
          image: 'https://images.unsplash.com/photo-1505373876331-7d3ec0f35492?w=1200&h=600&fit=crop&crop=center',
          title: 'Event 3'
        }
      ]
    }
  },
  mounted() {
    this.startAutoSlide();
  },
  beforeUnmount() {
    this.stopAutoSlide();
  },
  methods: {
    goToSlide(index) {
      this.currentSlide = index;
    },
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length;
    },
    startAutoSlide() {
      if (!this.autoSlideInterval) {
        this.autoSlideInterval = setInterval(this.nextSlide, 3000);
      }
    },
    stopAutoSlide() {
      if (this.autoSlideInterval) {
        clearInterval(this.autoSlideInterval);
        this.autoSlideInterval = null;
      }
    }
  }
}
</script>

<style scoped>
/* Simple styles for the carousel */
.banner-carousel {
  max-width: 1280px;
  width: 100%;
}

/* Make sure images cover the area */
img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}
</style>
