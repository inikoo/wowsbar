<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { useLocaleStore } from "@/Stores/locale"
import { faEnvelope, faAsterisk } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import {Link} from "@inertiajs/vue3";
import { useFormatTime } from '@/Composables/useFormatTime'

library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()

function listRoute(dispatchedEmail) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.mailshots.show':
            return route(
                'org.crm.shop.prospects.show',
                [route().params['shop'], dispatchedEmail.slug]);

    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(contact_name)="{ item: dispatchedEmail }">
            <Link :href="listRoute(dispatchedEmail)"  class="specialUnderlineOrg py-1">
                {{ dispatchedEmail.contact_name }}
            </Link>
        </template>
        <template #cell(last_contacted_at)="{ item: dispatchedEmail }">
            <div class="text-gray-500 whitespace-nowrap">{{ useFormatTime(dispatchedEmail.last_contacted_at, {formatTime: 'hm'}) }}</div>
        </template>
    </Table>
</template>


