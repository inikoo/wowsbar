<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue';
import {useLocaleStore} from "@/Stores/locale";
import {Link} from "@inertiajs/vue3";

const props = defineProps<{
    data: object,
    tab?: string
}>()
const locale = useLocaleStore()

function listRoute(parent: object)
{
    return route('org.crm.shop.prospects.lists.show', {query: parent['slug'], shop: route().params.shop})
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: prospect_list }">
            <Link :href="listRoute(prospect_list)" :id="prospect_list['slug']" class="specialUnderlineCustomer py-4 px-2 whitespace-nowrap">
                {{ prospect_list['name'] }}
            </Link>
        </template>
        <template #cell(number_items)="{ item: prospect_list }">
            {{locale.number(prospect_list['number_items'])}}
        </template>
    </Table>
</template>


