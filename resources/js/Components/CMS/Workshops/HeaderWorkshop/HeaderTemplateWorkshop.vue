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
                  <!-- add Content -->
                  <div>
                      <h2 class="text-sm font-medium text-gray-900">Content</h2>
                      <div class="mt-2">
                          <div class="flex items-center space-x-3">
                              <div as="template" v-for="item in Dummy.addContent">
                                  <div v-if="item.value !== 'image'"
                                      class='relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none'>
                                      <div 
                                          :class="['flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit']">
                                          <div as="span">
                                              <FontAwesomeIcon :icon="item.icon" />
                                          </div>
                                      </div>
                                  </div>
                                  <div v-if="item.value == 'image'"
                                      class='relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none'>
                                      <div
                                          :class="['flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit']">
                                          <input type="file"  name="file" id="fileInput" class="sr-only"
                                              @change="Uploadimage" ref="fileInput" accept=".jpg,.jpeg,.png" />
                                          <label for="fileInput" as="span">
                                              <FontAwesomeIcon :icon="item.icon" />
                                          </label>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                    <!-- end add Content -->
  
                  <hr class="my-5" />
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
  
  