<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from '@/Stores/layout'
import LastEditedBanners from '@/Components/LastEditedBanners.vue'
import BoxCreateBanner from '@/Components/Elements/BoxCreateBanner.vue'
import WelcomeSteps from '@/Components/Dashboard/WelcomeSteps.vue'

const props = defineProps<{
    title: string
    latest_banners?: {}
    latest_banners_count: number
    portfolio_websites_count: number
    name: string
    welcome?: any
    firstBanner: {
        text: string
        websiteOptions: {
            1: {
                label: string
            }
        }
        createRoute: {
            name: string
        }
    }
}>()

const currentHour = new Date().getHours();

// Greeting Message (Good Morning, Good Afternoon, etc)
const greetingMessage =
    currentHour >= 4 && currentHour < 12 ? // after 4:00AM and before 12:00PM
        trans('Good morning') :
        currentHour >= 12 && currentHour <= 17 ? // after 12:00PM and before 6:00pm
            trans('Good afternoon') :
            currentHour > 17 || currentHour < 4 ? // after 5:59pm or before 4:00AM (to accommodate night owls)
                trans('Good evening') :
                trans('Welcome') // if for some reason the calculation didn't work

const layout = useLayoutStore()

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <!-- Greeting Message -->
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl font-light">
            {{ trans(greetingMessage) }}, <span class="font-bold capitalize">{{ name }}</span>!
        </div>
        
        <!-- Last Edited Banners -->
        <div v-if="latest_banners_count > 0" class="">
            <hr class="mt-3 mb-8">
            <LastEditedBanners :banners="latest_banners" />
        </div>

        <!-- Box Create Banner (if banner is 0) -->
        <div v-if="firstBanner" class="">
            <hr class="mt-3 mb-8">
            <BoxCreateBanner :text="firstBanner.text" :websiteOptions="firstBanner.websiteOptions" :createRoute="firstBanner.createRoute"/>
        </div>

        <!-- Welcome Steps (if website is 0) -->
        <WelcomeSteps v-if="welcome" :data="welcome" />
    </div>
</template>