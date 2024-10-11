<script setup lang="ts">
import { ref, watch, onMounted} from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import SideEditor from '@/Components/Workshop/Fields/SideEditor.vue';
import ScreenView from "@/Components/ScreenView.vue"
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { debounce } from 'lodash'
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import Publish from '@/Components/Utils/PublishWorkshop.vue'

import { routeType } from "@/types/route"
import { PageHeading as TSPageHeading } from '@/types/PageHeading'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

const props = defineProps<{
    pageHead: TSPageHeading
    title: string
    data: {
        footer: Object
    }
    autosaveRoute: routeType
}>()

const tabsBar = ref(0)
const isLoading =ref(false)
const usedTemplates = ref( props?.data?.data ? props.data.data :  footerTheme1)
const previewMode = ref(false)
const iframeSrc = route("customer.banners.workshop.footers.preview")
const iframeClass = ref('w-full h-full')
const openFullScreenPreview = () => window.open(iframeSrc.value, '_blank')
const socketLayout = SocketFooter();
const comment = ref('')
const isIframeLoading = ref(true)
const debouncedSendUpdate = debounce((data) => autoSave(data), 1000, { leading: false, trailing: true })

const onPublish = async (popover: Function) => {
    try {
        isLoading.value = true
        const response = await axios.post(route('customer.models.banner.workshop.footers.publish.footer'), {
            comment: comment.value,
            layout: usedTemplates.value
        })
        popover.close()
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message || 'Unknown error occurred'
        notify({
            title: 'Something went wrong.',
            text: errorMessage,
            type: 'error',
        })
    } finally {
        isLoading.value = false
    }
};


const setIframeView = (view: String) => {
    if (view === 'mobile') {
        iframeClass.value = 'w-[375px] h-[667px] mx-auto';
    } else if (view === 'tablet') {
        iframeClass.value = 'w-[768px] h-[1024px] mx-auto';
    } else {
        iframeClass.value = 'w-full h-full';
    }
}

const autoSave = async (data: Object) => {
    try {
        const response = await axios.patch(
            route("customer.models.banner.workshop.footers.autosave.footer"),
            { layout: data }
        )
    } catch (error: any) {
        console.error('error', error)
    }
}

watch(previewMode, (newVal) => {
    if (socketLayout) socketLayout.actions.send({ 
        previewMode: newVal, 
    })
}, { deep: true })

watch(usedTemplates, (newVal) => {
    if (newVal) debouncedSendUpdate(newVal)
}, { deep: true })

onMounted(()=>{
    if (socketLayout) socketLayout.actions.send({previewMode: previewMode})
})


const handleIframeError = () => {
    console.error('Failed to load iframe content.');
    isIframeLoading.value = false
    notify({
        title: 'Something went wrong in the preview.',
        text: 'failed to load preview',
        type: 'error',
    })
}

</script>

<template>
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other>
            <Publish :isLoading="isLoading" :is_dirty="true" v-model="comment"
                @onPublish="(popover) => onPublish(popover)" />
        </template>
    </PageHeading>

    <div class="h-[85vh] grid grid-flow-row-dense grid-cols-6">
        <div v-if="usedTemplates?.data" class="col-span-1 bg-[#F9F9F9] flex flex-col h-full border-r border-gray-300">
            <div class="flex h-full">
                <div class="w-[15%] bg-slate-200 ">
                    <div v-for="(tab, index) in usedTemplates?.data.bluprint"
                        class="py-2 px-3 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105"
                        :title="tab.name" @click="tabsBar = index"
                        :class="[tabsBar == tab.key ? 'bg-gray-300/70' : 'hover:bg-gray-200/60']" v-tooltip="tab.name">
                        <FontAwesomeIcon :icon="tab.icon" :class="[tabsBar == index ? 'text-indigo-300' : '']"
                            aria-hidden='true' />
                    </div>
                </div>
                <div class="w-[85%]">
                    <SideEditor v-model="usedTemplates.data.footer"
                        :bluprint="usedTemplates.data.bluprint[tabsBar].bluprint" />
                </div>
            </div>

        </div>

        <div class="bg-gray-100 h-full" :class="usedTemplates?.data ? 'col-span-5' : 'col-span-6'">
            <div class="h-full w-full bg-white">
                <div v-if="usedTemplates?.data" class="w-full h-full">
                    <div class="flex justify-between bg-slate-200 border border-b-gray-300">
                        <div class="flex">
                            <ScreenView @screenView="setIframeView" />
                            <div class="py-1 px-2 cursor-pointer" title="Desktop view" v-tooltip="'Preview'"
                                @click="openFullScreenPreview">
                                <FontAwesomeIcon :icon='faExternalLink' aria-hidden='true' />
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="text-xs" :class="[
                                previewMode ? 'text-slate-600' : 'text-slate-300'
                            ]">Preview</div>
                            <Switch @click="previewMode = !previewMode" :class="[
                                previewMode ? 'bg-slate-600' : 'bg-slate-300'
                            ]"
                                class="pr-1 relative inline-flex h-3 w-6 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
                                <span aria-hidden="true" :class="previewMode ? 'translate-x-3' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-full w-1/2 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out">
                                </span>
                            </Switch>                       
                        </div>
                    </div>

                        <div v-if="isIframeLoading" class="loading-overlay">
                         <div class="spinner"></div>
                         </div>

                         <iframe :src="iframeSrc" :title="props.title"
                        :class="[iframeClass]" @error="handleIframeError"
                        @load="isIframeLoading = false" />
                </div>
                <div v-else>
                    <EmptyState
                        :data="{ description: 'You need pick a template from list', title: 'Pick Footer Templates' }">
                        <template #button-empty-state>
                            <div class="mt-4 block">
                                <Button type="secondary" label="Templates" icon="fas fa-th-large"
                                    @click="isModalOpen = true"></Button>
                            </div>
                        </template>
                    </EmptyState>
                </div>
            </div>
        </div>
    </div>
</template>


<style scss>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1000;
}

.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
