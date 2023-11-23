<script setup lang='ts'>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUser, faCalendarAlt, faEnvelope, faPhone, faGlobe } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
import { useFormatTime } from '@/Composables/useFormatTime';
import CopyButton from '@/Components/Utils/CopyButton.vue';
import { capitalize } from '@/Composables/capitalize'
library.add(faUser, faCalendarAlt, faEnvelope, faPhone, faGlobe)

const props = defineProps<{
    data: {
        name: string
        date: string
        email: string
        phone: string
        website: string
        tags: string[]
        state: string
    }
}>()

</script>

<template>
    <div v-tooltip="capitalize((data.state).replace('-', ' '))" class="w-fit rounded-lg shadow-sm"
        :class="[
            { 'ring-1 ring-gray-500/30': data.state === 'no-contacted' },
            { 'bg-lime-50 ring-1 ring-lime-700/50': data.state === 'interested' },
            { 'bg-rose-200 ring-1 ring-rose-700/50': data.state === 'not-interested' },
        ]"
    >
        <dl class="w-fit flex flex-col divide-y divide-gray-900/5">
            <div class="flex justify-between items-end w-full px-6 py-6">
                <div class="flex flex-col items-end">
                    <!-- <dt class="text-sm font-semibold leading-6">Amount</dt> -->
                    <dd class="bg-gradient-to-br from-slate-900 via-purple-800 to-slate-900 bg-clip-text text-transparent mt-1 text-xl font-semibold whitespace-nowrap">
                        {{ data.name }}
                    </dd>
                </div>

                <!-- Section: Tag -->
                <div v-if="data.tags" class="w-[50%] flex justify-end">
                    <dt class="sr-only">Status</dt>
                    <div class="flex justify-end flex-wrap w-fit gap-x-0.5 gap-y-1">
                        <Tag v-for="tag in data.tags" :label="tag" stringToColor />
                    </div>
                </div>
            </div>

            <!-- Section: Contact Information -->
            <div class="flex flex-col w-full py-2 text-gray-500">
                <!-- Info: Date -->
                <div v-if="data.date" class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">Date</span>
                        <FontAwesomeIcon icon='fal fa-calendar-alt' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ useFormatTime(data.date) }}</dd>
                    <CopyButton :text="useFormatTime(data.date)" />
                </div>
                
                <!-- Info: Email -->
                <div v-if="data.email" class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">Email</span>
                        <FontAwesomeIcon icon='fal fa-envelope' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.email ?? '-' }}</dd>
                    <CopyButton :text="data.email" />
                </div>

                <!-- Info: Phone -->
                <div v-if="data.phone" class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">Phone</span>
                        <FontAwesomeIcon icon='fal fa-phone' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.phone ?? '-' }}</dd>
                    <CopyButton :text="data.phone" />
                </div>

                <!-- Info: Website -->
                <div v-if="data.website" class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">Website</span>
                        <FontAwesomeIcon icon='fal fa-globe' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.website ?? '-' }}</dd>
                    <CopyButton :text="data.website" />
                </div>

            </div>
        </dl>
    </div>
</template>