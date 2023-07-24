<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script  setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
  import { faEye,  faTrash } from "@/../private/pro-solid-svg-icons"
  import { library } from '@fortawesome/fontawesome-svg-core';
  import draggable from "vuedraggable"
  import { get } from 'lodash'
  import PrimaryButton from "@/Components/PrimaryButton.vue";
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
  import Toggle from "@/Components/Forms/Fields/Toggle.vue"
  import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
  import SlideBackground from "@/Components/Workshop/Fileds/SlideBackground.vue";
  import { useForm } from '@inertiajs/vue3'
  library.add(faEye,  faTrash )
  const props = defineProps<{
    slide : Array
    filesChange: Function
    changeLink: Function
  }>()

  const isDragging = ref(false)
  const slide  = ref(props.slide )
  const fileInput = ref(null)
  const open = ref(false)
  const fileEdit = ref(slide .value[0])

  const onChange = () => {
    let setData = []
    for (const set of fileInput.value.slide ) {
      if (set && set instanceof File) {
        setData.push({
          link: { label: "open", target: "" },
          imageAlt: set.name,
          imageSrc: set,
          id: uuidv4()
        })
      }
    }
    const newFiles = [...setData]
    slide .value = [...slide .value, ...newFiles]
    props.filesChange([...slide .value])
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
    slide .value.splice(i, 1)
    props.filesChange([...slide .value])
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
    for (const set of e.dataTransfer.slide ) {
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
    slide .value = [...slide .value, ...newFiles]
    isDragging.value = false
  }

  const openEdit = (file) => {
    fileEdit.value = file
    open.value = true
    ChangeData(form.value.data())
    setFormValue()
  }

  const ChangeData = (newData) =>{
    const index = slide .value.findIndex((item)=>item.id == fileEdit.value.id)
    slide [index] = {
      ...slide [index],
      visibility : newData.visibility,
      imageSrc  : newData.imageSrc,
      text : {
        subtitle: newData.subtitle,
        title:  newData.title
      },
      link : {
        label :  newData.title,
        target : newData.target
      }
    }
    props.filesChange(slide )
  }


  const changeIndex = () => {
    props.filesChange(slide .value)
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
      'toggle': Toggle,
      'slideBackground': SlideBackground
    };
    return components[componentName]
  };



  const blueprint = ref([
    {
      title: trans('Background'),
      fields: [
        {
          name: 'visibility',
          type: 'toggle',
          label: 'Visibility',
          value: 'visibility'
        },
        {
          name: 'imageSrc',
          type: 'slideBackground',
          label: 'Banner Image',
          value: 'imageSrc'
        },
      ]
    },
    {
      title: trans('corners'),
      fields: [
        {
          name: 'Visibel',
          type: 'input',
          label: 'description',
          value: 'visibel'
        },
        {
          name: 'label',
          type: 'input',
          label: 'Button Label',
          value: ['link','label']
        },
        {
          name: 'target',
          type: 'input',
          label: 'Link',
          value: ['link','target']
        },
      ]
    },
    {
      title:  trans('central stage'),
      fields: [
        {
          name: 'title',
          type: 'input',
          label: 'Title',
          value: ['text','title']
        },
        {
          name: 'subtitle',
          type: 'input',
          label: 'Subtitle',
          value: ['text','subtitle']
        },
        {
          name: 'Position',
          type: 'select',
          label: 'position',
          value: ['text','position'],
          options : ['center','topLeft','bottomLeft','topRight','bottomRight']
        },
      ]
    },
  ])
  const current = ref(0)


  onMounted(() => {
    setFormValue();
  });

  const setFormValue = () => {
    let fields = {};
    for (const section of blueprint.value) {
      for (const field of section.fields) {
        // Check if the value is an array (nested field value)
        if (Array.isArray(field.value)) {
          fields[field.name] = getNestedValue(fileEdit.value, field.value);
        } else {
          fields[field.name] = fileEdit.value[field.value];
        }
      }
    }

    form.value = useForm(fields);
  }

  const getNestedValue = (obj, keys) => {
    return keys.reduce((acc, key) => {
      if (acc && typeof acc === 'object' && key in acc) {
        return acc[key];
      }
      return null; // Or you can return a default value if the key is not found
    }, obj);
  }


  const form = ref(useForm({}));

  </script>

  <template>
    <div class="flex flex-grow gap-2.5">
      <div class="w-30 p-2.5 border-dashed" style="border: 1px dashed #d9d9d9;" v-if="slide .length" @dragover="dragover" @dragleave="dragleave" @drop="drop">
        <div class="mb-2 text-lg font-medium">Banner list</div>
        <draggable :list="slide " group="slide " item-key="id" handle=".handle" @change="changeIndex">
          <template #item="{ element: file }">
            <div :class="file.id !== fileEdit.id ? 'flex relative h-66 p-2 border border-gray-300 rounded-md mb-2 items-center' : 'bg-orange-50 border-orange-500 text-orange-700 flex relative h-66 p-2 border rounded-md mb-2 items-center'">
              <font-awesome-icon icon="fa fa-align-justify"
                class="handle p-2.5 text-gray-300 cursor-grab"></font-awesome-icon>
              <div class="img">
                <img class="overflow-hidden whitespace-nowrap overflow-ellipsis h-12 w-12 leading-60 text-center flex-none" :src="generateThumbnail(file)" />
              </div>
              <div class="overflow-hidden whitespace-nowrap overflow-ellipsis px-8 leading-tight flex-auto transition-all duration-300 cursor-pointer " @click="openEdit(file)">
                <div class="overflow-hidden whitespace-nowrap overflow-ellipsis">{{ generateName(file) }}</div>
              </div>
              <div class="flex justify-center items-center m-2.5">
                <button class="ml-2" type="button" @click="remove(slide .indexOf(file))" title="Remove file">
                  <font-awesome-icon :icon="['fas', 'eye']" />
                </button>
                <button class="ml-2 text-rose-500" type="button" @click="remove(slide .indexOf(file))" title="Remove file">
                  <font-awesome-icon :icon="['fas', 'trash']" />
                </button>


              </div>
            </div>
          </template>
        </draggable>

        <PrimaryButton class="m-2.5">
          <input type="file" multiple name="file" id="fileInput" class="opacity-0 overflow-hidden absolute w-1 h-1" @change="onChange" ref="fileInput"
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
                <div v-for="(fieldData, index ) in blueprint[current].fields" :key="index" class="mt-1 ">
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
                            <component :is="getComponent(fieldData['type'])" :form="form" :fieldName="fieldData.name" :options="fieldData.options"
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
