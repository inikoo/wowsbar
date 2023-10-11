<script setup lang='ts'>
import { ref, Ref } from 'vue'
import { trans } from 'laravel-vue-i18n'
import Button from "@/Components/Elements/Buttons/Button.vue"

import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faImage, faPhotoVideo } from "@/../private/pro-light-svg-icons"
library.add(faImage, faPhotoVideo)

const props = defineProps<{
    bannerType: string
}>()

const emits = defineEmits<{
    (e: 'dragOver'): void
    (e: 'dragLeave'): void
    (e: 'drop'): void
    (e: 'onChangeInput'): void
    (e: 'onClickButtonGallery'): void
    (e: 'addedFiles', files: File[]): void
}>()

const fileInput: Ref<any> = ref(null)

const onChange = () => {
    // props.addedFiles = fileInput.value?.files
    emits('addedFiles', fileInput.value?.files)
    emits('onChangeInput')
}


</script>

<template>
    <div class="w-full h-full" @dragover="emits('dragOver')" @dragleave="emits('dragLeave')" @drop="emits('drop')">
        <div class="relative mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 bg-gray-400/10 hover:bg-gray-400/20"
            :class="bannerType == 'square' ? 'h-72 aspect-square mx-auto' : ''"
        >
            <label for="fileInput"
                class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-2 focus-within:ring-gray-400 focus-within:ring-offset-0">
                <!-- <span>{{ trans("Click") }}</span> -->
                <input type="file" multiple name="file" id="fileInput" class="sr-only" @change="onChange" ref="fileInput" />
            </label>
            
            <div class="text-center text-gray-500">
                <FontAwesomeIcon :icon="['fal', 'image']" class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                <div class="mt-2 flex  justify-center text-lg font-medium leading-6 ">
                    <p class="pl-1">{{ trans("Upload Image") }}</p>
                </div>
                <div class="flex text-sm leading-6 ">
                    <p class="pl-1">{{ trans("Click or drag & drop") }}</p>
                </div>
                <p class="text-[0.7rem]">
                    {{ trans("PNG, JPG, GIF up to 10MB") }}
                </p>
                <Button id="gallery" :style="`primary`" :icon="'fal fa-photo-video'" label="Gallery" size="xs"
                    class="relative m-2.5" @click="emits('onClickButtonGallery')" />
            </div>
        </div>
        <div v-if="bannerType == 'landscape'" class="text-xs text-gray-400 py-1">{{ trans("The recommended image size is 1800 x 450") }}</div>
        <div v-else-if="bannerType == 'square'" class="text-xs text-gray-400 py-1">{{ trans("The recommended image size is 500 x 500") }}</div>
    </div>
</template>