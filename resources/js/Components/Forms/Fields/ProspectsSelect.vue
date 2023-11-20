<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { faInfoCircle } from '@far/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted, watch, reactive } from 'vue'
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"
import { get, set, isArray, isNull } from 'lodash'
import { faExclamationCircle, faCheckCircle, faChevronCircleLeft } from '@fas/';

library.add(faChevronCircleLeft, faInfoCircle, faExclamationCircle, faCheckCircle)

const props = defineProps<{
    form?: any
    fieldName: string
    tabName: string
    options: string[] | object
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
        searchable?: boolean
    }
}>()


const tagsOptions = ref([])

const getTagsOptions = async () => {
    try {
        const response = await axios.get(
            route('org.json.tags'),
        )
        tagsOptions.value = Object.values(response.data)
    } catch (error) {
        notify({
            title: "Failed",
            text: "Failed to get data, Tags, please reload you page",
            type: "error"
        });
    }
}

const emits = defineEmits();


const setFormValue = (data,fieldName) => {
    if (isArray(fieldName)) return get(data, fieldName, []);  /* if fieldName array */
    else return get(data, fieldName, []); /* if fieldName string */
};

const value = ref(setFormValue(props.form, props.fieldName));

watch(value, (newValue) => {
    updateFormValue(newValue);
});

const updateFormValue = (newValue) => {
    let target = props.form;
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    emits("update:form", target);
};


onMounted(() => {
    getTagsOptions()
})

</script>
  
<template>
        <Multiselect v-model="value" mode="tags" placeholder="Select the tag" valueProp="slug" trackBy="name"
            label="name" :close-on-select="false" :searchable="true" :caret="false" :options="tagsOptions"
            noResultsText="No one left. Type to add new one.">

            <template
                #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                <div class="px-0.5 py-[3px]">
                    <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true" size="sm"
                        @onClose="(event) => handleTagRemove(option, event)" />
                </div>
            </template>
        </Multiselect>
</template>
  
<style lang="scss">
.multiselect-tags-search {
    @apply focus:outline-none focus:ring-0
}

.multiselect.is-active {
    @apply shadow-none
}


.multiselect-tags {
    @apply m-0.5
}

.multiselect-tag-remove-icon {
    @apply text-lime-800
}
</style>
