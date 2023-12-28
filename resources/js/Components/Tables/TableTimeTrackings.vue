<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'



const props = defineProps<{
    data: object,
    tab?:string
}>()


function employeeRoute(employee) {
    switch (route().current()) {
        case 'org.hr.employees.index':
            return route(
                'org.hr.employees.show',
                [employee.slug])

    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5"   >


    </Table>
</template>
