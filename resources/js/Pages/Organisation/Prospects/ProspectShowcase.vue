<script setup lang='ts'>
import { useLayoutStore } from '@/Stores/layout'
import { trans } from 'laravel-vue-i18n'
import Image from '@/Components/Image.vue'
import { useCopyText } from '@/Composables/useCopyText'
import { useFormatTime } from '@/Composables/useFormatTime'
import ContactCard from '@/Components/DataDisplay/ContactCard.vue'
import Tag from '@/Components/Tag.vue';
import Timeline from '@/Components/Utils/Timeline.vue'
import Feeds from '@/Components/Utils/Feeds.vue'

const props = defineProps<{
    data: any
}>()

const dataContact = {
    name: props.data.info.name,
    date: props.data.info.created_at,
    email: props.data.info.email,
    phone: props.data.info.phone,
    website: props.data.info.website,
    tags: props.data.info.tags
}

const dataFeeds = [
    { name: 'Chelsea Hagon', action: 'created', dateTime: '2023-01-23T10:32' },
    { name: 'Chelsea Hagon', action: 'updated', dateTime: '2023-01-23T11:03' },
    { name: 'Chelsea Hagon', action: 'deleted', dateTime: '2023-01-23T11:24' },
    {
        name: 'Chelsea Hagon',
        action: 'commented',
        comment: 'Called client, they reassured me the invoice would be paid by the 25th.',
        dateTime: '2023-01-23T15:56',
    },
    { name: 'Alex Curren', action: 'updated', dateTime: '2023-01-24T09:12' },
    { name: 'Alex Curren', action: 'published', dateTime: '2023-01-24T09:20' },
]

</script>

<template>
    <div class="grid grid-cols-8 px-4 py-4">
        <!-- Section: Contact Card -->
        <div class="col-span-5 space-y-8">
            <ContactCard :data="dataContact"/>
        </div>

        <!-- Section: Feeds -->
        <div class="col-span-3 py-3 pl-4 pr-8 rounded-md border border-gray-200 shadow">
            <div class="font-semibold text-lg mb-4">{{ trans("Recent activity") }}</div>
            <Feeds :dataFeeds="dataFeeds" />
        </div>
    </div>
</template>