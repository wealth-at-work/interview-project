<script setup lang="ts">
import { useFavoriteStore } from '@/stores/favoriteStore'

interface Props {
  type: 'movie' | 'book'
  id: number
}

const props = defineProps<Props>()
const favoriteStore = useFavoriteStore()

const toggleFavorite = async () => {
  try {
    await favoriteStore.toggleFavorite(props.type, props.id)
  } catch (error) {
    console.error('Error toggling favorite:', error)
  }
}
</script>

<template>
  <button 
    @click.stop="toggleFavorite"
    class="favorite-btn"
    :class="{ 'is-favorite': favoriteStore.isFavorite(type, id) }"
    :aria-label="favoriteStore.isFavorite(type, id) ? 'Remove from favorites' : 'Add to favorites'"
  >
    <svg 
      v-if="favoriteStore.isFavorite(type, id)"
      class="icon-filled"
      xmlns="http://www.w3.org/2000/svg" 
      viewBox="0 0 24 24" 
      fill="currentColor"
    >
      <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
    </svg>
    
    <svg 
      v-else
      class="icon-outline"
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24" 
      stroke="currentColor"
    >
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
  </button>
</template>

<style scoped>
.favorite-btn {
  padding: 0.5rem;
  border: none;
  background: transparent;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #6b7280;
}

.favorite-btn:hover {
  transform: scale(1.1);
}

.favorite-btn.is-favorite {
  color: #ef4444;
}

.icon-filled,
.icon-outline {
  width: 24px;
  height: 24px;
}

.favorite-btn:hover .icon-outline {
  color: #ef4444;
}
</style>