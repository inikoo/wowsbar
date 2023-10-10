<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { capitalize } from "@/Composables/capitalize"
import PageHeading from '@/Components/Headings/PageHeading.vue';
import Edit from '@/Components/Edit.vue';
import {library} from '@fortawesome/fontawesome-svg-core';

import { faGlobe, faUserPlus, faSignalStream} from "@/../private/pro-light-svg-icons";

library.add(faGlobe, faUserPlus, faSignalStream)

const props = defineProps<{
    title: string,
    pageHead: {
        title: string
        exitEdit: {
            route: {
                name: string
                parameters: string[]
            }
        }
    },
    formData: {
        blueprint: Array<{
            title: string
            icon: string
            current: boolean
            fields: Array<
                {
                    name: string,
                    type: string,
                    label: string,
                    value: string | object
                }
            >
            button: {
                title: string
                route: string
                disable: boolean
            }
        }>,
        args: {
            updateRoute: {
                name: string,
                parameters: string | string[]
            }
        }
    }
}>()

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Edit :form-data="formData" appName="organisation" />
</template>



