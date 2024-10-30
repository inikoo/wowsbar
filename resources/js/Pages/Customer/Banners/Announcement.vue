<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:20:36 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { inject, provide, reactive, ref, toRaw, watch } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
// import Promo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
// import Information1 from '@/Components/Workshop/Announcement/Templates/Information/AnnouncementInformation1.vue'
import AnnouncementTemplateList from '@/Components/Workshop/Announcement/AnnouncementTemplateList.vue'


import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt } from '@fal'
import { faThLarge } from '@fas'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import AnnouncementSideEditor from '@/Components/Workshop/Announcement/AnnouncementSideEditor.vue'
import { notify } from '@kyvg/vue3-notification'
import ScreenView from '@/Components/ScreenView.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import { debounce } from 'lodash'
import Modal from '@/Components/Utils/Modal.vue'
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { routeType } from '@/types/route'

library.add(faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt, faThLarge)

const props = defineProps<{
    pageHead: {}
    title: string
    data: {},
    firstBanner?: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
    }
    announcement_data: {
        code: string
        id: number
        name: string
        ulid: string
        created_at: string
        updated_at: string
        icon: string
        close_button: {
            size: string
            text_color: string
            block_properties: {
                position: {
                    x: string
                    y: string
                    type: string  // 'absolute' | 'relative'
                }
            }
        }
    }
    announcement_list: {}
    routes_list: {
        publish_route: routeType
        update_route: routeType
        reset_route: routeType
    }
}>()


// const vvvv = async () => {
//     // const zzz = await axios.get('http://delivery.wowsbar.test/announcement.js');
//     fetch(`http://delivery.wowsbar.test/announcement/01J9T4KWNQJM9BMMPHKGKY0AVK`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok ' + response.statusText);
//             }
//             return response.json(); // or response.text() if expecting plain text
//         })
//         .then(data => {
//             console.log('from fetch delivery', data); // Process the data
//         })
//         .catch(error => {
//             console.error('There was a problem with the fetch operation:', error);
//         });

//     // console.log("axios:", zzz)
// }
// vvvv()

const isModalOpen = ref(false)

// const selectedBlockOpenPanel = ref(true)
// const isLoading = ref<string | boolean>(false)
// const comment = ref("")
// const openDrawer = ref<string | boolean>(false)
// const iframeSrc = ref(route('grp.websites.preview', [route().params['website'], route().params['webpage']]))
// const data = ref({ ...props.webpage })
// const iframeClass = ref('w-full h-full')
const isIframeLoading = ref(false)
// const _WebpageSideEditor = ref(null)


// const onPublish = async (action: {}, popover: {}) => {
//     try {
//         // Ensure action is defined and has necessary properties
//         if (!action || !action.method || !action.name || !action.parameters) {
//             throw new Error('Invalid action parameters')
//         }

//         isLoading.value = true

//         // Make sure route and axios are defined and used correctly
//         const response = await axios[action.method](route(action.name, action.parameters), {
//             comment: comment.value,
//             publishLayout: { blocks: data.value.layout }
//         })
//         popover.close()

//     } catch (error) {
//         // Ensure the error is logged properly
//         console.error('Error:', error)
//         const errorMessage = error.response?.data?.message || error.message || 'Unknown error occurred'
//         notify({
//             title: 'Something went wrong.',
//             text: errorMessage,
//             type: 'error',
//         })
//     } finally {
//         isLoading.value = false
//     }
// };

const isLoadingSave = ref(false)
const saveCancelToken = ref<Function | null>(null)
const onSave = () => {
    router.post(
        route(props.routes_list.update_route.name, props.routes_list.update_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onSuccess: () => {
                notify({
                    title: 'Success',
                    text: 'Data Announcement saved',
                    type: 'success',
                })
            },
            onError: (error) => {
                notify({
                    title: 'Something went wrong',
                    text: 'Failed to save Announcement data',
                    type: 'error',
                })
            },
            onCancelToken: (cclToken) => saveCancelToken.value = cclToken.cancel,
            preserveState: true,
            preserveScroll: true
        }
    )
}
const onPublish = () => {
    router.post(
        route(props.routes_list.publish_route.name, props.routes_list.publish_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onError: (error) => {
                notify({
                    title: 'Something went wrong',
                    text: error.message,
                    type: 'error',
                })
            },
            preserveState: true,
            preserveScroll: true
        }
    )
}
const onReset = () => {
    router.post(
        route(props.routes_list.reset_route.name, props.routes_list.reset_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onError: (error) => console.error('======', error),
            onCancelToken: (cclToken) => saveCancelToken.value = cclToken.cancel,
            preserveState: true,
            preserveScroll: true
        }
    )
}

const announcementData = ref(props.announcement_data)
provide('announcementData', announcementData.value)

// If fieldvalue have changes, then auto save
watch(announcementData, (newVal) => {
    if (newVal) {
        // If still on progress saving, cancel the save
        if (saveCancelToken.value) {
            saveCancelToken.value()
        }

        xxx()
    }
}, { deep: true })

const xxx = debounce(() => onSave(), 1000, { leading: false, trailing: true })


const isOnPublishState = inject('isOnPublishState', false)
const styleToRemove = isOnPublishState ? ['top'] : null
const _parentComponent = ref(null)

</script>

<template layout="CustomerApp">

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #iconRight v-if="isLoadingSave">
            <LoadingIcon />
        </template>

        <template #other>
            <div class="flex gap-x-2">
                <Button @click="onReset" label="Reset" v-tooltip="'Reset data to last publish'" :loading="isLoadingSave" :style="'negative'" icon="fal fa-undo-alt" />
                <Button @click="onSave" label="save" :loading="isLoadingSave" :style="'tertiary'" icon="fal fa-save" />
                <Button @click="onPublish" label="Publish" :loading="isLoadingSave" iconRight="fal fa-rocket-launch"/>
            </div>
        </template>
    </PageHeading>


    <div class="flex h-[86.7vh]">
        <!-- Section: Side editor -->
        <div class="w-[400px] py-2 px-3">
            <div class="w-full text-lg font-semibold flex items-center justify-between gap-3 border-b border-gray-300">
                <div class="flex items-center gap-3">
                    {{ trans('Announcement') }}
                </div>

                <div class="py-1 px-2 cursor-pointer" title="template" v-tooltip="'Template'"
                    @click="isModalOpen = true">
                    <FontAwesomeIcon icon="fas fa-th-large" aria-hidden='true' />
                </div>
            </div>

            <AnnouncementSideEditor v-if="1" />
        </div>

        <!-- Section: Preview -->
        <div v-if="true" class="w-full h-full flex flex-col py-2 px-3">
            <div class="flex justify-between">
                <!-- <div class="py-1 px-2 cursor-pointer md:hidden block" title="Desktop view" v-tooltip="'Navigation'">
                    <FontAwesomeIcon :icon='faBars' aria-hidden='true' @click="()=>openDrawer = true" />
                    <Drawer v-model:visible="openDrawer" :header="''" :dismissable="true">
                        <WebpageSideEditor ref="_WebpageSideEditor" :webpage="data" :webBlockTypeCategories="webBlockTypeCategories"
                            @update="sendBlockUpdate" @delete="sendDeleteBlock" @add="addNewBlock"
                            @order="sendOrderBlock"  @openBlockList="()=>{openDrawer = false, _WebpageSideEditor.isModalBlocksList = true}" />
                    </Drawer>
                </div> -->

                <!-- Section: Screenview -->
                <div class="flex">
                    <ScreenView @screenView="false" />
                    
                    <a
                        :href="`http://delivery.wowsbar.test/announcement/${announcementData.ulid}?iframe=true`"
                        target="_blank"
                        class="py-1 px-2 cursor-pointer"
                        title="Desktop view"
                        v-tooltip="'Preview'"
                    >
                        <FontAwesomeIcon icon='fal fa-external-link' aria-hidden='true' />
                    </a>
                </div>
            </div>

            <div class="border-2 h-full w-full">
                <div v-if="isIframeLoading" class="flex justify-center items-center w-full h-64 p-12 bg-white">
                    <FontAwesomeIcon icon="fad fa-spinner-third" class="animate-spin w-6" aria-hidden="true" />
                </div>

                <div v-else class="h-full w-full bg-white relative">
                    <div
                        ref="_parentComponent"
                        :style="{
                            ...propertiesToHTMLStyle(announcementData.container_properties, { toRemove: styleToRemove}),
                            position: announcementData.container_properties?.position?.type === 'fixed' ? 'absolute' : announcementData.container_properties?.position?.type
                        }"
                    >
                        <component
                            :is="getAnnouncementComponent(announcementData.code)"
                            :announcementData="announcementData"
                            :key="announcementData.code"
                            isEditable
                            :_parentComponent
                        />
                    </div>
                    <!-- <br>
                    <br>
                    <pre>{{ announcementData }}</pre> -->
                </div>
            </div>
        </div>
    </div>

    
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <!-- <HeaderListModal 
            :onSelectBlock
            :webBlockTypes="selectedWebBlock"
            :currentTopbar="usedTemplates.topBar"
        /> -->

        <div class="h-[500px]">
            <AnnouncementTemplateList
            />
        </div>
    </Modal>
</template>
