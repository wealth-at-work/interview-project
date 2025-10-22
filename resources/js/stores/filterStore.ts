import { defineStore } from 'pinia'

type FilterType = 'all' | 'movie' | 'book' | 'favorites'

interface FilterState {
  searchQuery: string
  selectedType: FilterType
}

export const useFilterStore = defineStore('filter', {
  state: (): FilterState => ({
    searchQuery: '',
    selectedType: 'all'
  }),

  getters: {
    hasActiveFilters: (state): boolean => {
      return state.searchQuery.trim() !== '' || state.selectedType !== 'all'
    },


    filterSummary: (state): string => {
      const parts: string[] = []
      
      if (state.selectedType !== 'all') {
        const labels: Record<FilterType, string> = {
          all: 'All',
          movie: 'Movies',
          book: 'Books',
          favorites: 'Favorites'
        }
        parts.push(labels[state.selectedType])
      }
      
      if (state.searchQuery.trim()) {
        parts.push(`"${state.searchQuery}"`)
      }
      
      return parts.length > 0 ? parts.join(' - ') : 'All items'
    }
  },

  actions: {
    setSearchQuery(query: string) {
      this.searchQuery = query
    },

    setType(type: FilterType) {
      this.selectedType = type
    },

    clearFilters() {
      this.searchQuery = ''
      this.selectedType = 'all'
    },

    clearSearch() {
      this.searchQuery = ''
    }
  }
})