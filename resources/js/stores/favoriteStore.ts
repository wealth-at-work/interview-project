// stores/favoriteStore.ts
import { defineStore } from 'pinia'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

interface Favorite {
  id: number
  favoritable_id: number
  type: 'movie' | 'book'
}

export const useFavoriteStore = defineStore('favorite', {
  state: () => ({
    favorites: [] as Favorite[],
    loading: false,
    error: null as string | null
  }),

  getters: {
    isFavorite: (state) => (type: string, id: number) => {
      if (!Array.isArray(state.favorites)) {
        return false
      }
      
      return state.favorites.some(
        fav => fav.favoritable_id === id && 
               fav.type.toLowerCase() === type.toLowerCase()
      )
    },
    
    favoriteCount: (state) => state.favorites.length
  },

  actions: {
    async fetchFavorites() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get('/favorites')
        
        if (response.data.data && Array.isArray(response.data.data)) {
          this.favorites = response.data.data
        } else if (Array.isArray(response.data)) {
          this.favorites = response.data
        } else {
          this.favorites = []
        }
        
      } catch (error: any) {
        console.error('Failed to fetch favorites:', error)
        this.error = error.response?.data?.message || 'Failed to fetch favorites'
        this.favorites = []
      } finally {
        this.loading = false
      }
    },

    async toggleFavorite(type: 'movie' | 'book', id: number) {
      try {
        await axios.post('/favorites/toggle', { type, id })
        await this.fetchFavorites()
      } catch (error: any) {
        console.error('Failed to toggle favorite:', error)
        this.error = error.response?.data?.error || 'Failed to toggle favorite'
        throw error
      }
    }
  }
})