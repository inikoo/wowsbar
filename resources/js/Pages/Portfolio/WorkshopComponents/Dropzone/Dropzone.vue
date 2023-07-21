<script  setup lang="ts">
import { ref, onMounted } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from "@/../private/pro-solid-svg-icons"
import { faTrash } from "@/../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core";
import draggable from "vuedraggable"
import { get } from 'lodash'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from '../Modal/Modal.vue'
import { v4 as uuidv4 } from 'uuid';
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Phone from '@/Components/Forms/Fields/Phone.vue'
import Date from '@/Components/Forms/Fields/Date.vue'
import { trans } from "laravel-vue-i18n"
import Address from "@/Components/Forms/Fields/Address.vue"
import Radio from '@/Components/Forms/Fields/Radio.vue'
import Country from "@/Components/Forms/Fields/Country.vue"
import Currency from "@/Components/Forms/Fields/Currency.vue"
import { capitalize } from "@/Composables/capitalize"
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import { Head, useForm } from '@inertiajs/vue3'

library.add(faImage, faTrash)
const props = defineProps<{
  files: Array
  filesChange: Function
  changeLink: Function
}>()

const isDragging = ref(false)
const files = ref(props.files)
const fileInput = ref(null)
const open = ref(false)
const fileEdit = ref(files.value[0])

const onChange = () => {
  let setData = []
  for (const set of fileInput.value.files) {
    if (set && set instanceof File) {
      setData.push({
        file: set,
        link: { label: "open", target: "" },
        imageAlt: set.name,
        imageSrc: 'img',
        id: uuidv4()
      })
    }
  }
  const newFiles = [...setData]
  files.value = [...files.value, ...newFiles]
  props.filesChange([...files.value])
}

const generateThumbnail = (file) => {
  if (file.file && file.file instanceof File) {
    let fileSrc = URL.createObjectURL(file.file)
    setTimeout(() => {
      URL.revokeObjectURL(fileSrc)
    }, 1000)
    return fileSrc
  } else {
    return getImageUrl(file.imageSrc)
  }
}

const getImageUrl = (name: string) => {
  return new URL(`@/../../../../../../art/banner/` + name, import.meta.url).href
}

const generateName = (file) => {
  return file.imageAlt
}

const remove = (i) => {
  files.value.splice(i, 1)
  props.filesChange([...files.value])
}

const dragover = (e) => {
  e.preventDefault()
  isDragging.value = true
}

const dragleave = () => {
  isDragging.value = false
}

const drop = (e) => {
  e.preventDefault()
  let setData = []
  for (const set of e.dataTransfer.files) {
    if (set && set instanceof File) {
      setData.push({
        file: set,
        link: { label: "open", target: "" },
        imageAlt: set.name,
        imageSrc: 'img',
        id: uuidv4()
      })
    }
  }
  const newFiles = [...setData]
  files.value = [...files.value, ...newFiles]
  isDragging.value = false
}

const openEdit = (file) => {
  fileEdit.value = file
  open.value = true
}


const changeIndex = () => {
  props.filesChange(files.value)
}

const getComponent = (componentName) => {
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



const blueprint = ref([
  {
    title: 'Banner',
    fields: [
      {
        name: 'file',
        type: 'input',
        label: 'Image',
        value: get(fileEdit.value, 'imageSrc')
      },
      {
        name: 'title',
        type: 'input',
        label: 'title',
        value: get(fileEdit.value, 'imageSrc')
      },
      {
        name: 'description',
        type: 'input',
        label: 'description',
        value: get(fileEdit.value, 'imageSrc')
      },
    ]

  },
  {
    title: 'Button & Link',
    fields: [
      {
        name: 'Label',
        type: 'input',
        label: 'Button Label',
        value: get(fileEdit.value, 'imageSrc')
      },
      {
        name: 'Link',
        type: 'input',
        label: 'Link',
        value: get(fileEdit.value, 'imageSrc')
      },
    ]

  },
])
const current = ref(0)
let fields = {};
onMounted(() => {
  setFormValue()
});

const setFormValue=()=>{
  for (const data of blueprint.value) {
  Object.entries(data.fields).forEach(([fieldName, fieldData]) => {
    fields[fieldName] = fieldData['value'];
  });
}
}




const form = useForm(fields);

</script>

<template>
  <div class="main">
    <div class="container-preview" v-if="files.length" @dragover="dragover" @dragleave="dragleave" @drop="drop">
      <div class="mb-2 text-lg font-medium">Banner list</div>
      <draggable :list="files" group="files" item-key="id" handle=".handle" @change="changeIndex">
        <template #item="{ element: file }">
          <div :class="file.id !== fileEdit.id ? 'flex relative h-66 p-2 border border-gray-300 rounded-md mb-2 items-center' : 'bg-orange-50 border-orange-500 text-orange-700 flex relative h-66 p-2 border rounded-md mb-2 items-center'">
            <font-awesome-icon icon="fa fa-align-justify"
              class="handle p-2.5 text-gray-300 cursor-grab"></font-awesome-icon>
            <div class="img">
              <img class="preview-img" :src="generateThumbnail(file)" />
            </div>
            <div class="title cursor-pointer " @click="openEdit(file)">
              <div class="overflow-hidden whitespace-nowrap overflow-ellipsis">{{ generateName(file) }}</div>
            </div>
            <div class="flex justify-center items-center m-2.5">
              <button class="ml-2 " type="button" @click="remove(files.indexOf(file))" title="Remove file">
                <font-awesome-icon :icon="['fas', 'pen']" />
              </button>
              <button class="ml-2" type="button" @click="remove(files.indexOf(file))" title="Remove file">
                <font-awesome-icon :icon="['fas', 'eye']" />
              </button>
              <button class="ml-2 text-rose-500" type="button" @click="remove(files.indexOf(file))" title="Remove file">
                <font-awesome-icon :icon="['fas', 'trash']" />
              </button>


            </div>
          </div>
        </template>
      </draggable>

      <PrimaryButton class="m-2.5">
        <input type="file" multiple name="file" id="fileInput" class="hidden-input" @change="onChange" ref="fileInput"
          accept=".pdf,.jpg,.jpeg,.png" />
        <label for="fileInput"> <font-awesome-icon :icon="['fas', 'plus']" class="mr-1" /> Banner</label>
      </PrimaryButton>
    </div>


    <div style="width: 70%; border: 1px solid #d9d9d9;">
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

                        <FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']"
                          class="font-light text-[12px] text-red-400 mr-1" />
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
    </div>


  </div>
</template>

<style>
.main {
  display: flex;
  flex-grow: 1;
  gap: 10px;
}

.dropzone-container {
  height: 208px;
  width: 100%;
  text-align: center;
  background: rgba(0, 0, 0, 0.02);
  border: 1px dashed #d9d9d9;
  border-radius: 8px;
  cursor: pointer;
  transition: border-color 0.3s;
  display: flex;
  justify-content: center;
  align-items: center;
}

.hidden-input {
  opacity: 0;
  overflow: hidden;
  position: absolute;
  width: 1px;
  height: 1px;
}

.preview-img {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  width: 48px;
  height: 48px;
  line-height: 60px;
  text-align: center;
  flex: none;
}

.title {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 0 8px;
  line-height: 1.5714285714285714;
  flex: auto;
  transition: all 0.3s;
}

.container-preview {
  width: 30%;
  padding: 10px;
  border: 1px dashed #d9d9d9;
}
</style>
