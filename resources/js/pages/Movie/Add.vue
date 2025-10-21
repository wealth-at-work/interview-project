<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

interface MediaItem {
    id: string;
    title: string;
    picture: string;
    year?: number;
    added_by?: string;
    is_already_added?: boolean;
}

const props = defineProps<{
    searchTerm: string;
    movies: MediaItem[];
}>();

const searchInput = ref(props.searchTerm);

const handleSearch = () => {
    router.get('/movies/add',
        { q: searchInput.value },
        { preserveState: true }
    );
};
</script>

<template>
    <GuestLayout>
        <section>
            <div class="flex justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-dark-green">Add Movie</h2>
                    <p class="text-dark-green">Add a movie to your local library. Use the search field below to search and select the movie you would like to add to your local library.</p>
                </div>
                <a href="/movies" class="bg-dark-green text-white p-2 rounded-md h-10">Cancel</a>
            </div>
            <div>
                <div class="flex w-full max-w-sm items-center gap-2">
                    <Input
                        v-model="searchInput"
                        placeholder="Search movies..."
                        @keyup.enter="handleSearch"
                    />
                    <Button @click="handleSearch">Search</Button>
                </div>
            </div>
        </section>
        <section v-if="movies && props.searchTerm" class="mt-8">
            <div v-if="movies.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <component
                    :is="movie.is_already_added ? 'div' : Link"
                    v-for="movie in movies"
                    :key="movie.id"
                    :href="movie.is_already_added ? undefined : `/movies/add/${movie.id}`"
                    class="group"
                    :class="{'cursor-not-allowed': movie.is_already_added}"
                >
                    <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden transition-shadow" :class="{'hover:shadow-lg': !movie.is_already_added}">
                        <img
                            :src="movie.picture"
                            :alt="movie.title"
                            class="w-full aspect-[2/3]  object-cover"
                            :class="{'opacity-30': movie.is_already_added}"
                        />
                        <div class="p-4">
                            <h3
                                class="text-lg font-semibold text-dark-green mb-2"
                                :class="{'group-hover:underline': !movie.is_already_added}">
                                {{ movie.title }}
                            </h3>
                            <p v-if="movie.is_already_added" class="text-dark-green font-bold">Movie is already added</p>
                        </div>
                    </div>
                </component>
            </div>
            <div v-else class="text-center py-12 text-dark-green">
                <p class="text-xl">No movies match your search.</p>
            </div>
        </section>
    </GuestLayout>
</template>
