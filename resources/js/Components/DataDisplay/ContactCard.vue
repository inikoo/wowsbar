<script setup lang='ts'>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUser, faCalendarAlt, faEnvelope, faPhone, faGlobe } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
import { useFormatTime } from '../../Composables/useFormatTime';
library.add(faUser, faCalendarAlt, faEnvelope, faPhone, faGlobe)

const props = defineProps<{
    data: {
        name: string
        date: string
        email: string
        phone: string
        website: string
        tags: string[]
    }
}>()

</script>

<template>
    <div class="w-fit rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">
        <dl class="w-fit flex flex-col divide-y divide-gray-900/5">
            <div class="flex justify-between items-end w-full px-6 py-6">
                <div class="flex flex-col items-end">
                    <!-- <dt class="text-sm font-semibold leading-6">Amount</dt> -->
                    <dd class="mt-1 text-lg font-semibold whitespace-nowrap">{{ data.name }}</dd>
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
                <div class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">User</span>
                        <FontAwesomeIcon icon='fal fa-calendar-alt' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ useFormatTime(data.date) }}</dd>
                </div>
                
                <div class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">User</span>
                        <FontAwesomeIcon icon='fal fa-envelope' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.email ?? '-' }}</dd>
                </div>

                <div class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">User</span>
                        <FontAwesomeIcon icon='fal fa-phone' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.phone ?? '-' }}</dd>
                </div>

                <div class="flex items-center w-full gap-x-4 px-6 py-3">
                    <dt class="flex items-center">
                        <span class="sr-only">User</span>
                        <FontAwesomeIcon icon='fal fa-globe' fixed-width class='h-5 aspect-square text-gray-400' aria-hidden='true' />
                    </dt>
                    <dd class="flex items-center leading-none">{{ data.website ?? '-' }}</dd>
                </div>

            </div>
        </dl>
    </div>
</template>