<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'
import { ref, watch } from 'vue'
import Banner from "@/Components/Slider/Banner.vue";
import FieldForm from '@/Components/Forms/FieldForm.vue';
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Phone from '@/Components/Forms/Fields/Phone.vue'
import Date from '@/Components/Forms/Fields/Date.vue'
import {trans} from "laravel-vue-i18n"
import Address from "@/Components/Forms/Fields/Address.vue"
import Radio from '@/Components/Forms/Fields/Radio.vue'
import Country from "@/Components/Forms/Fields/Country.vue"
import Currency from "@/Components/Forms/Fields/Currency.vue"
import { capitalize } from "@/Composables/capitalize.js"
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import {Head, useForm} from '@inertiajs/vue3'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  closeModal: {
    type: Function,
    required: true
  },
  data: {
    type: Object,
    required: false
  },
  changeLink: {
    type: Function,
  }
})

const getComponent = (componentName) => {
  console.log('set',componentName)
    const components = {
        'input': Input,
        'inputWithAddOn': InputWithAddOn,
        'phone': Phone,
        'date': Date,
        'select': Select,
        'address': Address,
        'radio': Radio,
        'country': Country,
        'currency': Currency,
    };
    return components[componentName]
};





const dataSet = ref(props.data)

watch(() => props.data, (newValue) => { dataSet.value = newValue })

const blueprint = ref([
  {
    title: 'Banner Image',
    fields: [
      {
        name: 'file',
        type: 'input',
        label: 'Image',
        value: dataSet.value.imageSrc
      },
    ]

  },
  {
    title: 'Label & Link',
    fields: [
      {
        name: 'Label',
        type: 'input',
        label: 'Button Label',
        value:  dataSet.value.imageSrc
      },
      {
        name: 'Link',
        type: 'input',
        label: 'Link',
        value:  dataSet.value.imageSrc
      },
    ]

  },
  {
    title: 'Banner Text',
    fields: [
      {
        name: 'background',
        type: 'input',
        label: 'background',
        value:  dataSet.value.imageSrc
      },
    ]

  },
])
const current = ref(0)

let fields = {};

for(const data of blueprint.value){
  Object.entries(data.fields).forEach(([fieldName, fieldData]) => {
    console.log('tst',fieldName, fieldData)
    fields[fieldName] = fieldData['value'];
  });
}

const form = useForm(fields);

</script>
<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95">
            <DialogPanel
              class="w-full transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                Edit Banner
              </DialogTitle>

              <div>
                <Banner :data="{ data: { slides: [{...dataSet}] } }" />
              </div>

              <div class="shadow p-2.5 my-2.5">
                <div class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x">

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
                          <FontAwesomeIcon v-if="item.icon" aria-hidden="true" :class="[
                            key === current
                              ? 'text-orange-500 group-hover:text-orange-500'
                              : 'text-gray-400 group-hover:text-gray-500',
                            'flex-shrink-0 -ml-1 mr-3 h-6 w-6',
                          ]" :icon="item.icon" />

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

                                              <FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']" class="font-light text-[12px] text-red-400 mr-1"/>
                                              <span>{{ fieldData.label }}</span>
                                          </div>
                                      </dt>
                                      <dd class="sm:col-span-2">
                                          <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                              <div class="relative flex-grow">
                                                  <component
                                                      :is="getComponent(fieldData['type'])"
                                                      :form="form"
                                                      :fieldName="fieldName"
                                                      :fieldData="fieldData"
                                                      :key="index"
                                                  >
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
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>



