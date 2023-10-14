<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 24 Aug 2023 09:33:07 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Slider from "@/Components/Slider/Slider.vue";

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

<template >
    <div class="w-full">
        <Slider :data="data" class="h-full" />
    </div>
</template>

