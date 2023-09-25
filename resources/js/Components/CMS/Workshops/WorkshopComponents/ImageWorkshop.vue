<script setup lang="ts">
import { Ref, ref } from 'vue'
import axios from 'axios'
import WebpageColorPicker from '@/Components/CMS/Workshops/WorkshopComponents/WebpageColorPicker.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faImage } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faImage)

import { ulid } from "ulid"

const props = defineProps<{
    dataImage: {
        link: string
        color: string
    }
}>()

const popoverValue: Ref<any> = ref(false)
const tempId = ulid() // To show the match Popover
const isLoadingUpload = ref(false)

// Running when file is uploaded
const onUploadFile = async (fileUploaded: any) => {
    isLoadingUpload.value = true
    try {
        // await axios.post(
        //     route(props.routes.upload.name,props.routes.upload.parameters),
        //     {
        //         file: fileUploaded.target.files[0],
        //     },
        //     {
        //         headers: { "Content-Type": "multipart/form-data" },
        //     }
        // )
        props.dataImage.link = await URL.createObjectURL(fileUploaded.target.files[0]) // Create blob image
    } catch (error: any) {
        // console.error("===========================")
        console.error(error.message)
    }
    isLoadingUpload.value = false
}

</script>

<template>
    <div @click="() => { popoverValue = tempId }"
        class="group z-20 h-full w-full relative rounded focus-within:border-transparent transition-all duration-300 ease-in-out">
        <div class="hidden group-hover:flex flex-col gap-y-4 z-20 relative w-full h-full hover:bg-gray-100/30 items-center justify-center">
            <!-- Change image -->
            <div>
                <input type="file" id="upload-file" class="sr-only" @change="onUploadFile" accept="image/*" />
                <label for="upload-file"
                    class="flex items-center gap-x-2 text-gray-100 px-5 py-3 ring-2 ring-gray-400 rounded cursor-pointer hover:bg-gray-800/40">
                    <FontAwesomeIcon icon='fal fa-image' class='h-6' aria-hidden='true' />
                    Change image
                </label>
            </div>

            <!-- Color picker -->
            <div class="">
                <WebpageColorPicker v-model="dataImage.color" />
            </div>
        </div>
    </div>
</template>