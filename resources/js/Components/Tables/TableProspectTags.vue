<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useLocaleStore } from "@/Stores/locale"
import Button from '@/Components/Elements/Buttons/Button.vue'
import { reactive } from 'vue'
import { usePage } from '@inertiajs/vue3'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5"></Table>
</template>


