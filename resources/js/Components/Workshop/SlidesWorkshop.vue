<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script  setup lang="ts">
  import { ref, onMounted, watch } from 'vue'
  import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
  import { faEye,  faTrashAlt, faAlignJustify } from "@/../private/pro-light-svg-icons"
  import { library } from '@fortawesome/fontawesome-svg-core';
  import draggable from "vuedraggable"
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import { v4 as uuidV4 } from 'uuid';
  import Input from '@/Components/Forms/Fields/Input.vue'
  import { trans } from "laravel-vue-i18n"
  import { useForm } from '@inertiajs/vue3'
  import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue";
  import { cloneDeep } from 'lodash'
  library.add(faEye,  faTrashAlt,faAlignJustify )
  const props = defineProps<{
        data: {
          delay: number,
          common: {
              corners:{
                  topLeft?: object,
                  topRight?: object,
                  bottomLeft?: object,
                  bottomRight?: object
              }

          },
          link?:string,
          slides: Array<
              {
                  id: string,
                  imageSrc: string
                  imageAlt: string,
                  link?: string,
                  corners:{
                      topLeft?: {
                          type: string,
                          data?: object
                      },
                      topRight?: object,
                      bottomLeft?: object,
                      bottomRight?: object
                  }
                  centralStage: {
                      title?: string
                      subtitle?: string
                      text?:string,
                      footer?:string
                  }

              }
          >,

      }
        filesChange: Function
        changeLink: Function
  }>()

  const isDragging = ref(false)
  const slides  = ref(props.data.slides )
  const fileInput = ref(null)
  const fileEdit = ref(slides.value[0])

  const onChange = () => {
    let setData = []
    for (const set of fileInput.value.files ) {
      if (set && set instanceof File) {
        setData.push({
          imageAlt: set.name,
          imageSrc: set,
          "visibility" : true,
          id: uuidV4()
        })
      }
    }
    const newFiles = [...setData]
    slides.value = [...slides.value, ...newFiles]
    props.data.slides = [...slides.value]
  }

  const generateThumbnail = (file) => {
    if (file.imageSrc && file.imageSrc instanceof File) {
      let fileSrc = URL.createObjectURL(file.imageSrc)
      setTimeout(() => {
        URL.revokeObjectURL(fileSrc)
      }, 1000)
      return fileSrc
    } else {
      return getImageUrl(file.imageSrc)
    }
  }

  const getImageUrl = (name: string) => {
    return new URL(`@/../../../../art/banner/` + name, import.meta.url).href
  }

  const generateName = (file) => {
    return file.imageAlt
  }

  const remove = (i) => {
    slides.value.splice(i, 1)
    props.data.slides = [...slides.value]
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
    for (const set of e.dataTransfer.files ) {
      if (set && set instanceof File) {
        setData.push({
          file: set,
          link: { label: "open", target: "" },
          imageAlt: set.name,
          imageSrc: 'img',
          id: uuidV4()
        })
      }
    }
    const newFiles = [...setData]
    slides.value = [...slides.value, ...newFiles]
    isDragging.value = false
  }

  const openEdit = (file) => {
    fileEdit.value = file
    _SlideWorkshop.value.current = 0
    setFormValue(file)
  }

const visible = (i) => {
    slides.value[i].visibility = !slides.value[i].visibility
    props.data.slides = [...slides.value]
  }

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
                type: 'corners',
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
                name: ['centralStage','title'],
                type: 'input',
                label: trans('Title'),
                value: ['centralStage','title']
            },
            {
                name: ['centralStage','subtitle'],
                type: 'input',
                label: trans('subtitle'),
                value: ['centralStage','subtitle']
            },
        ]
    },
])


onMounted(() => {
    setFormValue(fileEdit.value)
});

const _SlideWorkshop = ref(null)
const form = ref({});
const setFormValue=(data)=>{ form.value = useForm(data) , console.log(form) }

watch(form, (newValue) => {
  if (newValue.data()) {
    let oldFile = cloneDeep(fileEdit.value),
    newfile = newValue.data()
    fileEdit.value = { ...newfile, visibility: oldFile.visibility };
    const index = slides.value.findIndex((item) => item.id === fileEdit.value.id);
    if (index !== -1)  slides.value[index] = fileEdit.value;
    props.data.slides = slides.value;
  }
});

  </script>

  <template>
    <div class="flex flex-grow gap-2.5">
      <div class="w-30 p-2.5 border-dashed" style="border: 1px dashed #d9d9d9;" v-if="data.slides" @dragover="dragover" @dragleave="dragleave" @drop="drop">
          <div class='border-gray-300  h-66 p-2 border  mb-2 items-center'>
              <span class="ml-32">{{trans('Common properties')}}</span>
              </div>
          <div class="mb-2 text-lg font-medium">{{trans('Slides')}}</div>
        <draggable :list="data.slides" group="slide " item-key="id" handle=".handle">
          <template #item="{ element: file }">
            <div :class="[file.id !== fileEdit.id ?
            ' border-l-orange-500   border-l-4' :
             'border-gray-300',
             'flex relative h-66 p-2 border  mb-2 items-center ']">
              <font-awesome-icon icon="fal fa-align-justify"
                class="handle p-2.5 mr-4 text-gray-700 cursor-grab"></font-awesome-icon>
              <div class="img">
                <img class="overflow-hidden whitespace-nowrap overflow-ellipsis h-12 w-12 leading-60 text-center flex-none" :src="generateThumbnail(file)" />
              </div>
              <div class="overflow-hidden whitespace-nowrap overflow-ellipsis px-8 leading-tight flex-auto transition-all duration-300 cursor-pointer " @click="openEdit(file)">
                <div class="overflow-hidden whitespace-nowrap overflow-ellipsis">{{ generateName(file) }}</div>
              </div>
              <div class="flex justify-center items-center m-2.5">
                <button :class="['ml-2', file.visibility  ?  'text-orange-500' :  '']" type="button" @click="visible(slides.indexOf(file))" title="Remove file">
                  <font-awesome-icon :icon="['fal', 'eye']" />
                </button>
                <button class="ml-2 text-rose-500" type="button" @click="remove(slides.indexOf(file))" title="Remove file">
                  <font-awesome-icon :icon="['fal', 'trash-alt']" />
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
          <SlideWorkshop :fileEdit="fileEdit" :blueprint="blueprint" :form="form" ref="_SlideWorkshop"></SlideWorkshop>
      </div>
    </div>
  </template>
