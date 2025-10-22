<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { onMounted, computed  } from 'vue';
import { useFavoriteStore } from '@/stores/favoriteStore';
import SearchFilter from '@/components/SearchFilter.vue';
import { useFilterStore } from '@/stores/filterStore';
interface MediaItem {
    id: number;
    title: string;
    picture: string;
    added_by: string;
}

const props = defineProps<{
    books: MediaItem[];
    movies: MediaItem[];
}>();

defineOptions({
    layout: AppHeaderLayout
});

const favoriteStore = useFavoriteStore()
const filterStore = useFilterStore()

const isItemFavorite = (type: 'movie' | 'book', id: number) => {
  return favoriteStore.isFavorite(type, id)
}

// Filtered data
const filteredMovies = computed(() => {

  if (filterStore.selectedType === 'favorites') {
    return props.movies.filter(movie => isItemFavorite('movie', movie.id))
  }

  if (filterStore.searchQuery.trim()) {
    const query = filterStore.searchQuery.toLowerCase().trim()
    return props.movies.filter(movie => 
      movie.title.toLowerCase().includes(query)
    )
  }
  return props.movies
})
const filteredBooks = computed(() => {

    if (filterStore.selectedType === 'favorites') {
        return props.books.filter(book => isItemFavorite('book', book.id))
    }

    if (filterStore.searchQuery.trim()) {
    const query = filterStore.searchQuery.toLowerCase().trim()
    return props.books.filter(book => book.title.toLowerCase().includes(query))
  }
  return props.books
})

// Show sections based on filter
const showMovies = computed(() => {
  if (filterStore.selectedType === 'favorites') return filteredMovies.value.length > 0
  return  filterStore.selectedType === 'all' || filterStore.selectedType === 'movie'
})

const showBooks = computed(() => {
  if (filterStore.selectedType === 'favorites') return filteredBooks.value.length > 0
  return filterStore.selectedType === 'all' || filterStore.selectedType === 'book'
})

// Results count
const totalResults = computed(() => 
  filteredMovies.value.length + filteredBooks.value.length
)

onMounted(async () => {
    await favoriteStore.fetchFavorites()
})
</script>

<template>
    <div class="min-h-screen bg-cream">
        <div class="container mx-auto px-4 py-12">
            <!-- Search Filter Component -->
            <SearchFilter />

            <!-- No Results -->
            <div v-if="filterStore.hasActiveFilters && totalResults === 0" class="text-center py-16">
                <p class="text-xl text-gray-600 mb-2">No results found</p>
                <p class="text-gray-500 mb-4">Try adjusting your filters</p>
                <button @click="filterStore.clearFilters" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Clear Filters
                </button>
            </div>
            <!-- Books Section -->
            <section v-if="showBooks && filteredBooks.length > 0" class="mb-16">
                <h2 class="text-3xl font-bold text-dark-green mb-8">Latest Books</h2>
                <div v-if="books.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="book in filteredBooks"
                        :key="book.id"
                        :href="`/book/${book.id}`"
                        class="group"
                    >
                        <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="top-2 right-2 z-10">
                                <svg
                                    v-if="favoriteStore.isFavorite('book', book.id)"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    class="w-6 h-6 text-red-500 drop-shadow"
                                >
                                    <path
                                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                                    />
                                </svg>   
                                <span v-else>Discover more about the movie</span>                         
                            </div>
                            <img
                                :src="book.picture"
                                :alt="book.title"
                                class="w-full aspect-[2/3]  object-cover"
                            />
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-dark-green group-hover:underline mb-2">
                                    {{ book.title }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Added by {{ book.added_by }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>
                <div v-else class="text-center py-12 text-dark-green">
                    <p class="text-xl">No books here yet.</p>
                </div>
            </section>

            <!-- Movies Section -->
            <section v-if="showMovies && filteredMovies.length > 0">
                <h2 class="text-3xl font-bold text-dark-green mb-8">Latest Movies</h2>
                <div v-if="movies.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="movie in filteredMovies"
                        :key="movie.id"
                        :href="`/movie/${movie.id}`"
                        class="group"
                    >
                        <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="top-2 right-2 z-10">
                                <svg
                                    v-if="favoriteStore.isFavorite('movie', movie.id)"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    class="w-6 h-6 text-red-500 drop-shadow"
                                >
                                    <path
                                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                                    />
                                </svg>   
                                <span v-else>Discover more about the movie</span>                         
                            </div>
                            <img
                                :src="movie.picture"
                                :alt="movie.title"
                                class="w-full aspect-[2/3]  object-cover"
                            />
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-dark-green group-hover:underline mb-2">
                                    {{ movie.title }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Added by {{ movie.added_by }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>
                <div v-else class="text-center py-12 text-dark-green">
                    <p class="text-xl">No movies here yet.</p>
                </div>
            </section>
        </div>
    </div>
</template>
