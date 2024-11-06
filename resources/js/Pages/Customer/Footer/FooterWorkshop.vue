<script setup lang="ts">
import { ref, watch, IframeHTMLAttributes } from 'vue'
import { Head, router } from '@inertiajs/vue3'
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
import ProgressSpinner from 'primevue/progressspinner';
import Dialog from 'primevue/dialog';
import ListBlock from '@/Components/ListBlock.vue'
import Image from '@/Components/Image.vue'
import EmptyState from '@/Components/Utils/EmptyState.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'


import { routeType } from "@/types/route"
import { PageHeading as TSPageHeading } from '@/types/PageHeading'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { faThLarge } from '@fas';
import { library } from '@fortawesome/fontawesome-svg-core'
import ListItem from '@tiptap/extension-list-item'
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload, faThLarge)

const props = defineProps<{
    pageHead: TSPageHeading
    title: string
    data: {
        footer: Object
    }
    autosaveRoute: routeType
    publishRoute: routeType
    previewRoute: routeType
    web_blocks: {
        data: Array<any>
    }
}>()
const tabsBar = ref(0)
const isLoading = ref(false)
const usedTemplates = ref(props.data.data)
const previewMode = ref(false)
const iframeSrc = route(props.previewRoute.name, props.previewRoute.parameters)
const iframeClass = ref('w-full h-full')
const openFullScreenPreview = () => window.open(iframeSrc + '?fullscreen=true', '_blank');
const comment = ref('')
const visible = ref(false);
const isIframeLoading = ref(false)
const debouncedSendUpdate = debounce((data) => autoSave(data), 1000, { leading: false, trailing: true })
const saveCancelToken = ref<Function | null>(null)

const onPublish = async (popover: Function) => {
    try {
        isLoading.value = true
        const response = await axios.post(route(props.publishRoute.name, props.publishRoute.parameters), {
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
    router.patch(
        route(props.autosaveRoute.name, props.autosaveRoute.parameters),
        { layout: data },
        {
            onFinish: () => {
                saveCancelToken.value = null
            },
            onCancelToken: (cancelToken) => {
                saveCancelToken.value = cancelToken.cancel
            },
            onCancel: () => {
                console.log('The saving progress canceled.')
            },
            onError: (error) => {
                notify({
                    title: trans('Something went wrong.'),
                    text: error.message,
                    type: 'error',
                })
            },
            preserveScroll: true,
            preserveState: true,
        }
    )
}

watch(previewMode, (newVal) => {
    /* if (socketLayout) socketLayout.actions.send({
        previewMode: newVal,
    }) */
    sendToIframe({ key: 'previewMode', value: newVal })
}, { deep: true })

watch(usedTemplates, (newVal) => {
    if (saveCancelToken.value) {
        saveCancelToken.value()
    }
    if (newVal) debouncedSendUpdate(newVal)

}, { deep: true })

const _iframe = ref<IframeHTMLAttributes | null>(null)
const sendToIframe = (data: any) => {
    _iframe.value?.contentWindow.postMessage(data, '*')
}

/* onMounted(()=>{
    if (socketLayout) socketLayout.actions.send({ previewMode: previewMode.value })
}) */


const handleIframeError = () => {
    isIframeLoading.value = false
    notify({
        title: 'Something went wrong in the preview.',
        text: 'failed to load preview',
        type: 'error',
    })
}

const pickTemplate = (template) =>{
    console.log(template)
    usedTemplates.value = template
    visible.value = false
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

    <div class="h-[85vh] grid grid-flow-row-dense grid-cols-8">
        <div v-if="usedTemplates?.data" class="col-span-2 bg-[#F9F9F9] flex flex-col h-full border-r border-gray-300">
            <div class="flex h-full">
                <div class="w-fit bg-slate-200 ">
                    <div v-for="(tab, index) in usedTemplates?.blueprint"
                        class="py-2 px-3 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105"
                        :title="tab.name" @click="tabsBar = index"
                        :class="[tabsBar == tab.key ? 'bg-gray-300/70' : 'hover:bg-gray-200/60']" v-tooltip="tab.name">
                        <FontAwesomeIcon :icon="tab.icon" :class="[tabsBar == index ? 'text-indigo-300' : '']"
                            aria-hidden='true' />
                    </div>
                </div>
                <div class="w-full">
                    <SideEditor v-model="usedTemplates.data.fieldValue"
                        :blueprint="usedTemplates.blueprint[tabsBar].blueprint" />
                </div>
            </div>

        </div>

        <div class="bg-gray-100 h-full" :class="usedTemplates?.data ? 'col-span-6' : 'col-span-8'">
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

                            <div class="py-1 px-2 cursor-pointer" title="template" v-tooltip="'Template'">
                                <FontAwesomeIcon :icon="faThLarge" aria-hidden='true' @click="visible = true" />
                            </div>
                        </div>
                    </div>
                    <div v-if="isIframeLoading" class="loading-overlay">
                        <ProgressSpinner />
                    </div>

                    <iframe :src="iframeSrc" :title="props.title" :class="[iframeClass]" @error="handleIframeError"
                        @load="isIframeLoading = false" ref="_iframe" />
                </div>
                <div v-else>
                    <EmptyState
                        :data="{ description: 'You need pick a template from list', title: 'Pick Footer Templates' }">
                        <template #button-empty-state>
                            <div class="mt-4 block">
                                <Button type="secondary" label="Templates" icon="fas fa-th-large"
                                    @click="visible = true"></Button>
                            </div>
                        </template>
                    </EmptyState>
                </div>
            </div>
        </div>
    </div>


    <Dialog v-model:visible="visible" modal header="List Template" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <ListBlock :onSelectBlock="pickTemplate" :webBlockTypes="web_blocks.data.filter((item)=>item.component == 'footer')" >
            <template #image="{ block }">
                <div @click="() => pickTemplate(block)"
                    class="min-h-16 w-full aspect-[2/1] overflow-hidden flex items-center bg-gray-100 justify-center border border-gray-300 hover:border-indigo-500 rounded cursor-pointer">
                    <div class="w-auto shadow-md">
                        <Image :src="block.screenshot" class="object-contain" />
                    </div>
                </div>
            </template>
        </ListBlock>
    </Dialog>
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
