<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { PageHeading as TSPageHeading } from '@/types/PageHeading'
import TableTimeTrackings from "@/Components/Tables/TableTimeTrackings.vue";

const props = defineProps <{
    pageHead: TSPageHeading
    title: string
    data: object

}>()

// To handle Modal on click 'upload' button
const dataModal = reactive({
    isModalOpen: false
})
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />

    <PageHeading :data="pageHead">
    </PageHeading>

    <TableTimeTrackings :data="data" />

</template>
