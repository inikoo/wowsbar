<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import ContactCard from '@/Components/DataDisplay/ContactCard.vue'
import Feeds from '@/Components/Utils/Feeds.vue'

const props = defineProps<{
    data: {
        info: {
            name: string
            created_at: string
            email: string
            phone: string
            website: string
            tags: string[],
            fail_status:string
        }
        feeds: {
            name: string
            action: string
            comment?: string
            dateTime: string
        }[]
        state: string
    }
}>()

const dataContact = {
    name: props.data.info.name,
    date: props.data.info.created_at,
    email: props.data.info.email,
    phone: props.data.info.phone,
    website: props.data.info.website,
    tags: props.data.info.tags,
    state: props.data.state
}

</script>

<template>
    <div class="grid md:grid-cols-8 px-4 py-4 gap-y-4 md:gap-y-0">
        <!-- Section: Contact Card -->
        <div class="order-2 md:order-none md:col-span-5 space-y-8">
            {{data.info.fail_status}}
            <ContactCard :data="dataContact"/>
        </div>


        <!-- Section: Feeds -->
        <div class="md:col-span-3 pt-3 pb-5 pl-4 pr-8 h-fit rounded-md border border-gray-200 shadow">
            <div class="font-semibold mb-4 text-gray-600">{{ trans("Recent activity") }}</div>
            <Feeds :dataFeeds="data.feeds" />
        </div>
    </div>
</template>
