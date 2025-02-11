<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 12 Jul 2023 10:54:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import Button from "@/Components/Elements/Buttons/Button.vue"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCactus, faIslandTropical, faSkullCow, faFish } from '@fal'
import { faPlus } from '@far'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faPlus, faCactus, faIslandTropical, faSkullCow, faFish)
import { trans } from 'laravel-vue-i18n'

const props = defineProps<{
    data?: {
        action?: {
            label: string
            route: {
                name: string
                parameters: []
            }
            style?: string
            tooltip: string
            icon?: string | string[]
        }
        description: string
        title: string
        icons?: string[]
    }
}>()

const randomIcon = [
    {
        secondIcon: ['fal', 'fa-cactus'],
        firstIcon: ['fal', 'fa-skull-cow'],
    },
    {
        firstIcon: ['fal', 'fa-island-tropical'],
        secondIcon: ['fal', 'fa-fish'],
    },
]

const randomIndex = Math.floor(Math.random() * randomIcon.length)
</script>

<template>
    <div class="text-center border-gray-200 pt-14">
        <div v-if="data?.icons?.length === 1" class="mb-6">
            <FontAwesomeIcon :icon="data?.icons?.[0]" class="mx-auto h-9 text-gray-300" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[0]" class="mx-7 h-12 w-12 text-gray-400" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[0]" class="mx-auto h-8  text-gray-300" aria-hidden="true" />
        </div>
        
        <div v-else-if="data?.icons?.length === 2" class="mb-6">
            <FontAwesomeIcon :icon="data?.icons?.[1]" class="mx-auto h-9 text-gray-300" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[0]" class="mx-7 h-12 w-12 text-gray-400" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[1]" class="mx-auto h-8  text-gray-300" aria-hidden="true" />
        </div>
        
        <div v-else-if="data?.icons?.length === 3" class="mb-6">
            <FontAwesomeIcon :icon="data?.icons?.[1]" class="mx-auto h-9 text-gray-300" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[0]" class="mx-7 h-12 w-12 text-gray-400" aria-hidden="true" />
            <FontAwesomeIcon :icon="data?.icons?.[2]" class="mx-auto h-8  text-gray-300" aria-hidden="true" />
        </div>

        <div v-else class="mb-6">
            <FontAwesomeIcon :icon="randomIcon[randomIndex].secondIcon" class="mx-auto h-9 text-gray-300" aria-hidden="true" />
            <FontAwesomeIcon :icon="randomIcon[randomIndex].firstIcon" class="mx-7 h-12 w-12 text-gray-400" aria-hidden="true" />
            <FontAwesomeIcon :icon="randomIcon[randomIndex].secondIcon" class="mx-auto h-8  text-gray-300" aria-hidden="true" />
        </div>

        <h3 class="font-logo text-lg font-bold text-gray-600 capitalize">{{ data?.title ?? trans('No records found') }}</h3>
        <p v-if="data?.description" class="text-sm text-gray-500 inline-block">{{ data?.description }}</p>

        <Link v-if="data?.action" :href="route(data?.action.route.name, data?.action.route.parameters)" class="mt-4 block">
            <Button size="xs" :style="data?.action.style" :icon="data?.action.icon" :label="data?.action.tooltip" />
        </Link>

        <slot name="button-empty-state">
        </slot>
        
    </div>
</template>
