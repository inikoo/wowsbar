<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
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
        case 'org.customer-websites.index':
            return route(
                'org.customer-websites.show',
                [website.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: website }">
            <Link :href="websiteRoute(website)" :id=" website['slug']" class="py-2 px-1">
                {{ website['slug'] }}
            </Link>
        </template>
    </Table>


</template>


