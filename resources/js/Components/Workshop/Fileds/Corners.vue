<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import { ref } from 'vue'
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Radio from '@/Components/Forms/Fields/Radio.vue'
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import SlideBackground from "@/Components/Workshop/Fileds/SlideBackground.vue";
import Corners from "@/Components/Workshop/Fileds/Corners.vue";

const props = defineProps<{
  form: any,
  fieldName: string,
  options?: any,
  fieldData?: {
    placeholder: string
    readonly: boolean
    copyButton: boolean
  }
}>()


const getComponent = (componentName) => {
    const components = {
        'input': Input,
        'inputWithAddOn': InputWithAddOn,
        'select': Select,
        'radio': Radio,
        'slideBackground': SlideBackground,
        'corners':Corners
    };
    return components[componentName]
};

const Type = [
  {
    label: 'Footer',
    value: 'footer',
    fields: [
      {
        name: 'text',
        type: 'input',
        label: trans('Text'),
        value: ['data', 'text']
      },
    ]
  },
  {
    label: 'Corner text',
    value: 'cornerText',
    fields: [
      {
        name: 'title',
        type: 'input',
        label: trans('title'),
        value: ['data', 'title']
      },
      {
        name: 'subtitle',
        type: 'input',
        label: trans('subtitle'),
        value: ['data', 'subtitle']
      },
    ]
  },
  {
    label: 'Link button',
    value: 'linkButton',
    fields: [
      {
        name: 'text',
        type: 'input',
        label: trans('text'),
        value: ['data', 'title']
      },
      {
        name: 'subtitle',
        type: 'input',
        label: trans('subtitle'),
        value: ['data', 'subtitle']
      },
    ]
  },
  {
    label: 'Text', value: 'text',
    fields: [
      {
        name: 'text',
        type: 'input',
        label: trans('Text'),
        value: ['data', 'text']
      },
    ]
  },
]




const current = ref(0);
</script>


<template>
  <div class="h-64">
    <div class="w-full h-full flex">
      <div class="w-1/2 flex flex-col">
        <div class="border flex-grow hover:bg-blue-200">Top left</div>
        <div class="border flex-grow hover:bg-blue-200">Bottom left</div>
      </div>
      <div class="w-1/2 flex flex-col">
        <div class="border flex-grow hover:bg-blue-200">Top right</div>
        <div class="border flex-grow hover:bg-blue-200">Bottom right</div>
      </div>
    </div>

    <div class="w-full flex mt-3">
      <span class="isolate flex w-full rounded-md shadow-sm">
        <!-- Use v-for to loop through buttonType array -->
        <button v-for="(item, key) in Type" :key="item.value" type="button" @click="current = key"
        :class="['flex-grow', 'relative', 'inline-flex', 'items-center', 'justify-center', 'px-3', 'py-2', 'text-sm', 'font-semibold', 'text-gray-900', 'ring-1', 'ring-inset', 'ring-gray-300', 'focus:z-10', { 'bg-orange-500': current === key }]">
          {{ item.label }}
        </button>
      </span>
    </div>



    <div v-for="(fieldData, index ) in Type[current].fields" :key="index" class="mt-2.5">
      <dl class="divide-y divide-green-200  ">
        <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 max-w-2xl">
          <dt class="text-sm font-medium text-gray-500 capitalize">
            <div class="inline-flex items-start leading-none">

              <FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']"
                class="font-light text-[12px] text-red-400 mr-1" />
              <span>{{ fieldData.label }}</span>
            </div>
          </dt>
          <dd class="sm:col-span-2">
            <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
              <div class="relative flex-grow">
                <component :is="getComponent(fieldData['type'])" :form="props.form" :fieldName="fieldData.name"
                  :fieldData="fieldData" :key="index">
                </component>
                
              </div>
            </div>
          </dd>
        </div>
      </dl>
    </div>


  </div>
</template>
