<script setup lang="ts">
import { ref } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from "@/../private/pro-light-svg-icons";
import { ulid } from "ulid";
import { trans } from "laravel-vue-i18n";
import Modal from "./Modal/Modal.vue";
import CropImage from "./CropImage/CropImage.vue";

library.add(faImage);
const props = defineProps<{
  data: {
    common: {
      centralStage: {
        subtitle?: string;
        text?: string;
        title?: string;
      };
      corners: Corners;
    };
    components: Array<{
      id: number;
      image_id: number;
      image_source: string;
      layout: {
        link?: string;
        centralStage: {
          title?: string;
          subtitle?: string;
          // text?: string,
          // footer?: string
        };
      };
      corners: Object;
      imageAlt: string;
      link: string;
      visibility: boolean;
    }>;
    delay: number;
  };
  imagesUploadRoute: Object;
}>();

const isOpen = ref(false);
const addFiles = ref([]);

const closeModal = () => {
  addFiles.value.files = null;
  isOpen.value = false;
  fileInput.value.value = "";
};

const isDragging = ref(false);
const fileInput = ref(null);

const onChange = () => {
  addFiles.value = fileInput.value?.files;
  isOpen.value = true;
};

const dragover = (e) => {
  e.preventDefault();
  isDragging.value = true;
};

const dragleave = () => {
  isDragging.value = false;
};

const drop = (e) => {
  e.preventDefault();
  addFiles.value = e.dataTransfer.files;
  isOpen.value = true;
  isDragging.value = false;
};

const uploadImageRespone = (res) => {
  console.log(res);
  let setData = [];
  for (const set of res.data) {
    setData.push({
      id: null,
      ulid: ulid(),
      layout: {
        imageAlt: set.name,
      },
      image: set,
      visibility: true,
    });
  }
  const newFiles = [...setData];
  props.data.components = [...props.data.components, ...newFiles];
  isOpen.value = false;
};
</script>

<template layout="TenantApp">
    <Modal :isOpen="isOpen" @onClose="closeModal">
        <div>
            <CropImage :data="addFiles" :imagesUploadRoute="props.imagesUploadRoute" :respone="uploadImageRespone" />
        </div>
    </Modal>
    <div class="col-span-full p-3" @dragover="dragover" @dragleave="dragleave" @drop="drop">
        <div class="relative mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 bg-gray-400/10 hover:bg-gray-400/20">
            <label for="fileInput"
                class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-2 focus-within:ring-gray-400 focus-within:ring-offset-0">
                <!-- <span>{{ trans("Click") }}</span> -->
                <input type="file" multiple name="file" id="fileInput" class="sr-only" @change="onChange" ref="fileInput" />
            </label>
            <div class="text-center text-gray-500">
                <FontAwesomeIcon :icon="['fal', 'image']" class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                <div class="mt-2 flex text-sm leading-6 ">
                    <p class="pl-1">{{ trans("Click or drag & drop") }}</p>
                </div>
                <p class="text-[0.7rem]">
                    {{ trans("PNG, JPG, GIF up to 10MB") }}
                </p>
            </div>
        </div>
        <div class="text-xs text-gray-400 py-1">{{ trans("The recommended image size is 1800 x 450") }}</div>
    </div>
</template>
