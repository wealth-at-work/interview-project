<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface MediaItem {
    id: number;
    title: string;
    picture: string;
    added_by: string;
}

defineProps<{
    books: MediaItem[];
    movies: MediaItem[];
}>();
</script>

<template>
    <div class="min-h-screen bg-cream">
        <div class="container mx-auto px-4 py-12">
            <!-- Books Section -->
            <section class="mb-16">
                <h2 class="text-3xl font-bold text-dark-green mb-8">Latest Books</h2>
                <div v-if="books.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="book in books"
                        :key="book.id"
                        :href="`/book/${book.id}`"
                        class="group"
                    >
                        <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
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
            <section>
                <div class="flex justify-between mb-8">
                    <h2 class="text-3xl font-bold text-dark-green">Latest Movies</h2>
                    <a href="/movies" class="bg-dark-green text-white p-2 rounded-md h-10">View all</a>
                </div>
                <div v-if="movies.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="movie in movies"
                        :key="movie.id"
                        :href="`/movie/${movie.id}`"
                        class="group"
                    >
                        <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
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
