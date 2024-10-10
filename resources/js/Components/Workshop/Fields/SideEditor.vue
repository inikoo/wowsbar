<script setup lang="ts">
import { ref } from 'vue'

import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import InputText from 'primevue/inputtext';

/* import UploadImage from '@/Components/Pure/UploadImage.vue' */
import Payments from '@/Components/Workshop/Fields/Payment.vue'
/* import Editor from "@/Components/Forms/Fields/BubleTextEditor/EditorForm.vue" */
import socialMedia from '@/Components/Workshop/Fields/SocialMedia.vue'
import FooterColumn from '@/Components/Workshop/Fields/FooterColumn.vue'


import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faAngleDown, faAngleUp } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faAngleDown, faAngleUp )


const props = defineProps<{
    modelValue: any,
    bluprint: Array
    uploadImageRoute?: routeType
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: string | number): void
}>()

const openPanel = ref(0)

const getComponent = (componentName: string) => {
    const components: Component = {
        'text': InputText,
        /*'upload_image': UploadImage, */
        'payment_templates': Payments,
        /*  'editor': Editor, */
        'socialMedia': socialMedia,
        'footerColumn': FooterColumn,
    }

    return components[componentName]
}

const onUpdateValue = (field, value) => {
    emits('update:modelValue', {
        ...props.modelValue, [field.key]: value
    })
}


</script>

<template>
    <Disclosure v-slot="{ open }" v-for="(field, index) in bluprint">
        <DisclosureButton
            class="flex w-full justify-between bg-gray-200 px-4 py-1 text-left text-sm 
            font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus-visible:ring focus-visible:ring-gray-500/75">
            <span class="text-lg font-bold text-gray-600">{{ field.name }}</span>
            <FontAwesomeIcon :icon="open ? faAngleDown : faAngleUp" class="h-5 w-5 text-gray-500" />
        </DisclosureButton>
        <DisclosurePanel class="px-4 bg-gray-100 pb-2 pt-4 text-sm text-gray-500">
            <component :is="getComponent(field.type)" :key="field.key" v-model="modelValue[field.key]"
                @update:modelValue="value => onUpdateValue(field, value)" :uploadRoutes="uploadImageRoute"
                v-bind="field?.props_data" />
        </DisclosurePanel>
    </Disclosure>
</template>


<style lang="scss" scoped>
.editor-content {
    background-color: white;
    border: solid;
}

.p-inputtext {
    width: 100%
}

:deep(.p-accordionpanel.p-accordionpanel-active > .p-accordionheader) {
    background-color: #800080 !important;
    /* Ungu */
    color: white !important;
    /* Warna teks */
    margin-bottom: 12px !important;
    border-radius: 0 !important;
}

:deep(.p-accordionpanel.p-accordionpanel-active > .p-accordionheader:hover) {
    background-color: #800080 !important;
    /* Ungu saat hover */
    color: white !important;
    border-radius: 0 !important;
}

:deep(.p-accordionpanel:not(.p-disabled).p-accordionpanel-active > .p-accordionheader .p-accordionheader-toggle-icon) {
    color: white !important;
    /* Warna teks */
}
</style>
