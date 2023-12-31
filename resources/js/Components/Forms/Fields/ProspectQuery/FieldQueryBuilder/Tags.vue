<script setup lang="ts">
import descriptor from '../descriptor'
import Multiselect from "@vueform/multiselect"
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"
import { ref, onMounted, watch, reactive, computed} from 'vue'
import { trans } from "laravel-vue-i18n";

const props = withDefaults(defineProps<{
    value: any
    fieldName: any
}>(), {})

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


const positive = computed(() => {
  if (!tagsOptions.value || !props.value || !props.value[props.fieldName] || !props.value[props.fieldName].negative_tag_ids) {
    return tagsOptions.value;
  }

  const negativeTagIds = props.value[props.fieldName].negative_tag_ids;
  return tagsOptions.value.filter(item => !negativeTagIds.includes(item.id));
});

const negative = computed(() => {
  if (!tagsOptions.value || !props.value || !props.value[props.fieldName] || !props.value[props.fieldName].tag_ids) {
    return tagsOptions.value ;
  }

  const positiveTagIds = props.value[props.fieldName].tag_ids;
  return tagsOptions.value.filter(item => !positiveTagIds.includes(item.id));
});


onMounted(() => {
    getTagsOptions()
})
</script>

<template>
    <div>
        <div class="mb-2">
        <span class="font-bold text-sm block mb-1">{{trans("Included Tags")}} :</span>
            <Multiselect v-model="value[fieldName].tag_ids" mode="tags" placeholder="Select the tag" valueProp="id"
                trackBy="name" label="name" :close-on-select="false" :searchable="true" :caret="false"
                :options="positive" noResultsText="No one left. Type to add new one.">

                <template
                    #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                    <div class="px-0.5 py-[3px]">
                        <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true" size="sm"
                            @onClose="(event) => handleTagRemove(option, event)" />
                    </div>
                </template>
            </Multiselect>
        </div>

        <div v-if="value[fieldName].tag_ids.length > 1" class="mb-4">
            <div class="mt-1">
                <fieldset>
                    <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                        <div v-for="(filter, filterIndex) in descriptor.logic" :key="filter.value"
                            class="flex items-center">
                            <input :id="filter.value" :name="'logic' + fieldName" type="radio" :value="filter.value"
                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                v-model="value[fieldName].logic" />
                            <label :for="filter.value" class="ml-3 block text-xs font-medium leading-6 text-gray-900">{{filter.label}}</label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div>
            <span class="font-bold text-sm block mb-2">{{trans("Tags not included")}} :</span>
            <Multiselect v-model="value[fieldName].negative_tag_ids" mode="tags" placeholder="Select the tag" valueProp="id"
                trackBy="name" label="name" :close-on-select="false" :searchable="true" :caret="false"
                :options="negative" noResultsText="No one left. Type to add new one.">

                <template
                    #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                    <div class="px-0.5 py-[3px]">
                        <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true" size="sm"
                            @onClose="(event) => handleTagRemove(option, event)" />
                    </div>
                </template>
            </Multiselect>
        </div>

    </div>
</template>

<style></style>

