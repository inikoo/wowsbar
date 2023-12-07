<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { useLocaleStore } from "@/Stores/locale"
import Button from '@/Components/Elements/Buttons/Button.vue'

import { faEnvelope, faAsterisk } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import {Link} from "@inertiajs/vue3";
import QueryInformatics from '@/Components/Queries/QueryInformatics.vue'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()

function listRoute(prospect_list) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.lists.index':
        case 'org.crm.shop.customers.lists.index':
            return route(
                'org.crm.shop.prospects.lists.show',
                [route().params['shop'], prospect_list.slug]);
        default:
            return route(
                'org.crm.lists.show',
                [prospect_list.slug]);
    }
}

</script>

<template>
    <!-- <pre>{{ usePage().props }}</pre> -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: prospect_list }">
            <Link :href="listRoute(prospect_list)">
                {{ prospect_list['name'] }}
            </Link>
        </template>
        <!-- Cell: Prospects (Number Items) -->
        <template #cell(number_items)="{ item: prospect_list }">
            <span class="tabular-nums">{{ locale.number(prospect_list['number_items']) }}</span>
        </template>

        <!-- Cell: Description -->
        <template #cell(description)="{ item: prospect_list }">
        <QueryInformatics :option="prospect_list" />
        </template>

        <!-- {{ prospect_list.arguments }} -->
        <template #cell(actions)="{ item: prospect_list }">
            <div class="flex gap-x-2 items-center">
                <Button :style="prospect_list.arguments != false ? 'primary' : 'tertiary'" label="Send Mailshot" size="xs" />
            </div>
        </template>
    </Table>
</template>


