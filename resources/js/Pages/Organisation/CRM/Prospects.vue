<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Tue, 28 Feb 2023 10:07:36 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import TableProspects from "@/Components/Tables/TableProspects.vue";
import { capitalize } from "@/Composables/capitalize"
import {computed, ref, watch} from "vue";
import Pusher from "pusher-js";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import Button from "@/Components/Elements/Buttons/Button.vue";
import ModalUpload from "@/Components/Utils/ModalUpload.vue";

const props = defineProps<{
    data: object
    title: string
    pageHead: object
}>()


const isProgress = ref(false)
const isUploaded = ref(false)
const isModalOpen = ref(false)
const dataPusher = ref({
    data: {
        total_uploads: 0,
        total_complete: 0
    }
})

// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('uploads.org')
channel.bind('Prospect', (data: any) => {
    dataPusher.value = data
    // console.log("==============")
    // console.log(data)
})

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.total_uploads ? dataPusher.value?.data.total_complete/dataPusher.value?.data.total_uploads * 100 : 0
})

watch(compProgressBar, () => {
    compProgressBar.value >= 100 ? setTimeout(() => {
        isProgress.value = true
    }, 3000) : ''
})
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button0>
            <!-- Button Upload -->
            <Button @click="isModalOpen = true" :style="'secondary'" class="rounded-l-none border-none rounded-r-none flex gap-x-2"  alt="Import website via file">
                <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
                <span>Upload</span>
            </Button>
        </template>
    </PageHeading>
    <!-- Modal: Upload -->
    <ModalUpload
        v-model="isModalOpen"
        :routeUpload="pageHead.actions[0].buttons[0].route.name"
        :routeHistory="'org.models.prospects.upload'"
        :routeDownload="'org.crm.prospects.uploads.template.download'"
        :isUploaded="isUploaded"
        @isUploaded="(val: any) => isUploaded = val"
    />

    <!-- Progress Bar -->
    <div :class="isUploaded && !isProgress ? 'bottom-12' : '-bottom-12'" class="z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1">
        <div class="flex justify-center items-center flex-col gap-y-1">
            <div v-if="compProgressBar >=100">Finished!!🥳</div>
            <div v-else>Adding Prospects ({{ dataPusher.data.total_complete }}/<span class="font-semibold inline">{{ dataPusher.data.total_uploads }}</span>)</div>
            <div class="overflow-hidden rounded-full bg-gray-200 w-64">
                <div class="h-2 rounded-full bg-slate-600 transition-all duration-100 ease-in-out" :style="`width: ${compProgressBar}%`" />
            </div>
        </div>
        <div class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>
    <TableProspects :data="data" />
</template>

