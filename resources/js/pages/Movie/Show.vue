<script setup lang="ts">
import CommentsList from '@/components/CommentList.vue';

interface Movie {
    id: number;
    title: string;
    synopsis: string | null;
    ratings: {
        imdb: {
            score: string | null;
            votes: string | null;
        };
        metacritic: {
            score: string | null;
        };
        rotten_tomatoes: {
            score: string | null;
        };
    };
    poster: string;
    director: string | null;
    writer: string | null;
    actors: string[];
}

interface Comment {
    id: number;
    title: string;
    body: string;
    user_id: number;
    user_name: string;
    created_at: string;
}

defineProps<{
    movie: Movie;
    comments: Comment[];
}>();
</script>

<template>
    <div class="min-h-screen bg-cream">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden p-8 mb-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Poster -->
                        <div class="flex-shrink-0">
                            <img
                                :src="movie.poster"
                                :alt="movie.title"
                                class="w-full md:w-64 aspect-[2/3] object-cover rounded border-2 border-dark-green"
                            />
                        </div>

                        <!-- Details -->
                        <div class="flex-grow">
                            <h1 class="text-4xl font-bold text-dark-green mb-4">{{ movie.title }}</h1>

                            <!-- Ratings -->
                            <div class="flex gap-4 mb-6">
                                <div v-if="movie.ratings.imdb.score" class="text-center">
                                    <div class="text-sm text-gray-600">IMDb</div>
                                    <div class="text-lg font-semibold text-dark-green">{{ movie.ratings.imdb.score }}/10</div>
                                </div>
                                <div v-if="movie.ratings.metacritic.score" class="text-center">
                                    <div class="text-sm text-gray-600">Metacritic</div>
                                    <div class="text-lg font-semibold text-dark-green">{{ movie.ratings.metacritic.score }}</div>
                                </div>
                                <div v-if="movie.ratings.rotten_tomatoes.score" class="text-center">
                                    <div class="text-sm text-gray-600">Rotten Tomatoes</div>
                                    <div class="text-lg font-semibold text-dark-green">{{ movie.ratings.rotten_tomatoes.score }}</div>
                                </div>
                            </div>

                            <!-- Synopsis -->
                            <div v-if="movie.synopsis" class="mb-6">
                                <h2 class="text-xl font-semibold text-dark-green mb-2">Synopsis</h2>
                                <p class="text-gray-700">{{ movie.synopsis }}</p>
                            </div>

                            <!-- Credits -->
                            <div class="space-y-3">
                                <div v-if="movie.director">
                                    <span class="font-semibold text-dark-green">Director:</span>
                                    <span class="text-gray-700 ml-2">{{ movie.director }}</span>
                                </div>
                                <div v-if="movie.writer">
                                    <span class="font-semibold text-dark-green">Writer:</span>
                                    <span class="text-gray-700 ml-2">{{ movie.writer }}</span>
                                </div>
                                <div v-if="movie.actors.length > 0">
                                    <span class="font-semibold text-dark-green">Actors:</span>
                                    <span class="text-gray-700 ml-2">{{ movie.actors.join(', ') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <CommentsList :comments="comments" />
            </div>
        </div>
    </div>
</template>
