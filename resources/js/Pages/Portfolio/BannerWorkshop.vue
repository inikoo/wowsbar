<script setup lang="ts">
import {Head} from '@inertiajs/vue3';


import PageHeading from '@/Components/Headings/PageHeading.vue';

import { capitalize } from "@/Composables/capitalize"



const props = defineProps<{
    title: string,
    pageHead: object,
    banner: object

}>()




</script>


<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>

    {{banner.layout}}

</template>

