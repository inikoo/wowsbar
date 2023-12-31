<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Link } from "@inertiajs/vue3"
import { Hero } from "@/types/hero"
import Image from "@/Components/Image.vue"
import Button from '../Elements/Buttons/Button.vue'

const props = defineProps<{
    hero: Hero
}>()
</script>

<template>
    <div class="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0 lg:pt-0">
            <div class="w-72">
                <Image :src="hero.media.logo" alt="Wowsbar" />
            </div>
            <div v-if="hero.whatsNew" class="mt-8">
                <Link :href="route(hero.whatsNew.route.name, hero.whatsNew.route.parameters)" class="inline-flex space-x-6">
                    <span
                        class="rounded-full bg-gray-600/10 px-3 py-1 text-sm font-semibold leading-6 text-gray-600 ring-1 ring-inset ring-gray-600/10">
                        What's new
                    </span>
                    <span class="inline-flex items-center space-x-2 text-sm font-medium leading-6 text-gray-600">
                        <span>{{ hero.whatsNew.label }}</span>
                        <ChevronRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </span>
                </Link>
            </div>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-gray-700 sm:text-6xl">{{ hero.title }}</h1>
            <p v-if="hero.text" class="mt-6 text-lg leading-8 text-gray-600">{{ hero.text }}</p>
            <!-- Get started -->
            <div class="mt-8 flex items-center gap-x-6 select-none">
                <a href="#" class="">
                    <Button :style="`primary`">
                        Get started
                    </Button>
                </a>
                <a href="#" class="text-sm font-semibold leading-6 text-gray-700">
                    Learn more
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mr-0 lg:mt-0 lg:flex-none ">
            <div class="max-w-3xl flex-none">
                <div
                    class="-m-2 rounded-xl bg-gray-700/5 p-2 ring-1 ring-inset ring-gray-700/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                    <div class="rounded-lg overflow-hidden ring-1 ring-gray-300">
                        <Image :src="hero.media.appScreenshot" alt="App screenshot" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
