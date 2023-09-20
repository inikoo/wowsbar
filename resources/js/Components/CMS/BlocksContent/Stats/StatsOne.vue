<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faDatabase } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faDatabase)
const props = defineProps<{
    dataStats: any,
}>()
</script>

<template>
    <div class="w-full bg-gray-100 py-10 px-5">
        <!-- <h3 class="text-base font-semibold leading-6 text-gray-900">Last 30 days</h3> -->
        <Text v-model="dataStats.title"/>

        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="item in dataStats.statsList" :key="item.id"
                class="rounded-lg bg-white shadow">
                <div class="flex gap-x-4 px-4 py-5 sm:px-6 sm:py-6">
                    <dt class="flex items-center justify-center rounded-md bg-indigo-500 inset-y-0 w-fit h-fit p-4">
                        <FontAwesomeIcon :icon='item.icon' class='h-8 w-8 text-white' aria-hidden='true' />
                    </dt>
                    
                    <dd class="flex flex-col justify-start items-baseline">
                        <Text v-model="item.name" class=""/>
                        <Text v-model="item.value" />
                    </dd>
                </div>
                <div class="text-center inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                            View all <span class="sr-only"> {{ item.name }} stats</span>
                        </a>
                    </div>
                </div>
            </div>
        </dl>
    </div>
</template>