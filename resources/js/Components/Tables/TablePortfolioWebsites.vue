<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Website} from "@/types/website";
import {computed, ref, watch} from "vue";
import Pusher from "pusher-js";
import ModalUpload from "@/Components/Utils/ModalUpload.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import { routeType } from '@/types/route'

const props = defineProps<{
    data: object,
    tab?:string,
    uploadRoutes: {
        upload: routeType
        history?: routeType
        download?: routeType
    }
}>()


function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.portfolio.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
        case 'org.portfolios.index':
            return route(
                'org.portfolios.show',
                [website.slug]);
    }
}


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

<template>{{ uploadRoutes }}
    <!-- Modal: Upload -->
    <!-- <ModalUpload
        v-model="isModalOpen"
        :routes="uploadRoutes"
        :isUploaded="isUploaded"
        @isUploaded="(val: any) => isUploaded = val"
    /> -->

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
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: website }">
            <Link :href="websiteRoute(website)" :id=" website['slug']" class="py-2 px-1">
                {{ website['slug'] }}
            </Link>
        </template>
    </Table>


</template>


