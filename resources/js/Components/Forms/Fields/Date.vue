<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 05 Apr 2023 11:18:06 Malaysia Time, Sanur, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import PureDatePicker from '@/Components/Pure/PureDatePicker.vue'

import { faExclamationCircle, faCheckCircle } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
library.add(faExclamationCircle, faCheckCircle)

const props = defineProps(['form', 'fieldName', 'options', 'fieldData'])

</script>


<template>
    <div class="relative">
        <PureDatePicker
            v-model="form[fieldName]"
            :format="'dd MMMM yyyy'"
            :timePicker="false"
            :required="fieldData.required"
            @update:model-value="() => form.errors[fieldName] = ''"
        />

        <!-- State: error icon -->
        <div v-if="form.errors[fieldName] || form.recentlySuccessful"
            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="fas fa-exclamation-circle" v-if="form.errors[fieldName]" class="h-5 w-5 text-red-500"
                aria-hidden="true" />
            <FontAwesomeIcon icon="fas fa-check-circle" v-if="form.recentlySuccessful"
                class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true" />
        </div>
    </div>

    <!-- State: error deskripsi -->
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600">{{ form.errors[fieldName] }}</p>
</template>