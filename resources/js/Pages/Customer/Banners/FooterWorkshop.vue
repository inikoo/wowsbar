<script setup lang="ts">
import { ref, watch, onMounted} from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import SideEditor from '@/Components/Workshop/Fields/SideEditor.vue';
import ScreenView from "@/Components/ScreenView.vue"
import { SocketFooter } from "@/Composables/SocketWebBlock"


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
const usedTemplates = ref(footerTheme1)
const previewMode = ref(false)
const iframeSrc = route("customer.banners.workshop.footers.preview")
const iframeClass = ref('w-full h-full')
const openFullScreenPreview = () => window.open(iframeSrc.value, '_blank')
const socketLayout = SocketFooter();


const setIframeView = (view: String) => {
    if (view === 'mobile') {
        iframeClass.value = 'w-[375px] h-[667px] mx-auto';
    } else if (view === 'tablet') {
        iframeClass.value = 'w-[768px] h-[1024px] mx-auto';
    } else {
        iframeClass.value = 'w-full h-full';
    }
}

watch(previewMode, (newVal) => {
    if (socketLayout) socketLayout.actions.send({ previewMode: newVal })
}, { deep: true })

console.log(usedTemplates.value)


onMounted(()=>{
    const channelName = `footer.preview`
	const chanel = window.Echo.join(channelName).whisper("otherIsNavigating", { data: 'sdsd' })
	console.log("funcition",chanel)
})
</script>

<template>
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>

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
                <div class="w-[85%] overflow-auto">
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

                    <iframe :src="iframeSrc" :title="props.title"
                        :class="[iframeClass]" />
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


<style scss></style>
