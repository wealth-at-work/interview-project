<script setup lang="ts">
import CommentsList from '@/components/CommentList.vue';
import FavoriteButton from '@/components/FavoriteButton.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';

interface Book {
    id: number;
    title: string;
    picture: string;
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
    book: Book;
    comments: Comment[];
}>();

defineOptions({
    layout: AppHeaderLayout
});
</script>

<template>
    <div class="min-h-screen bg-cream">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden p-8 mb-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Cover -->
                        <div class="flex-shrink-0">
                            <img
                                :src="book.picture"
                                :alt="book.title"
                                class="w-full md:w-64 aspect-[2/3] object-cover rounded border-2 border-dark-green"
                            />
                        </div>

                        <!-- Details -->
                        <div class="flex-grow">
                            <h1 class="text-4xl font-bold text-dark-green mb-4">{{ book.title }}</h1>
                            <FavoriteButton type="book" :id="book.id" />

                            <div class="bg-cream border-2 border-dark-green rounded-lg p-6 text-center">
                                <p class="text-xl text-dark-green font-semibold">
                                    📚 Book details coming soon!
                                </p>
                                <p class="text-gray-600 mt-2">
                                    We're working on integrating a book API to show more information.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <CommentsList :comments="comments" />
            </div>
        </div>
    </div>
</template>
