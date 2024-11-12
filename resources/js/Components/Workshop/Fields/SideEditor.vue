<script setup lang="ts">
import { inject, ref, watch, onMounted, defineProps, defineEmits } from 'vue';
import InputText from 'primevue/inputtext';
import PanelProperties from '@/Components/Workshop/PanelProperties.vue';
import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';
import UploadImage from '@/Components/UploadImage.vue';
import ArrayPhone from "@/Components/Workshop/Fields/ArrayPhone.vue";
import Payments from '@/Components/Workshop/Fields/Payment.vue';
import socialMedia from '@/Components/Workshop/Fields/SocialMedia.vue';
import FooterColumn from '@/Components/Workshop/Fields/FooterColumn/FooterColumn.vue';

import { isArray, set as setLodash, cloneDeep, get } from 'lodash';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faAngleDown, faAngleUp } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core';
import Icon from '@/Components/Icon.vue';
library.add(faAngleDown, faAngleUp);

const props = defineProps<{
    modelValue: any,
    blueprint: Array<any>,
    uploadImageRoute?: string,
    background?: string,
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', value: any): void
}>();

const openPanel = ref(0);

const getComponent = (componentName: string) => {
    const components: Record<string, any> = {
        'text': InputText,
        'payment_templates': Payments,
        'socialMedia': socialMedia,
        'footerColumn': FooterColumn,
        'body': PanelProperties,
        "upload_image": UploadImage,
        "arrayPhone": ArrayPhone,
    };
    return components[componentName];
};

const onUpdateValue = () => {
    emits('update:modelValue', props.modelValue);
};

// Helper function to get nested value using path array
const getFormValue = (data: any, fieldKeys: string | string[]) => {
    const keys = Array.isArray(fieldKeys) ? fieldKeys : [fieldKeys];
    return keys.reduce((acc, key) => acc && acc[key], data) ?? null;
};

// Helper function to set nested value using path array
const setFormValue = (mValue: any, fieldKeys: string | string[], newVal: any) => {
    const keys = Array.isArray(fieldKeys) ? fieldKeys : [fieldKeys];
    setLodash(mValue, keys, newVal);
    onUpdateValue();
};

const openFieldWorkshop = inject('openFieldWorkshop', null);

const setChild = (blueprint = [], data = {}) => {
    const result = { ...data };
    for (const form of blueprint) {
        getFormValues(form, result);
    }
    return result;
};

const getFormValues = (form: any, data: any = {}) => {
    const keyPath = Array.isArray(form.key) ? form.key : [form.key];
    if (form.replaceForm) {
        const set = getFormValue(data, keyPath) || {};
        setLodash(data, keyPath, setChild(form.replaceForm, set));
    } else {
        if (!get(data, keyPath)) {
            setLodash(data, keyPath, null);
        }
    }
};

const setFormValues = (blueprint = [], data = {}) => {
    for (const form of blueprint) {
        getFormValues(form, data);
    }
    return data;
};

watch(() => openFieldWorkshop?.value, (value) => {
    if (value) {
        openPanel.value = props.blueprint.findIndex(item => item.key?.includes(value));
    }
});

onMounted(() => {
    emits('update:modelValue', setFormValues(props.blueprint, cloneDeep(props.modelValue)));
});

</script>

<template>
    <Accordion v-model:value="openPanel">
        <AccordionPanel 
            v-for="(field, index) of blueprint.filter((item)=>item.type != 'hidden')" 
            :key="index" 
            :value="index"
        >
            <AccordionHeader>
                <div>
                    <Icon v-if="field?.icon" :data="field.icon" />
                    {{ field.name }}
                </div>
            </AccordionHeader>

            <AccordionContent class="px-0 py-2">
                <div class="bg-white mt-[0px]">
                    <template v-if="field.replaceForm">
                        <div v-for="form in field.replaceForm">
                            <div v-if="form.type != 'hidden'">
                                <div class="my-2 text-xs font-semibold">{{ form?.name }}</div>
                                <component :is="getComponent(form.type)" :key="form.key"
                                    :modelValue="getFormValue(modelValue, [...field.key, ...form.key])"
                                    @update:modelValue="newValue => setFormValue(modelValue, [...field.key, ...form.key], newValue)"
                                    :uploadRoutes="uploadImageRoute" v-bind="{ ...form?.props_data, background }" />
                            </div>

                        </div>
                    </template>

                    <template v-else>
                        <div class="my-2 text-xs font-semibold">{{ field?.name }}</div>
                        <component :is="getComponent(field.type)" :key="field.key"
                            :modelValue="getFormValue(modelValue, field.key)"
                            @update:modelValue="newValue => setFormValue(modelValue, field.key, newValue)"
                            :uploadRoutes="uploadImageRoute" v-bind="{ ...form?.props_data, background }" />
                    </template>
                </div>
            </AccordionContent>
        </AccordionPanel>
    </Accordion>
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
    background-color: #475569 !important;
    color: white !important;
    margin-bottom: 0px !important;
    border-radius: 0 !important;
}

:deep(.p-accordionpanel.p-accordionpanel-active > .p-accordionheader:hover) {
    background-color: #475569 !important;
    color: white !important;
    border-radius: 0 !important;
}

:deep(.p-accordionpanel:not(.p-disabled).p-accordionpanel-active > .p-accordionheader .p-accordionheader-toggle-icon) {
    color: white !important;
}
</style>
