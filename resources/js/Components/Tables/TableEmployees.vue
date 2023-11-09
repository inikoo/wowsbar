<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
// import JobPositionBadges from "@/Components/Elements/Badges/JobPositionBadges.vue"
import Icon from "@/Components/Icon.vue"
import Tag from '@/Components/Tag.vue'

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
        <template #cell(slug)="{ item: employee }">
            <Link :href="employeeRoute(employee)" class="py-1 specialUnderlineOrg">
                {{ employee['slug'] }}
            </Link>
        </template>

        <template  #cell(state)="{ item: employee }">
            <Icon :data="employee['state_icon']"/>
        </template>

        <template #cell(positions)="{ item: employee }">
            <div class="flex gap-x-1.5">
                <Link v-for="(position, key) in employee.positions" :key="key"
                    :href="route('org.hr.job-positions.show', position.slug)"
                    :title="position.name" class="inline-flex">
                    <Tag :label="position.name" stringToColor />
                </Link>
            </div>
        </template>


    </Table>
</template>
