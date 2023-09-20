<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script setup lang="ts">
  import { ref, reactive, watch } from 'vue'
  import { library } from '@fortawesome/fontawesome-svg-core';
  import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
  import { faHandPointer, faHandRock, faPlus, faText, faSearch, faImage, faTrash, faBars } from '@/../private/pro-solid-svg-icons';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { ulid } from "ulid";
  import { get } from 'lodash'
  import Input from '@/Components/CMS/Fields/Input.vue';
  import Layout from '../Header/Layout.vue';
  import ToolsTop from '@/Components/CMS/Header/ToolsTop.vue';
  import draggable from "vuedraggable"
  import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
  import HeaderTheme1 from '../../Header/HeaderTheme1.vue';
  import HeaderTheme2 from '../../Header/HeaderTheme2.vue';
  import Header from '@/Components/CMS/Header/index.vue'
  library.add(faHandPointer, faText, faSearch, faImage, faTrash, faBars)
  const props = defineProps<{
    data: Object,
  }>()
  
  
  const Dummy = {
      tools: [
          { name: 'edit', icon: ['fas', 'fa-hand-pointer'] },
          { name: 'grab', icon: ['fas', 'hand-rock'] },
          // { name: 'Heather Grey', icon: ['fas', 'fa-hand-pointer']},
      ],
      addContent: [
          { name: 'Text', value: 'text', icon: "fas fa-text" },
          { name: 'Image', value: 'image', icon: "fas fa-image" },
          { name: 'Search', value: 'search', icon: "fas fa-search" },
      ],
  }

  const set = {
    img : "https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600",
    appointment : {
      label : 'appointment',
      link : ''
    }
  }
  
  const data = reactive([...props.data.data.data])
  const layerActive = ref(null)
  
  const theme = ref({ name: 'Header theme One', value: 1 })
  
  const fileInput = ref(null)
  const Uploadimage = () => {
      let setData = {}
      for (const set of fileInput.value[0].files) {
          setData = {
              name: 'image',
              id: ulid(),
              type: 'image',
              style: { top: 0, left: 0, height : 200 , width : 200 },
              file: set
          }
      }
      data.splice(0, 0, setData)
  }
  

  
  </script>
  
  <template>
      <div class="bg-white">
          <div class="flex" @click="layerActive = null">
              <div class="w-[200px] p-6 overflow-y-auto overflow-x-hidden border border-gray-300 border-1 h-[46rem]">
              </div>
  
              <!-- editing area -->
              <div class="w-full bg-gray-200 border border-gray-300 overflow-hidden">
                  <ToolsTop  :data="data" :theme="theme" @click="(e)=>e.stopPropagation()" />
                    <div style="transform: scale(0.8);" class="w-full">
                      <Header :theme="theme.value" :data="set" />
                      </div>
              
               
              </div>
  
          </div>
  
      </div>
  </template>
  
  