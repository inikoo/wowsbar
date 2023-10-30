<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import { faSign, faGlobe } from '@fal/'
import { faLink } from '@far/'
import { library } from '@fortawesome/fontawesome-svg-core'

library.add(faSign, faGlobe, faLink)

const props = defineProps<{
    data: any
    tab?: string
}>()


</script>


<template>
    <div class="py-3 mx-auto px-5 space-y-4">


    </div>

</template>

