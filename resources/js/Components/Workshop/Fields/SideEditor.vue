<script setup lang="ts">
import { ref } from 'vue'

import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import InputText from 'primevue/inputtext';
import PanelProperties from '@/Components/Workshop/PanelProperties.vue'
import Accordion from 'primevue/accordion'
import AccordionPanel from 'primevue/accordionpanel'
import AccordionHeader from 'primevue/accordionheader'
import AccordionContent from 'primevue/accordioncontent'


import Payments from '@/Components/Workshop/Fields/Payment.vue'
import socialMedia from '@/Components/Workshop/Fields/SocialMedia.vue'
import FooterColumn from '@/Components/Workshop/Fields/FooterColumn/FooterColumn.vue'
import { isArray, set as setLodash } from 'lodash'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faAngleDown, faAngleUp } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faAngleDown, faAngleUp)


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
        'payment_templates': Payments,
        'socialMedia': socialMedia,
        'footerColumn': FooterColumn,
        'body': PanelProperties
    }

    return components[componentName]
}

/* const onUpdateValue = (field, value) => {
    emits('update:modelValue', {
        ...props.modelValue, [field.key]: value
    })
} */

const onUpdateValue = () => {
    emits('update:modelValue', props.modelValue)
}

// To trick the modelValue with deep object (['container', 'properties'])
const getFormValue = (data: Object, fieldKeys: string | string[]) => {
    if (Array.isArray(fieldKeys)) {
        return fieldKeys.reduce((acc, key) => {
            if (acc && typeof acc === "object" && key in acc) return acc[key]
            return null
        }, data)
    } else {
        return data[fieldKeys]
    }
}
const setFormValue = (mValue: Object, fieldKeys: string | string[], newVal) => {
    setLodash(props.modelValue, fieldKeys, newVal)
    onUpdateValue()
}

</script>

<template>
    <Accordion>
        <AccordionPanel v-for="(field, index) of bluprint" :key="index" :value="index" @click="openPanel = index">
            <AccordionHeader>
                <div>
                    <Icon :data="field.icon" />
                    {{ field.name }}
                </div>
            </AccordionHeader>

            <AccordionContent class="px-0 py-2">
                <!-- Component side editor -->
                <div class="bg-white mt-[0px]">
                    <!-- field key: {{ field.key }} -->
                    <!-- model value: <pre>{{ modelValue }}</pre> -->
                    <!-- {{ modelValue[field.key] }} -->
                    <!-- <pre>{{ modelValue }}</pre> -->

                    <!-- If field have 'replaceform' and in [] -->
                    <template v-if="field.replaceForm">
                        <template v-for="form in field.replaceForm">
                            <!-- If multi type -->
                            <template v-if="isArray(form.type)">
                                <component v-for="(type, indexType) in form.type" :is="getComponent(type)"
                                    :modelValue="getFormValue(modelValue, form.key)"
                                    @update:modelValue="newValue => setFormValue(modelValue, form.key, newValue)"
                                    :uploadRoutes="uploadImageRoute" v-bind="form?.props_data" />
                            </template>

                            <template v-else>
                                <!-- If single type -->
                                <component :is="getComponent(form.type)" :key="form.key"
                                    :modelValue="getFormValue(modelValue, form.key)"
                                    @update:modelValue="newValue => setFormValue(modelValue, form.key, newValue)"
                                    :uploadRoutes="uploadImageRoute" v-bind="form?.props_data" />
                            </template>
                        </template>
                    </template>

                    <!-- If have no 'replaceform' -->
                    <template v-else>
                        <template v-if="isArray(field.type)">
                            <!-- If multi type -->
                            <component v-for="(type, indexType) in field.type" :is="getComponent(type)"
                                :modelValue="getFormValue(modelValue, field.key)"
                                @update:modelValue="newValue => setFormValue(modelValue, field.key, newValue)"
                                :uploadRoutes="uploadImageRoute" v-bind="field?.props_data" />
                        </template>

                        <template v-else>
                            <!-- If single type -->
                            <component :is="getComponent(field.type)" :key="field.key"
                                :modelValue="getFormValue(modelValue, field.key)"
                                @update:modelValue="newValue => setFormValue(modelValue, field.key, newValue)"
                                :uploadRoutes="uploadImageRoute" v-bind="field?.props_data" />
                        </template>
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
