<script setup lang="ts">
    import Alert from '@/components/ui/alert/Alert.vue';
    import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
    import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const page = usePage();
    const flash = computed(() => page.props.flash);
</script>

<template>
    <div class="min-h-screen bg-cream">
        <div class="container mx-auto px-4 py-12">
            <div v-if="flash.success || flash.error" class="mb-4">
                <Alert v-if="flash.success" variant="success">
                    <AlertTitle>Success!</AlertTitle>
                    <AlertDescription>
                        {{ flash.success }}
                    </AlertDescription>
                </Alert>

                <Alert v-if="flash.error" variant="destructive">
                    <AlertTitle>Oops!</AlertTitle>
                    <AlertDescription>
                        {{ flash.error }}
                    </AlertDescription>
                </Alert>
            </div>

            <slot />
        </div>
    </div>
</template>
