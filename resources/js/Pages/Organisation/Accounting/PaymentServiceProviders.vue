<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Tue, 28 Feb 2023 10:07:36 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup>
import {Head, Link} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import Table from '@/Components/Table/Table.vue';
import { capitalize } from "@/Composables/capitalize"

defineProps(['payment_service_providers', 'title', 'pageHead']);

</script>

<template layout="OrgApp">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Table :resource="payment_service_providers" class="mt-5">
        <template #cell(code)="{ item: payment_service_provider }">

        </template>
    </Table>
</template>
