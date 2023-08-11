<script setup>
import { trans } from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue";
import { ref, toRefs, watch, defineEmits } from "vue";
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.compact.css';
import Modal from "../Modal/Modal.vue";
import CropImage from '../CropImage/CropImage.vue'
import LibrariesImage from "../LibrariesImage.vue";
import Image from '@/Components/Image.vue'
import { set } from 'lodash'

const props = defineProps(["data", 'fieldName','fieldData']);
const { data, fieldName } = toRefs(props);
const isOpen = ref(false)
const fileInput = ref(null)
const emits = defineEmits()
const closeModal = () => {
    isOpen.value = false
}
const isOpenCropModal = ref(false)

const closeModalisOpenCropModal = () => {
    addFiles.value = []
    isOpenCropModal.value = false
    fileInput.value.value = ''
}

const setFormValue = (data,fieldName) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName);
    } else {
        return data[fieldName];
    }
}

const getNestedValue = (obj, keys) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key];
        return null;
    }, obj);
}

const value = ref(setFormValue(props.data, props.fieldName))

watch(data, (newValue) => {
    value.value = setFormValue(newValue, props.fieldName)
});

const addFiles = ref([])
const onFileChange = (event) => {
    addFiles.value = event.target.files
    isOpenCropModal.value = true
};

watch(value, (newValue) => {
      updateLocalFormValue({...newValue});
  });

  const updateLocalFormValue = (newValue) => {
      let localData = { ...props.data }
      if (Array.isArray(props.fieldName)) {
          set(localData, props.fieldName, newValue);
      } else {
          localData[props.fieldName] = newValue;
      }
     set(props.data,[props.fieldName],newValue)
  };
const uploadImageRespone=(res)=>{
    value.value = {...res.data[0]}
    isOpenCropModal.value = false
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
                <CropImage :data="addFiles" :imagesUploadRoute="props.fieldData.uploadRoute"  :respone="uploadImageRespone"/>
            </div>
        </Modal>
        <div class="w-full overflow-hidden relative">
            <Image class="aspect-[4/1] w-full rounded-2xl object-cover" :src="value.source"
                :alt="value.name"></Image>
        </div>

        <div class="w-full relative space-y-4 mt-2.5">
            <div class="flex gap-x-2">
                <Button :style="`secondary`" icon="fas fa-upload" class="relative" size="xs">
                    {{ trans("Upload image") }}
                    <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                        for="input-slide-large" />
                    <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large"
                        ref="fileInput" accept="image/*"
                        class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
                </Button>

                <Button :style="`tertiary`" icon="fal fa-image" size="xs" class="relative" @click="isOpen = !isOpen">
                    {{ trans("Libraries") }}
                </Button>
            </div>
        </div>
    </div>
</template>

