<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 14 Jul 2023 15:19:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';

import Slider from "@/Components/Slider/Slider.vue";


import { faWindowMaximize, faGlobe } from "@/../private/pro-light-svg-icons"

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
</script>


<template>
    <Slider :data="data" />
</template>

