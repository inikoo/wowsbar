<template>
    <div class="relative ">
        <div class="bg-gradient-to-b from-gray-100/50 to-gray-100/0 w-screen fixed top-0 z-10 px-8 py-1 flex justify-between items-center">
            <Link :href="route('landlord.welcome')">
                <img class="h-6 select-none" src="@/../art/logo/png/1.png" alt="Wowsbar" />
            </Link>
            <div class="flex justify-end gap-x-4 text-sm">
                <Link :href="route('landlord.login')" class="bg-gray-500 text-gray-100 py-0.5 flex place-items-center cursor-pointer px-4 rounded hover:bg-gray-600/90 active:bg-gray-600 select-none">
                    Login
                </Link>
                <Link :href="route('landlord.register')" class="bg-gray-100/30 border-2 border-gray-500 cursor-pointer text-gray-600 py-0.5 flex place-items-center px-4 rounded hover:bg-gray-200/70 active:bg-gray-300 select-none">
                    Register
                </Link>
            </div>
        </div>
        <slot />
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<style lang="scss">
</style>