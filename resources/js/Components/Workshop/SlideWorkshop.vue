<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 16:36:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, onMounted} from 'vue'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faImage} from "../../../private/pro-solid-svg-icons"
import {faTrashAlt} from "../../../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
import draggable from "vuedraggable"
import {get} from 'lodash'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from './Modal/Modal.vue'
import {v4 as uuidv4} from 'uuid';
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Phone from '@/Components/Forms/Fields/Phone.vue'
import Date from '@/Components/Forms/Fields/Date.vue'
import {trans} from "laravel-vue-i18n"
import Address from "@/Components/Forms/Fields/Address.vue"
import Radio from '@/Components/Forms/Fields/Radio.vue'
import Country from "@/Components/Forms/Fields/Country.vue"
import Currency from "@/Components/Forms/Fields/Currency.vue"
import {capitalize} from "@/Composables/capitalize"
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import {Head, useForm} from '@inertiajs/vue3'
import SlideBackground from "@/Components/Workshop/Fileds/SlideBackground.vue";


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
                name: 'file',
                type: 'slideBackground',
                label: trans('image'),
                value: null
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
                label: trans('top left corner'),
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
                value: null
            },
            {
                name: 'sub-title',
                type: 'input',
                label: trans('subtitle'),
                value: null
            },
        ]

    },


])
const current = ref(0)
let fields = {};
onMounted(() => {
    setFormValue()
});

const setFormValue = () => {
    for (const data of blueprint.value) {
        Object.entries(data.fields).forEach(([fieldName, fieldData]) => {
            fields[fieldName] = fieldData['value'];
        });
    }
}


const form = useForm(fields);

</script>

<template>

    <div class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x h-full">

        <!-- Left Tab: Navigation -->
        <aside class="py-0 lg:col-span-3 lg:h-full">
            <nav role="navigation" class="space-y-1">
                <ul>
                    <li v-for="(item, key) in blueprint" @click="current = key" :class="[
                key == current
                  ? 'bg-orange-50 border-orange-500 text-orange-700 hover:bg-orange-50 hover:text-orange-700'
                  : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900',
                'cursor-pointer group border-l-4 px-3 py-2 flex items-center text-sm font-medium',
              ]" :aria-current="key === current ? 'page' : undefined">
                        <FontAwesomeIcon
                            v-if="item.icon"
                            aria-hidden="true"
                            :flip="item.iconFlip?item.iconFlip:false"
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
                    <div v-for="(fieldData, fieldName, index ) in blueprint[current].fields" :key="index" class="mt-1 ">
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
                                            <component :is="getComponent(fieldData['type'])" :form="form" :fieldName="fieldName"
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

