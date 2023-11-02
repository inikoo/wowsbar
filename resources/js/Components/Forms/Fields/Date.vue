<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 05 Apr 2023 11:18:06 Malaysia Time, Sanur, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'

import DatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

import { faExclamationCircle ,faCheckCircle} from '@fas/'
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
library.add(faExclamationCircle,faCheckCircle)


const props = defineProps(['form', 'fieldName','options', 'fieldData'])

const _dp = ref()

</script>


<template>
    <div class="relative">
        <DatePicker
            ref="_dp"
            v-model="form[fieldName]"
            :enable-time-picker="false"
            :format="'dd MMMM yyyy'"
            auto-apply
            :clearable="!fieldData.required ?? false"
            @update:model-value="() => form.errors[fieldName] = ''"
        >

            <!-- Button: 'Today' -->
            <template #action-extra="{ selectCurrentDate }">
                <Button @click="selectCurrentDate()" size="xs" label="Today" :style="'tertiary'" />
            </template>

            <!-- Button: Select -->
            <template #action-buttons>
                <Button size="xs" label="Select" @click="_dp.selectDate()"/>
            </template>
        </DatePicker>

        <!-- State: error icon -->
        <div v-if="form.errors[fieldName] || form.recentlySuccessful " class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <FontAwesomeIcon icon="fas fa-exclamation-circle" v-if="form.errors[fieldName]" class="h-5 w-5 text-red-500" aria-hidden="true" />
            <FontAwesomeIcon icon="fas fa-check-circle" v-if="form.recentlySuccessful" class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true"/>
        </div>
    </div>

    <!-- State: error deskripsi -->
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600">{{ form.errors[fieldName] }}</p>
</template>


