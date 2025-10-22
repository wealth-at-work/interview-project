<script setup lang="ts">
import { ref } from 'vue'
import { useFilterStore } from '@/stores/filterStore'
import { Search, X, Film, BookOpen, Grid3x3, Heart } from 'lucide-vue-next'

const filterStore = useFilterStore()

const filterOptions = [
  { value: 'all', label: 'All', icon: Grid3x3 },
  { value: 'movie', label: 'Movies', icon: Film },
  { value: 'book', label: 'Books', icon: BookOpen },
  { value: 'favorites', label: 'Favorites', icon: Heart }
] as const

const searchInput = ref<HTMLInputElement | null>(null)

const handleClearSearch = () => {
  filterStore.clearSearch()
  searchInput.value?.focus()
}

const handleClearAll = () => {
  filterStore.clearFilters()
  searchInput.value?.focus()
}
</script>

<template>
  <div class="search-filter-container">
    <!-- Filter Type Tabs -->
    <div class="filter-tabs">
      <button
        v-for="option in filterOptions"
        :key="option.value"
        @click="filterStore.setType(option.value)"
        :class="[
          'filter-tab',
          { 'active': filterStore.selectedType === option.value }
        ]"
      >
        <component :is="option.icon" class="icon" />
        <span>{{ option.label }}</span>
      </button>
    </div>

    <!-- Search Input only for title-->
    <div v-if="filterStore.selectedType !== 'favorites'" class="search-wrapper">
      <div class="search-input-container">
        <Search class="search-icon" />
        
        <input
          ref="searchInput"
          v-model="filterStore.searchQuery"
          type="text"
          placeholder="Search by title..."
          class="search-input"
        />

        <!-- Clear Search Button-->
        <button
          v-if="filterStore.searchQuery"
          @click="handleClearSearch"
          class="clear-btn"
          aria-label="Clear search"
        >
          <X class="icon" />
        </button>
      </div>

      <!-- Clear All Button to reset all filters and will default it as All-->
      <button
        v-if="filterStore.hasActiveFilters"
        @click="handleClearAll"
        class="clear-all-btn"
      >
        Clear All
      </button>
    </div>

    <div v-if="filterStore.hasActiveFilters" class="filter-summary">
      <span class="summary-label">Filtering:</span>
      <span class="summary-value">{{ filterStore.filterSummary }}</span>
    </div>
  </div>
</template>

<style scoped>
.search-filter-container {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  padding: 0.25rem;
  background: #f3f4f6;
  border-radius: 8px;
}

.filter-tab {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  border: none;
  background: transparent;
  border-radius: 6px;
  font-weight: 500;
  font-size: 0.875rem;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-tab:hover {
  background: rgba(255, 255, 255, 0.8);
  color: #374151;
}

.filter-tab.active {
  background: white;
  color: #1f2937;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.filter-tab .icon {
  width: 18px;
  height: 18px;
}

.search-wrapper {
  display: flex;
  gap: 0.75rem;
  align-items: center;
}

.search-input-container {
  position: relative;
  flex: 1;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  color: #9ca3af;
  pointer-events: none;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 3rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-input::placeholder {
  color: #9ca3af;
}

.clear-btn {
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  padding: 0.5rem;
  border: none;
  background: transparent;
  border-radius: 6px;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
}

.clear-btn:hover {
  background: #f3f4f6;
  color: #374151;
}

.clear-btn .icon {
  width: 16px;
  height: 16px;
}

.clear-all-btn {
  padding: 0.75rem 1.5rem;
  border: 2px solid #e5e7eb;
  background: white;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.clear-all-btn:hover {
  border-color: #ef4444;
  color: #ef4444;
  background: #fef2f2;
}

.filter-summary {
  margin-top: 1rem;
  padding: 0.75rem 1rem;
  background: #f9fafb;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.summary-label {
  color: #6b7280;
  font-weight: 500;
}

.summary-value {
  color: #1f2937;
  font-weight: 600;
}

@media (max-width: 640px) {
  .search-filter-container {
    padding: 1rem;
  }

  .filter-tabs {
    flex-direction: column;
  }

  .search-wrapper {
    flex-direction: column;
  }

  .clear-all-btn {
    width: 100%;
  }
}
</style>