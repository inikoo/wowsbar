<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { trans } from "laravel-vue-i18n"
import Icon from "@/Components/Icon.vue"
import { useLocaleStore } from "@/Stores/locale"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import { faTransporter3} from '@far/'

library.add(faTransporter3)

const props = defineProps<{
    data: object,
    tab?: string
}>()

const locale = useLocaleStore()
function mailshotRoute(mailshot: Mailshot) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.mailshots.index':
            return route(
                'org.crm.shop.prospects.mailshots.show',
                [route().params.shop, mailshot.slug])

    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(state)="{ item: mailshot }">
            <Icon :data="mailshot['state_icon']" class="px-1" />
        </template>

        <template #cell(subject)="{ item: mailshot }">
            <Link :href="mailshotRoute(mailshot)" class="specialUnderlineOrg py-1">
                <span v-if="mailshot.subject">{{ mailshot['subject'] }}</span>
                <span v-else class="italic opacity-50">{{ trans('Unknown') }}</span>
            </Link>
        </template>

        <template #cell(number_recipients)="{ item: mailshot }">
            <span v-if="!mailshot.start_sending_at" class="italic opacity-75">℮ {{ locale.number(mailshot['number_recipients']) }}</span>
            <span v-else-if="mailshot.start_sending_at && !mailshot.sent_at"> <font-awesome-icon class="animate-pulse" :icon="['far', 'transporter-3']" /> {{ locale.number(mailshot['number_recipients']) }}</span>
            <span v-else>{{ locale.number(mailshot['number_recipients']) }}</span>

        </template>
    </Table>
</template>


