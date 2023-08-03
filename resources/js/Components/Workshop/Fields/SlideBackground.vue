<script setup>
import { trans } from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue";
import { ref, onMounted, watch, computed } from "vue";
import VuePictureCropper, { cropper } from "vue-picture-cropper";
import { set } from "lodash";
const props = defineProps(["data"]);
const crooper = ref(null);
const onCrop = (cropPosition) => {
    console.log(crooper.value)
    set(props, ['data', 'imagePosition'], cropPosition.detail)
};


const generateThumbnail = (fileOrUrl) => {
    if (fileOrUrl == null) {
        if (props.data.imageFile && props.data.imageFile instanceof File) {
            let fileSrc = URL.createObjectURL(props.data.imageFile);
            setTimeout(() => {
                URL.revokeObjectURL(fileSrc);
            }, 1000);
            return fileSrc;
        }
    } else {
        if (fileOrUrl instanceof File) {
            let fileSrc = URL.createObjectURL(fileOrUrl);
            setTimeout(() => {
                URL.revokeObjectURL(fileSrc);
            }, 1000);
            return fileSrc;
        } else if (typeof fileOrUrl === "string") {
            return fileOrUrl;
        }
    }
};

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        props.data.image_source = null;
        props.data.imageFile = file;
        props.data.layout.imageAlt = file.name;
    }
};



</script>

<template>
    <div class="w-full">
        <div class="w-full h-52 overflow-hidden">
            <VuePictureCropper ref="crooper" @crop="onCrop" :img="generateThumbnail(props.data.image_source)"
                :options="{
                    viewMode: 1,
                    aspectRatio: 4 / 1,
                    dragMode: 'move',
                    cropBoxResizable: false,
                    zoomable: false,
                    responsive: false,
                    restore: false,
                    rotatable: false,
                    scalable: false,
                    
                    minCropBoxWidth: 320,
                    minCropBoxHeight: 80,
                }" :outputSize="outputSize" />
        </div>


        <!-- Avatar Button: Large view -->
        <div class="w-full relative space-y-4 mt-2.5">
            <!-- Button: Add slide -->
            <div class="flex gap-x-2">
                <Button :style="`secondary`" icon="fas fa-upload" class="relative" size="xs">
                    {{ trans("Upload image") }}
                    <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                        for="input-slide-large" />
                    <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large"
                        accept="image/*" class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
                </Button>

                <Button :style="`tertiary`" icon="fal fa-image" size="xs" class="relative">
                    {{ trans("Libraries") }}
                    <!-- <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                        for="fileInput" />
                    <input ref="fileInput" type="file" multiple name="file" id="fileInput" @change="addComponent"
                        accept="image/*" class="absolute cursor-pointer rounded-md border-gray-300 sr-only" /> -->
                </Button>
            </div>
        </div>
    </div>
</template>
