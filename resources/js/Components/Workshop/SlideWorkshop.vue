<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 16:36:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, onMounted, defineExpose } from 'vue'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faImage,faExpandArrows,faAlignCenter} from "../../../private/pro-light-svg-icons"
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'

import {trans} from "laravel-vue-i18n"
import Radio from '@/Components/Forms/Fields/Radio.vue'

import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import {useForm} from '@inertiajs/vue3'
import SlideBackground from "@/Components/Workshop/Fileds/SlideBackground.vue";
import {library} from "@fortawesome/fontawesome-svg-core";

library.add(faImage,faExpandArrows,faAlignCenter)
const props = defineProps<{
    fileEdit : Object
  }>()

defineExpose({
    setFormValue
});

const getComponent = (componentName) => {
    const components = {
        'input': Input,
        'inputWithAddOn': InputWithAddOn,
        'select': Select,
        'radio': Radio,
        'slideBackground': SlideBackground
    };
    return components[componentName]
};


const blueprint = ref([
    {
        title: trans('Background'),
        icon: ['fal', 'fa-image'],
        fields: [
            {
                name: 'imageSrc',
                type: 'slideBackground',
                label: trans('image'),
                value: ['imageSrc']
            },
        ]

    },
    {
        title: trans('corners'),
        icon: ['fal', 'fa-expand-arrows'],
        fields: [
            {
                name: 'top-left',
                type: 'slideBackground',
                label: null,
                value: null
            },
        ]

    },
    {
        title: trans('central stage'),
        icon: ['fal', 'fa-align-center'],
        fields: [
            {
                name: 'title',
                type: 'input',
                label: trans('Title'),
                value: ['centralStage','title']
            },
            {
                name: 'subtitle',
                type: 'input',
                label: trans('subtitle'),
                value: ['centralStage','subtitle']
            },
        ]

    },


])
const current = ref(0)

onMounted(() => {
    setFormValue()
});

 function setFormValue () {
    let fields = {};
    for (const section of blueprint.value) {
      for (const field of section.fields) {
        if (Array.isArray(field.value)) {
          fields[field.name] = getNestedValue(props.fileEdit, field.value);
        } else {
          fields[field.name] = props.fileEdit[field.value];
        }
      }
    }
    form.value = useForm(fields);
  }

  const getNestedValue = (obj, keys) => {
    return keys.reduce((acc, key) => {
      if (acc && typeof acc === 'object' && key in acc) return acc[key];
      return null;
    }, obj);
  }


const form = ref({});

</script>

<template>

    <div class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x h-full">

        <!-- Left Tab: Navigation -->
        <aside class="py-0 lg:col-span-3 lg:h-full">
            <nav role="navigation" class="space-y-1">
                <ul>
                    <li v-for="(item, key) in blueprint" @click="current = key" :class="[
                key == current
                  ? 'bg-gray-100 border-orange-500 text-orange-700 hover:bg-gray-100 hover:text-orange-700'
                  : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900',
                'cursor-pointer group border-l-4 px-3 py-2 flex items-center text-sm font-medium',
              ]" :aria-current="key === current ? 'page' : undefined">
                        <FontAwesomeIcon
                            v-if="item.icon"
                            aria-hidden="true"
                            :class="[
                  key === current
                    ? 'text-orange-500 group-hover:text-orange-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'flex-shrink-0 -ml-1 mr-3 h-6 w-6',
                ]" :icon="item.icon"/>

                        <span class="capitalize truncate">{{ item.title }}</span>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Content of forms -->
        <div class="px-4 sm:px-6 md:px-4 col-span-9">
            <div class="divide-y divide-grey-200 flex flex-col">
                <div class="mt-2 pt-4 sm:pt-5">
                    <div v-for="(fieldData, index ) in blueprint[current].fields" :key="index" class="mt-1 ">
                        <dl class="divide-y divide-green-200  ">
                            <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 max-w-2xl">
                                <dt class="text-sm font-medium text-gray-500 capitalize">
                                    <div class="inline-flex items-start leading-none">

                                        <FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']"
                                                         class="font-light text-[12px] text-red-400 mr-1"/>
                                        <span>{{ fieldData.label }}</span>
                                    </div>
                                </dt>
                                <dd class="sm:col-span-2">
                                    <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                        <div class="relative flex-grow">
                                            <component :is="getComponent(fieldData['type'])" :form="form" :fieldName="fieldData.name"
                                                       :fieldData="fieldData" :key="index">
                                            </component>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

