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
library.add(faPhone, faEnvelope, faGlobe, faUser)
import { CalendarDaysIcon, CreditCardIcon, UserCircleIcon } from '@heroicons/vue/20/solid'

const props = defineProps<{
    data: any
}>()

import { CheckIcon, HandThumbUpIcon, UserIcon } from '@heroicons/vue/20/solid'

const timeline = [
    {
        id: 1,
        content: 'Contacting to',
        target: 'Marisca Apriliyani',
        href: '#',
        date: 'Sep 20',
        datetime: '2020-09-20',
        icon: UserIcon,
        iconBackground: 'bg-gray-400',
    },
    {
        id: 2,
        content: 'Advanced to phone screening by',
        target: 'Bethany Blake',
        href: '#',
        date: 'Sep 22',
        datetime: '2020-09-22',
        icon: HandThumbUpIcon,
        iconBackground: 'bg-blue-500',
    },
    {
        id: 3,
        content: 'Completed phone screening with',
        target: 'Martha Gardner',
        href: '#',
        date: 'Sep 28',
        datetime: '2020-09-28',
        icon: CheckIcon,
        iconBackground: 'bg-green-500',
    },
    {
        id: 4,
        content: 'Advanced to interview by',
        target: 'Bethany Blake',
        href: '#',
        date: 'Sep 30',
        datetime: '2020-09-30',
        icon: HandThumbUpIcon,
        iconBackground: 'bg-blue-500',
    },
    {
        id: 5,
        content: 'Completed interview with',
        target: 'Katherine Snyder',
        href: '#',
        date: 'Oct 4',
        datetime: '2020-10-04',
        icon: CheckIcon,
        iconBackground: 'bg-green-500',
    },
]

</script>

<template>
    <!-- <pre>{{ data }}</pre> -->
    <div class="px-4 py-4 space-y-8">
        <div class="min-w-96 text-gray-600 w-fit">
            <h2 class="sr-only">Summary</h2>
            <div class="rounded-lg bg-gray-50 shadow ring-1 ring-gray-900/5">
                <dl class="p-6">
                    <div class="flex justify-between gap-x-4">
                        <div class="flex-auto">
                            <dd class="text-lg font-semibold leading-6">{{ data.info.name }}</dd>
                            <dd class="text-xs text-gray-400 whitespace-nowrap italic">
                                Created at {{ useFormatTime(data.info.created_at, { localeCode: 'enUS', formatTime: 'hms' }) }}
                            </dd>
                            <dd class="text-xs text-gray-400 whitespace-nowrap italic">
                                Updated at {{ useFormatTime(data.info.updated_at, { localeCode: 'enUS', formatTime: 'hms' }) }}
                            </dd>
                        </div>
                        <div class="flex flex-wrap justify-end items-end gap-x-0.5 gap-y-1 min-w-1/2 w-fit">
                            <!-- <Tag stringToColor label="Paid" />
                            <Tag stringToColor label="dddddddddddddddddddddddddddddddd" /> -->
                        </div>
                    </div>

                    <!-- Information -->
                    <div class="w-full flex flex-col gap-y-2 mt-4 pt-4 border-t border-gray-900/10">
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

        <div class="flow-root px-4">
            <ul role="list" class="">
                <li v-for="(event, eventIdx) in timeline" :key="event.id" class="relative pb-8">
                    <!-- Line  -->
                    <span v-if="eventIdx !== timeline.length - 1" class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-400" aria-hidden="true" />
                    <div class="relative flex space-x-3">
                        <!-- Icon -->
                        <span :class="[event.iconBackground, 'h-8 w-8 rounded-full flex items-center justify-center ring ring-gray-200']">
                            <!-- <component :is="event.icon" class="h-5 w-5 text-white" aria-hidden="true" /> -->
                            <FontAwesomeIcon icon='' class='h-5 aspect-square text-white' aria-hidden='true' />
                        </span>
                        
                        <!-- Description and Date -->
                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                            <div>
                                <p class="text-sm text-gray-500">
                                    {{ event.content }}
                                    <a :href="event.href" class="font-medium text-gray-900">
                                        {{ event.target }}
                                    </a>
                                </p>
                            </div>
                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                <time :datetime="event.datetime">{{ event.date }}</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>