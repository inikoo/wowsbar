<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:20:36 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { provide, reactive, ref, toRaw, watch } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import Promo1 from '@/Components/Workshop/Announcement/Templates/Promo/Promo1.vue'


import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe, faImage } from '@fal'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import AnnouncementSideEditor from '@/Components/Workshop/Announcement/AnnouncementSideEditor.vue'
import { notify } from '@kyvg/vue3-notification'
import ScreenView from '@/Components/ScreenView.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import { debounce } from 'lodash'

library.add(faGlobe, faImage)

const props = defineProps<{
    pageHead: {}
    title: string
    data: {},
    store_route: {},
    firstBanner?: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
    }
    announcementData: {}
}>()

const vvvv = async () => {
    // const zzz = await axios.get('http://delivery.wowsbar.test/announcement.js');
    fetch(`http://delivery.wowsbar.test/announcement/01J9T4KWNQJM9BMMPHKGKY0AVK`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); // or response.text() if expecting plain text
        })
        .then(data => {
            console.log('from fetch delivery', data); // Process the data
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

    // console.log("axios:", zzz)
}
vvvv()

const selectedBlockOpenPanel = ref(true)
const isLoading = ref<string | boolean>(false)
const comment = ref("")
const openDrawer = ref<string | boolean>(false)
// const iframeSrc = ref(route('grp.websites.preview', [route().params['website'], route().params['webpage']]))
const data = ref({ ...props.webpage })
const iframeClass = ref('w-full h-full')
const isIframeLoading = ref(false)
const _WebpageSideEditor = ref(null)

const isAddBlockLoading = ref<string | null>(null)
const addNewBlock = async (block: Daum) => {
    // try {
    //     const response = router.post(
    //         route(props.webpage.add_web_block_route.name, props.webpage.add_web_block_route.parameters),
    //         { web_block_type_id: block.id }
    //     )
    //     const set = { ...response.data.data }
    //     data.value = set
    // } catch (error: any) {
    //     console.error('error', error)
    // }
    // isAddBlockLoading.value = null
    router.post(
        route(props.webpage.add_web_block_route.name, props.webpage.add_web_block_route.parameters),
        { web_block_type_id: block.id },
        {
            onStart: () => isAddBlockLoading.value = 'addBlock' + block.id,
            onFinish: () => isAddBlockLoading.value = null,
            onError: (error) => {
                notify({
                    title: trans('Something went wrong'),
                    text: error.message,
                    type: 'error',
                })
            }
        }
    )
}

const isSavingBlock = ref(false)
const sendBlockUpdate = async (block: {}) => {
    // try {
    //     const response = router.patch(
    //         route(props.webpage.update_model_has_web_blocks_route.name, { modelHasWebBlocks: block.id }),
    //         { layout: block.web_block.layout }
    //     )
    //     const set = { ...response.data.data }
    //     data.value = set
    // } catch (error: any) {
    //     console.error('error', error)
    // }
    // console.log('on send block update')

    // router.post(
    //     route(props.webpage.update_model_has_web_blocks_route.name, { modelHasWebBlocks: block.id }),
    //     { layout: block.web_block.layout },
    //     {
    //         onStart: () => isSavingBlock.value = true,
    //         onFinish: () => isSavingBlock.value = false,
    //         onError: (error) => {
    //             notify({
    //                 title: trans('Something went wrong'),
    //                 text: error.message,
    //                 type: 'error',
    //             })
    //         },
    //         preserveScroll: true
    //     },
    // )
}

// const sendOrderBlock = async (block: Object) => {
//     try {
//         const response = await router.post(
//             route(props.webpage.reorder_web_blocks_route.name, props.webpage.reorder_web_blocks_route.parameters),
//             { positions: block }
//         )
//         // const set = { ...response.data.data }
//         // data.value = set
//     } catch (error: any) {
//         console.error('error', error)
//     }
// }

const isLoadingDelete = ref<string | null>(null)
const sendDeleteBlock = async (block: {}) => {
    // console.log('block', block)
    // isLoading.value = 'deleteBlock' + block.id
    // try {
    //     const response = await axios.delete(
    //         route(props.webpage.delete_model_has_web_blocks_route.name, { modelHasWebBlocks: block.id })
    //     )
    //     const set = { ...response.data.data }
    //     data.value = set
    // } catch (error: any) {
    //     console.error('error', error)
    // }

    router.delete(
        route(props.webpage.delete_model_has_web_blocks_route.name, { modelHasWebBlocks: block.id }),
        {
            onStart: () => isLoadingDelete.value = 'deleteBlock' + block.id,
            onFinish: () => isLoadingDelete.value = null,
            onError: (error) => {
                notify({
                    title: trans('Something went wrong'),
                    text: error.message,
                    type: 'error',
                })
            }
        }
    )
    // isLoading.value = false
}


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
const onSave = (dataToSend: {}) => {
    router.post(route(props.store_route.name, props.store_route.parameters), {
        ...props.announcementData
    }, {
        onStart: () => isLoadingSave.value = true,
        onFinish: () => {
            isLoadingSave.value = false
            saveCancelToken.value = null
        },
        onError: (error) => console.error('======', error),
        onCancelToken: (cclToken) => saveCancelToken.value = cclToken.cancel
    })
}

provide('announcementData', props.announcementData)

const xxx = debounce((newVal) => onSave(newVal), 1000, { leading: false, trailing: true })
// watch(() => props.announcementData, (newVal) => {
//     // console.log('vvvv', newVal)
//     // if (newVal) {
//     //     if (saveCancelToken.value) {
//     //         console.log('eeee')
//     //         saveCancelToken.value()
//     //     }
//     // }

//     // xxx(toRaw(newVal))
// }, { deep: true })

</script>

<template layout="CustomerApp">

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #iconRight v-if="isLoadingSave">
            <LoadingIcon />
        </template>

        <template #other>
            <Button @click="onSave" label="save" :loading="isLoadingSave" />
        </template>
    </PageHeading>


    <div class="grid grid-cols-5 h-[86.7vh]">
        <!-- Section: Side editor -->
        <div class="col-span-1 md:block hidden h-full p-2 ">
            <div class="bg-amber-100 border border-gray-300 h-full py-2 px-3 rounded">
                <AnnouncementSideEditor
                    :isLoadingDelete
                    :isAddBlockLoading
                    :webBlockTypeCategories="webBlockTypeCategories"
                    @update="sendBlockUpdate"
                    @delete="sendDeleteBlock"
                    @add="addNewBlock"
                />
            </div>
        </div>

        <!-- Section: Preview -->
        <div v-if="true" class="md:col-span-4 col-span-5 h-full flex flex-col py-2 px-3">
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
                    <ScreenView @screenView="setIframeView" />
                    <div class="py-1 px-2 cursor-pointer" title="Desktop view" v-tooltip="'Preview'"
                        @click="openFullScreenPreview">
                        <FontAwesomeIcon :icon='faExternalLink' aria-hidden='true' />
                    </div>
                </div>
            </div>

            <div class="border-2 h-full w-full">
                <div v-if="isIframeLoading" class="flex justify-center items-center w-full h-64 p-12 bg-white">
                    <FontAwesomeIcon icon="fad fa-spinner-third" class="animate-spin w-6" aria-hidden="true" />
                </div>

                <div v-else class="h-full w-full bg-white">
                    <Promo1 :announcementData="announcementData" isEditable />

                    <!-- <iframe
                        :src="iframeSrc"
                        :title="props.title"
                        :class="[iframeClass, isIframeLoading ? 'hidden' : '']"
                        @error="handleIframeError"
                        @load="isIframeLoading = false"
                    /> -->
                </div>
            </div>
        </div>

        <pre>{{ props.announcementData }}</pre>
    </div>
</template>
