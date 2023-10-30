<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:57:22 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script setup lang="ts">
  import { ref } from "vue"
  import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
  import { HeroPlugins, AppointmentPlugins, BlogPlugins, StatisticsPlugins, PricingPlugins, CtaPlugins, Email  } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";
  import { cloneDeep } from 'lodash'

  const props = defineProps<{
      title: string
      pageHead: any
      tabs: {
          current: string
          navigation: object
      }
      updateRoute: object,
      loadRoute: object
      imagesUploadRoute: Object
  }>()
  
  
  const isDataDirty = ref(cloneDeep(props.isDirty))
  
  </script>
  
  <template layout="OrgApp">
      <GrapeEditor @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer" :useBasic="true"
          :plugins="[Email]" :updateRoute="updateRoute" :loadRoute="loadRoute"
          :imagesUploadRoute="imagesUploadRoute">
      </GrapeEditor>
  </template>
  
  



