<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 25 Sep 2023 08:53:42 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faNetworkWired,faCalendar,faStopwatch,faBuilding
} from '@fal/';
import TablePortfolioSocialAccounts from "@/Components/Tables/TablePortfolioSocialAccounts.vue";

library.add(
    faNetworkWired,faCalendar,faStopwatch,faBuilding
);

defineProps(['title','pageHead','data'])

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TablePortfolioSocialAccounts :data="data"></TablePortfolioSocialAccounts>
</template>
