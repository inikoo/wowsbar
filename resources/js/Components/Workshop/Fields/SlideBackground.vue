<script setup>
import { trans } from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue";
import { ref, onMounted, watch, computed } from "vue";
import VuePictureCropper, { cropper } from "vue-picture-cropper";
import { set } from "lodash";
const props = defineProps(["data"]);
const crooper = ref(null);
const onCrop = (cropPosition) => {
    set(props,['data','imagePosition'],cropPosition.detail)
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
        <VuePictureCropper
            ref="crooper"
            @crop="onCrop"
            :img="generateThumbnail(props.data.image_source)"
            :options="{
                viewMode: 1,
                aspectRatio: 4 / 1,
                dragMode: 'move',
                cropBoxResizable: false,
            }"
        />

        <!-- Avatar Button: Large view -->
        <div class="w-full relative space-y-4">
            <div
                class="w-full aspect-[16/4] overflow-hidden relative shadow-md"
            >
                <img
                    class="absolute top-1/2 -translate-y-1/2 w-full"
                    :src="generateThumbnail(props.data.image_source)"
                    alt=""
                />
            </div>
            
            <!-- Button: Add slide -->
            <Button :style="`secondary`" class="" size="xs">
                {{ trans("Change image") }}
                <label
                    class="bg-transparent inset-0 absolute inline-block cursor-pointer"
                    id="input-slide-large-mask" for="input-slide-large"
                />
                <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large" accept="image/*"
                    class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
            </Button>
        </div>
    </div>
</template>
