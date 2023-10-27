<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 26 Oct 2023 23:04:42 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Prospect} from "@/types/prospect";
import {trans} from "laravel-vue-i18n";

const props = defineProps<{
    data: object,
    tab?: string
}>()


function prospectRoute(prospect: Prospect) {
    // console.log(route().current());
    switch (route().current()) {
        case 'org.crm.shop.prospects.index':
            return route(
                'org.crm.shop.prospects.show',
                [prospect.shop.slug, prospect.slug]);
        default:
            return route(
                'org.crm.prospects.show',
                [prospect.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(named)="{ item: prospect }">
            <Link :href="prospectRoute(prospect)">
                <span v-if="prospect.name">{{ prospect['name'] }}</span><span v-else class="italic opacity-50">{{trans('Unknown')}}</span>
            </Link>
        </template>
    </Table>
</template>


