<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
import Image from "@/Components/Image.vue"


const logo = usePage().props.art.logo

</script>

<template>
    <div class="bg-gradient-to-b from-gray-100/50 to-gray-100/0 w-screen fixed z-10 px-6 pt-3 flex justify-between items-center">
        <Link :href="route('public.home')">
            <Image class="h-6 select-none" :src="logo" alt="Wowsbar" />
        </Link>
        <div class="flex justify-end gap-x-4 text-sm font-medium">
            <Link :href="route('public.register')" class="">
                <Button :style="`tertiary`" size="xs">Register</Button>
            </Link>
            <Link :href="route('public.login')" class="">
                <Button :style="`primary`" size="xs">Login</Button>
            </Link>
        </div>
    </div>
</template>
