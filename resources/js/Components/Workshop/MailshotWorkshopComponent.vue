<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:57:22 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from "vue"
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/MJML.vue'
import Unlayer from '@/Components/CMS/Workshops/GrapeEditor/Unlayer.vue'
import { cloneDeep } from 'lodash'


const props = defineProps<{
    title: string
    updateRoute: object,
    loadRoute: object
    imagesUploadRoute: Object
}>()

const editor = import.meta.env.VITE_MAILSHOT_EDITOR

console.log('sdd',editor)

const isDataDirty = ref(cloneDeep(props.isDirty))

const getComponent = (componentName: string) => {
    const components: any = {
        'grape': GrapeEditor,
        'unlayer': Unlayer
    };
    return components[componentName] ?? null;

};


</script>
  
<template>
    <component :is="getComponent(editor)"
        @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer" :useBasic="true" :plugins="[]"
        :updateRoute="updateRoute" :loadRoute="loadRoute" :imagesUploadRoute="imagesUploadRoute">
    </component>
</template>
  
  



