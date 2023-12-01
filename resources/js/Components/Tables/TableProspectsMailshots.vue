<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Pusher from 'pusher-js'
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { trans } from "laravel-vue-i18n"
import Icon from "@/Components/Icon.vue"
import { useLocaleStore } from "@/Stores/locale"
import { useFormatTime } from '@/Composables/useFormatTime'
import LabelMailshotVerification from "@/Components/Mailshots/LabelMailshotVerification.vue"
import { SenderEmail } from '@/types/SenderEmail'

const props = defineProps<{
    data: {
        data: {
            slug: string
            number_delivered: string
            percentage_bounced: string
            percentage_opened: string
            percentage_clicked: string
            percentage_unsubscribe: string
            percentage_spam: string
        }[]
    },
    tab?: string
    senderEmail?: SenderEmail
}>()

const reactivePropsData = { ...props.data }

const locale = useLocaleStore()
function mailshotRoute(mailshot: { slug: string }) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.mailshots.index':
            return route(
                'org.crm.shop.prospects.mailshots.show',
                [route().params.shop, mailshot.slug])

    }
}

// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('hydrate.sent.emails')
reactivePropsData.data.forEach((item) => {
    channel.bind(`mailshot.${item.slug}`, (data: any) => {
        // After received data from Pusher, set value to data in table
        item.number_delivered = data.mailshot.stats.number_delivered_emails
        item.percentage_bounced = (((data.mailshot.stats.number_hard_bounced_emails + data.mailshot.stats.number_soft_bounced_emails) / data.mailshot.stats.number_dispatched_emails) * 100).toFixed(1) + '%'
        item.percentage_opened = (data.mailshot.stats.number_opened_emails / data.mailshot.stats.number_dispatched_emails * 100).toFixed(1) + '%'
        item.percentage_clicked = (data.mailshot.stats.number_clicked_emails / data.mailshot.stats.number_dispatched_emails * 100).toFixed(1) + '%'
        item.percentage_unsubscribe = (data.mailshot.stats.number_unsubscribed_emails / data.mailshot.stats.number_dispatched_emails * 100).toFixed(1) + '%'
        item.percentage_spam = (data.mailshot.stats.number_spam_emails / data.mailshot.stats.number_dispatched_emails * 100).toFixed(1) + '%'
    })
})

</script>

<template>
    <LabelMailshotVerification v-if="senderEmail?.state != 'verified' || !senderEmail" :message="senderEmail?.message" />

    <Table :resource="data" :name="tab">
        <template #cell(date)="{ item: mailshot }">
            <span class="whitespace-nowrap text-gray-500">{{ useFormatTime(mailshot.sent_at, { formatTime: 'hms' }) }}</span>
        </template>

        <template #cell(state)="{ item: mailshot }">
            <Icon :data="mailshot.state_icon" class="px-1" />
        </template>

        <template #cell(subject)="{ item: mailshot }">
            <Link :href="mailshotRoute(mailshot)" class="specialUnderlineOrg py-1">
                <span v-if="mailshot.subject">{{ mailshot.subject }}</span>
                <span v-else class="italic opacity-50">{{ trans('Unknown') }}</span>
            </Link>
        </template>

        <template #cell(number_recipients)="{ item: mailshot }">
            <span v-if="!mailshot.recipients_stored_at" class="italic opacity-75">
                ℮ {{ locale.number(mailshot.number_recipients) }}
            </span>
            <span v-else>{{ locale.number(mailshot.number_recipients) }}</span>
        </template>
    </Table>
</template>


