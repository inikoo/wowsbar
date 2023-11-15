<script setup lang='ts'>
import { useLayoutStore } from '@/Stores/layout'
import { trans } from 'laravel-vue-i18n'
import Image from '@/Components/Image.vue'
import { useCopyText } from '@/Composables/useCopyText'
import { useFormatTime } from '@/Composables/useFormatTime'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPhone, faEnvelope, faGlobe, faUser } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
import Timeline from '@/Components/Utils/Timeline.vue'
library.add(faPhone, faEnvelope, faGlobe, faUser)

const props = defineProps<{
    data: any
}>()

const timelineSort = props.data.timeline.sort((a, b) => {
    return a.value - b.value;
})

const timelineFilterNull = Object.groupBy(timelineSort.filter(obj => obj.value), (item) => {
    return item.value
})

const titlesObject = {};
for (const key in timelineFilterNull) {
  titlesObject[key] = timelineFilterNull[key].map(item => item.title);
}


</script>

<template>
    <Timeline :options="titlesObject"/>
    <!-- <pre>{{ timelineFilterNull }}</pre> -->
    <div class="px-4 py-4 space-y-8">
        <div class="min-w-96 text-gray-600 w-fit">
            <h2 class="sr-only">Summary</h2>
            <div class="rounded-lg bg-gray-50 shadow ring-1 ring-gray-900/5">
                <dl class="p-6">
                    <div class="flex justify-between gap-x-4">
                        <div class="flex-auto">
                            <dd class="text-lg font-semibold leading-6">{{ data.info.name }}</dd>
                            <!-- <dd class="text-xs text-gray-400 whitespace-nowrap italic">
                                Created at {{ useFormatTime(data.info.created_at, { localeCode: 'enUS', formatTime: 'hms' }) }}
                            </dd>
                            <dd class="text-xs text-gray-400 whitespace-nowrap italic">
                                Updated at {{ useFormatTime(data.info.updated_at, { localeCode: 'enUS', formatTime: 'hms' }) }}
                            </dd> -->
                        </div>
                        <div class="flex flex-wrap justify-end items-end gap-x-0.5 gap-y-1 min-w-1/2 w-fit">
                            <!-- <Tag stringToColor label="Paid" />
                            <Tag stringToColor label="dddddddddddddddddddddddddddddddd" /> -->
                        </div>
                    </div>

                    <!-- Information -->
                    <div class="w-full flex flex-col gap-y-2 mt-4 pt-4 border-t border-gray-900/10 text-gray-500">
                        <!-- <div v-if="data.info.name" class="flex w-full gap-x-4">
                            <dt class="flex-none">
                                <FontAwesomeIcon fixed-width icon="fal fa-user" class="h-4 text-gray-400" aria-hidden="true" />
                            </dt>
                            <dd class="text-sm font-medium leading-6">{{ data.info.name }}</dd>
                        </div> -->

                        <div v-if="data.info.phone" class="flex w-full gap-x-4">
                            <dt class="flex-none">
                                <FontAwesomeIcon fixed-width icon="fal fa-phone" class="h-4 text-gray-400"
                                    aria-hidden="true" />
                            </dt>
                            <dd class="text-sm leading-6 text-gray-500">
                                <time datetime="2023-01-31">{{ data.info.phone }}</time>
                            </dd>
                        </div>

                        <div v-if="data.info.website" class="flex w-full gap-x-4">
                            <dt class="flex-none">
                                <FontAwesomeIcon fixed-width icon="fal fa-globe" class="h-4 text-gray-400"
                                    aria-hidden="true" />
                            </dt>
                            <dd class="text-sm leading-6">{{ data.info.website }}</dd>
                        </div>

                        <div v-if="data.info.email" class="flex w-full items-center gap-x-4">
                            <dt class="">
                                <FontAwesomeIcon fixed-width icon="fal fa-envelope" class="h-4 text-gray-400"
                                    aria-hidden="true" />
                            </dt>
                            <dd class="text-sm">{{ data.info.email }}</dd>
                        </div>

                    </div>
                </dl>

                <!-- <div class="border-t border-gray-900/5 px-6 py-6">
                    <a href="#" class="text-sm font-semibold leading-6">
                        Download receipt
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div> -->
            </div>
        </div>

        
    </div>
</template>