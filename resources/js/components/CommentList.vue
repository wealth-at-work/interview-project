<script setup lang="ts">
interface Comment {
    id: number;
    title: string;
    body: string;
    user_id: number;
    user_name: string;
    created_at: string;
}

defineProps<{
    comments: Comment[];
}>();
</script>

<template>
    <div class="bg-white border-2 border-dark-green rounded-lg overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-dark-green mb-6">Comments</h2>

        <div v-if="comments.length > 0" class="space-y-6">
            <div
                v-for="comment in comments"
                :key="comment.id"
                class="border-b border-gray-200 pb-6 last:border-b-0"
            >
                <h3 class="text-lg font-semibold text-dark-green mb-2">{{ comment.title }}</h3>
                <p class="text-gray-700 mb-3">{{ comment.body }}</p>
                <div class="text-sm text-gray-500">
                    By {{ comment.user_name }} • {{ new Date(comment.created_at).toLocaleDateString() }}
                </div>
            </div>
        </div>

        <div v-else class="text-center py-8 text-gray-600">
            <p>No comments yet. Be the first to comment!</p>
        </div>
    </div>
</template>
