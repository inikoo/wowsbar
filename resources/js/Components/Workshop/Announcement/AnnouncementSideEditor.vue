<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 07 Jun 2023 02:45:27 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core'
import { inject, ref } from 'vue'
import { faBrowser, faDraftingCompass, faRectangleWide, faStars, faBars, faText, faChevronDown } from '@fal'
// import draggable from "vuedraggable"
import PanelProperties from '@/Components/Workshop/PanelProperties.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Button from '@/Components/Elements/Buttons/Button.vue'
import debounce from 'lodash/debounce'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import Modal from "@/Components/Utils/Modal.vue"
import AnnouncementTemplateList from '@/Components/Workshop/Announcement/AnnouncementTemplateList.vue'

// import { Root, Daum } from '@/types/webBlockTypes'
// import { Root as RootWebpage } from '@/types/webpageTypes'
import { Collapse } from 'vue-collapsed'
import { trans } from 'laravel-vue-i18n'
import Editor from '@/Components/Editor/Editor.vue'
import PureInputNumber from '@/Components/Pure/PureInputNumber.vue'


library.add(faBrowser, faDraftingCompass, faRectangleWide, faStars, faBars, faText, faChevronDown)

const props = defineProps<{
    webpage: RootWebpage
    webBlockTypeCategories: Root
    isLoadingDelete: string | null
    isAddBlockLoading: string | null
}>()

const emits = defineEmits<{
    (e: 'add', value: Daum): void
    (e: 'delete', value: Daum): void
    (e: 'update', value: Daum): void
    (e: 'order', value: Object): void
    (e: 'openBlockList', value: Boolean): void
}>()

const isModalBlocksList = ref(false)
const isLoading = ref<string | boolean>(false)

const sendNewBlock = async (block: Daum) => {
    emits('add', block)
}

const sendBlockUpdate = async (block: Daum) => {
    emits('update', block)
}

// const sendOrderBlock = async (block: Object) => {
//     emits('order', block)
// }

// const sendDeleteBlock = async (block: Daum) => {
//     emits('delete', block)
// }


const debouncedSendUpdate = debounce((block) => sendBlockUpdate(block), 1000, { leading: false, trailing: true })
const onUpdatedBlock = (block: Daum) => {
    debouncedSendUpdate(block)
}

// const onChangeOrderBlock = () => {
//     let payload = {}
//     props.webpage.layout.web_blocks.map((item, index) => {
//         payload[item.web_block.id] = { position: index }
//     })
//     sendOrderBlock(payload)
// }

const onPickBlock = async (block: Daum) => {
    await sendNewBlock(block)
    isModalBlocksList.value = false
}

const openModalBlockList = () => {
    isModalBlocksList.value = !isModalBlocksList.value
    emits('openBlockList', !isModalBlocksList.value)

}

// defineExpose({
//     isModalBlocksList
// })

const selectedBlockOpenPanel = ref<string | null>(null)

const announcementData = inject('announcementData', {})
</script>

<template>
    <!-- <div class="flex justify-between">
        <h2 class="text-sm font-semibold leading-6">Blocks List</h2>
        <Button icon="fas fa-plus" type="dashed" size="xs" @click="openModalBlockList" />
    </div> -->

    <div class="rounded bg-white">
        <div @click="() => selectedBlockOpenPanel === 'container' ? selectedBlockOpenPanel = null : selectedBlockOpenPanel = 'container'"
            class="w-full bg-gray-200 py-2 px-3 flex justify-between items-center cursor-pointer">
            <div class="select-none font-semibold">{{ trans('Container Settings') }}</div>
            <FontAwesomeIcon icon='fal fa-chevron-down' :class="selectedBlockOpenPanel === 'container' ? 'rotate-180' : ''" class="transition-all" fixed-width aria-hidden='true' />
        </div>

        <Collapse as="section" :when="selectedBlockOpenPanel === 'container'">
            <PanelProperties
                v-model="announcementData.container_properties"
                @update:modelValue="() => (console.log('zzz'), debouncedSendUpdate('element'))"
            />
        </Collapse>
    </div>

    <div class="rounded overflow-hidden bg-white">
        <div @click="() => selectedBlockOpenPanel === 'content' ? selectedBlockOpenPanel = null : selectedBlockOpenPanel = 'content'"
            class="w-full bg-gray-200 py-2 px-3 flex justify-between items-center cursor-pointer">
            <div class="select-none font-semibold">{{ trans('Content') }}</div>
            <FontAwesomeIcon icon='fal fa-chevron-down' :class="selectedBlockOpenPanel === 'content' ? 'rotate-180' : ''" class="transition-all" fixed-width aria-hidden='true' />
        </div>

        <Collapse as="section" :when="selectedBlockOpenPanel === 'content'">
            <div  class="border-t border-gray-300 pb-3">
                <div class="w-full py-1 px-2 select-none text-sm">{{ trans('Text 1') }}</div>
                <div class="">
                    <Editor v-model="announcementData.fields.text_1.text" />
                </div>
            </div>

            <div  class="border-t border-gray-300 pb-3">
                <div class="w-full py-1 px-2 select-none text-sm">{{ trans('Text 2') }}</div>
                <div class="">
                    <Editor v-model="announcementData.fields.text_2.text" />
                </div>
            </div>

            <div  class="border-t border-gray-300 pb-3">
                <div class="w-full py-1 px-2 select-none text-sm">{{ trans('Close button') }}</div>
                <div class="mx-auto h-11 w-11 flex justify-center items-center rounded-md border border-gray-300">
                    <FontAwesomeIcon icon='fal fa-times' class='text-gray-500' fixed-width aria-hidden='true' />
                </div>

                <div class="px-3 mb-4">
                    <div class="mb-2">
                        <div class="text-xs">{{ trans('Position Y') }}</div>
                    </div>
                    
                    <PureInputNumber v-model="announcementData.fields.close_button.position_top" class="" suffix="%" />
                </div>

                <div class="px-3">
                    <div class="mb-2">
                        <div class="text-xs">{{ trans('Position X') }}</div>
                    </div>
                    
                    <PureInputNumber v-model="announcementData.fields.close_button.position_left" class="" suffix="%" />
                </div>
            </div>
        </Collapse>
    </div>


    <Modal :isOpen="isModalBlocksList" @onClose="openModalBlockList">
        <AnnouncementTemplateList
            :onPickBlock="onPickBlock"
            :webBlockTypes="webBlockTypeCategories"
            scope="webpage"
        />
    </Modal>
</template>