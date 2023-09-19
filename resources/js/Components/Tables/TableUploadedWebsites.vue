<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Website} from "@/types/website";

const props = defineProps<{
    data: object,
    tab?:string
}>()


function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.portfolio.websites.uploads.index':
            return route(
                'customer.portfolio.websites.uploads.show',
                [website.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(original_filename)="{ item: website }">
            <Link :href="websiteRoute(website)" :id=" website['original_filename']" class="py-2 px-1">
                {{ website['original_filename'] }}
            </Link>
        </template>
    </Table>


</template>


