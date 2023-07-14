<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 14 Jul 2023 15:19:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { computed, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import Banner from "@/Components/Banner.vue";
import { capitalize } from "@/Composables/capitalize"
import TableHistories from "@/Pages/Tables/TableHistories.vue";

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
}>()



</script>


<template>
    <Banner :data="data.layout" />
</template>

