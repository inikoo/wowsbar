<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 26 Sep 2023 23:12:15 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Department} from "@/types/shop-department";

const props = defineProps<{
    data: object,
    tab?:string
}>();


function departmentRoute(department: Department) {
    switch (route().current()) {
        default:
            return route(
                'org.shops.departments.show',
                [department.slug]);
    }
}

</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: department }">
            <Link :href="departmentRoute(department)">
                {{ department['slug'] }}
            </Link>
        </template>
    </Table>
</template>


