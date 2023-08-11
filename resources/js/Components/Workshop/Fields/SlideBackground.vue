<script setup>
import { trans } from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue";
import { ref, h, defineComponent } from "vue";
import VuePictureCropper, { cropper } from "vue-picture-cropper";
import { set, get } from "lodash";
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.compact.css';
import Modal from "../Modal/Modal.vue";
import CropImage from '../CropImage/CropImage.vue'
import LibrariesImage from "../LibrariesImage.vue";

const props = defineProps(["data"]);
const _cropper = ref()

const cropOnChange = ({ coordinates, image, visibleArea, }) => {
    console.log(coordinates, image, visibleArea,)
    set(props, ['data', 'imagePosition'], coordinates)
}

const onReady = () => {
    _cropper.value.setCoordinates({
          left: get(props.data,['imagePosition','left'],0), // Set the left position to 0
          top: get(props.data,['imagePosition','top'],0), // Set the top position to 0
        });
}


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
const addFiles = ref([])
const onFileChange = (event) => {
    addFiles.value = event.target.files
    isOpenCropModal.value = true
    // const file = event.target.files[0];
    // if (file) {
    //     props.data.image_source = null;
    //     props.data.imageFile = file;
    //     props.data.layout.imageAlt = file.name;
    // }
};

const isOpen = ref(false)
const fileInput = ref(null)

const closeModal = () => {
    isOpen.value = false
}

const isOpenCropModal = ref(false)

const closeModalisOpenCropModal = () => {
    addFiles.value = []
    isOpenCropModal.value = false
    fileInput.value.value = ''
}

</script>

<template>
    <div class="block w-full">
      <Modal :show="isOpen" @onClose="closeModal">
        <div>
            <LibrariesImage />
        </div>
    </Modal>
    <Modal :isOpen="isOpenCropModal" @onClose="closeModalisOpenCropModal">
        <div>
            <CropImage :data="addFiles"  :imagesUploadRoute="props.imagesUploadRoute"/>
        </div>
    </Modal>
        <div class="w-full overflow-hidden relative">
            <img class="aspect-[4/1] w-full rounded-2xl object-cover" :src="generateThumbnail(props.data.image_source)" alt="" />
            <!-- <Cropper ref="_cropper" class="w-[400px] md:w-[440px] h-[200px]" :src="generateThumbnail(props.data.image_source)" :stencil-props="{
                aspectRatio: 4 / 1,
                movable: true,
                resizable: false,
            }" :auto-zoom="true" @ready="onReady" @change="cropOnChange" minWidth="">
            </Cropper> -->
        </div>

        <div class="w-full relative space-y-4 mt-2.5">
            <div class="flex gap-x-2">
                <Button :style="`secondary`" icon="fas fa-upload" class="relative" size="xs">
                    {{ trans("Upload image") }}
                    <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                        for="input-slide-large" />
                    <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large" ref="fileInput"
                        accept="image/*" class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
                </Button>

                <Button :style="`tertiary`" icon="fal fa-image" size="xs" class="relative" @click="isOpen = !isOpen">
                    {{ trans("Libraries") }}
                </Button>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.cropper {
    height: 200px;
    width: 400px;
    @apply md:w-[400px] 
}
</style>