<script setup lang="ts">
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div
                            class="mb-1 flex items-center justify-center rounded-md"
                        >
                            <img
                                src="/assets/LogoNoBck.png"
                                alt="App logo"
                                class="size-50"
                            />    
                        </div>
                        <span>Wealth On Couch </span>
                        <span>Digital Wealth, on your couch</span>
                        <span class="sr-only">{{ title }}</span>                    
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                    </Link>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
