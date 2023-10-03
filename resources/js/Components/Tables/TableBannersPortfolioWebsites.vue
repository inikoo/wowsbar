<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 03 Oct 2023 15:21:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { Website } from "@/types/website"

const props = defineProps<{
    data: object
    tab?: string
}>()


function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.banners.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
    }
}

</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(code)="{ item: website }">
            <Link :href="websiteRoute(website)" :id="website['slug']" class="py-2 px-1">
                {{ website['code'] }}
            </Link>
        </template>
    </Table>


</template>


