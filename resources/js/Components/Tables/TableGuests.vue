<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import JobPositionBadges from "@/Components/Elements/Badges/JobPositionBadges.vue";

const props = defineProps<{
    data: object,
    tab?:string,
}>()


function guestRoute(guest) {
    switch (route().current()) {
        case 'org.sysadmin.guests.index':
            return route(
                'org.sysadmin.guests.show',
                [guest.slug]);

    }
}

</script>

<template>
    <Table :resource="data" :name="tab"  class="mt-5">
        <template #cell(alias)="{ item: guest }">
            <Link :href="guestRoute(guest)" class="specialUnderlineOrg py-1">
                {{ guest['alias'] }}
            </Link>
        </template>
        <template #cell(positions)="{ item: guest }">
            <job-position-badges :job_positions="guest['positions']"/>
        </template>
    </Table>
</template>


