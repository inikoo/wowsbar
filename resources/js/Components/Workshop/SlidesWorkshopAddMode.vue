<script setup lang="ts">
import { ref } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from '@/../private/pro-light-svg-icons'
import { ulid } from 'ulid';
import { trans } from "laravel-vue-i18n"
import Modal from './Modal/Modal.vue'
import CropImage from './CropImage/CropImage.vue'

library.add(faImage)
const props = defineProps<{
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: Array<
            {
                id: number,
                image_id: number
                image_source: string
                layout: {
                    link?: string,
                    centralStage: {
                        title?: string
                        subtitle?: string
                        // text?: string,
                        // footer?: string
                    }
                }
                corners: Object,
                imageAlt: string
                link: string
                visibility: boolean
            }
        >
        delay: number,
    }
    imagesUploadRoute : Object

}>();

const isOpen = ref(false)
const addFiles = ref([])

const closeModal = () => {
    addFiles.value.files = null
    isOpen.value = false
    fileInput.value.value = ''
}

const isDragging = ref(false)
const fileInput = ref(null)

const onChange = () => {
    addFiles.value = fileInput.value?.files
    isOpen.value = true
}

const dragover = (e) => {
    e.preventDefault()
    isDragging.value = true
}

const dragleave = () => {
    isDragging.value = false
}

const drop = (e) => {
    e.preventDefault()
    addFiles.value = e.dataTransfer.files
    isOpen.value = true
    isDragging.value = false
}

const uploadImageRespone=(res)=>{
    console.log(res)
    let setData = []
     for (const set of res.data) {
            setData.push({
                id: null,
                ulid: ulid(),
                layout: {
                    imageAlt: set.name,
                },
                image : set,
                visibility : true
            })
    }
    const newFiles = [...setData]
    props.data.components = [...props.data.components, ...newFiles]
    isOpen.value = false
}

console.log('add',props)

</script>

<template layout="App">
     <Modal :isOpen="isOpen" @onClose="closeModal">
        <div>
            <CropImage :data="addFiles"  :imagesUploadRoute="props.imagesUploadRoute"  :respone="uploadImageRespone"/>
        </div>
    </Modal>
        <div class="col-span-full p-3" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
              <div class="text-center">
                <font-awesome-icon :icon="['fal', 'image']"  class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                  <label for="fileInput" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                    <span>{{trans('Click')}}</span>
                    <input type="file" multiple name="file" id="fileInput" class="sr-only" @change="onChange" ref="fileInput"/>
                  </label>
                  <p class="pl-1">{{trans('or drag and drop')}}</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">{{trans('PNG, JPG, GIF up to 10MB')}}</p>
              </div>
            </div>
          </div>
</template>
