<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 00:01:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {capitalize} from "@/Composables/capitalize"
import {faTasksAlt, faIndent} from '@fal/'
import {library} from '@fortawesome/fontawesome-svg-core';
import FlatTreeMap from "@/Components/Navigation/FlatTreeMap.vue";

library.add(faTasksAlt, faIndent);

const props = defineProps<{
    title: string,
    pageHead: any,
    flatTreeMaps: object,
}>()

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <FlatTreeMap class="mt-3 mx-4" v-for="(treeMap,idx) in flatTreeMaps" :key="idx" :nodes="treeMap"/>
</template>

