<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 14 Jul 2023 15:19:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core'
import Input from '@/Components/Forms/Fields/Input.vue';
import Slider from "@/Components/Slider/Slider.vue"


import { faWindowMaximize, faGlobe } from "@/../private/pro-light-svg-icons"
import EmptyState from '@/Components/Utils/EmptyState.vue';

library.add(faWindowMaximize, faGlobe)

const props = defineProps<{
    data: {
        layout: {
            parameters: {}
            data: {
                delay: number,
                slides: [
                    {
                        imageSrc: string,
                        imageAlt: string,
                        link?: {
                            label: string,
                            target: string
                        }
                    }
                ]
            }
        }
    }
    tab?: string
}>()

console.log(props.data)

</script>


<template>
    <Slider v-if="data.banner.components.length" :data="data.banner" />
    <EmptyState v-else /> <!-- If slide is not exist -->
    <div class="p-2.5">
        <Input :fieldData="{copyButton : true, readonly: true}" fieldName='url' :form="{url: data.url }" />
    </div>
    
</template>

