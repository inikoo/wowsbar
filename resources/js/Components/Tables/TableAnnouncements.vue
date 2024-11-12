<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import {useLocaleStore} from '@/Stores/locale'
import {Banner} from "@/types/banner";
import {Link} from "@inertiajs/vue3";

const locale = useLocaleStore()

const props = defineProps<{
    data: object,
    tab?: string
}>()

function announcementRoute(announcement) {
    return route(
        'customer.portfolio.announcements.show',
        [announcement.ulid]);
}
</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(ulid)="{ item: announcement }">
            <Link :href="announcementRoute(announcement)" :id="announcement['ulid']" class="specialUnderlineCustomer py-1 px-2 whitespace-nowrap">
                {{announcement['ulid']}}
            </Link>
        </template>

        <template #cell(name)="{ item: announcement }">
            {{announcement['name']}}
        </template>
    </Table>
</template>
