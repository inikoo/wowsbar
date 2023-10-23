<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 26 Sep 2023 02:21:49 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import Stats from '@/Components/DataDisplay/Stats.vue';
import { capitalize } from "@/Composables/capitalize"
import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faNetworkWired,faCalendar,faStopwatch,faBuilding
} from '@fal/';

library.add(
    faNetworkWired,faCalendar,faStopwatch,faBuilding
);


defineProps(['title','pageHead','stats'])

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Stats class="m-4" :stats="stats" />
</template>

