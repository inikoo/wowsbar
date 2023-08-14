<!--
  -  Author: Jonathan Lopez <raul@inikoo.com>
  -  Created: Wed, 12 Oct 2022 16:50:56 Central European Summer Time, Benalmádena, Malaga,Spain
  -  Copyright (c) 2022, Jonathan Lopez
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import TableWebsites from "@/Pages/Tables/TableWebsites.vue";
import { capitalize } from "@/Composables/capitalize"
import {faWindowMaximize} from "../../../../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
library.add(faWindowMaximize)

const props = defineProps <{
    pageHead: object
    title: string
    data:object
}>()
</script>

<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableWebsites :data="data" />
</template>

