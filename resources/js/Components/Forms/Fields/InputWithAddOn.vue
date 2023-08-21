<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 23:44:10 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->


<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faDollarSign } from "@/../private/pro-regular-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import { faExclamationCircle, faCheckCircle } from "@/../private/pro-solid-svg-icons"
import { faSpinnerThird } from "@/../private/pro-duotone-svg-icons"
library.add(faExclamationCircle, faCheckCircle, faSpinnerThird)

library.add(faDollarSign)


const props = defineProps<{
    form: any,
    fieldName: string,
    options?: any,
    fieldData?: {
        placeholder?: string
        leftAddOn?: {
            icon?: object,
            label?: string,
        }
        rightAddOn?: {
            icon?: object,
            label?: string,
        }
    }
}>()
</script>

<template>
    <div>
        <label :for="fieldName" class="block text-sm font-medium leading-6 text-gray-800"></label>
        <div
            class="relative flex rounded-md px-3 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-500 sm:max-w-md">
            <!-- Add On: Left -->
            <div v-if="fieldData?.leftAddOn" class="flex items-center gap-x-1.5">
                <div v-for="leftAddOn in fieldData?.leftAddOn"
                    class="flex select-none items-center text-gray-400 sm:text-sm">
                    <FontAwesomeIcon v-if="(typeof leftAddOn == 'object')" :icon="leftAddOn" aria-hidden="true">{{ leftAddOn
                    }}</FontAwesomeIcon>
                    <span v-if="(typeof leftAddOn == 'string')" class="leading-none mb-0.5">{{ leftAddOn }}</span>
                </div>
            </div>

            <input v-model="form[fieldName]" type="text" :name="fieldName" :id="fieldName"
                class="block flex-1 border-0 bg-transparent py-1.5 px-1 mb-0.5 leading-none placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                :placeholder="fieldData?.placeholder ?? ''" />

            <!-- Add On: Right -->
            <div v-if="fieldData?.rightAddOn" class="flex items-center gap-x-1.5">
                <div v-for="rightAddOn in fieldData?.rightAddOn"
                    class="flex select-none items-center text-gray-400 sm:text-sm">
                    <FontAwesomeIcon v-if="(typeof rightAddOn == 'object')" :icon="rightAddOn" aria-hidden="true">{{
                        rightAddOn }}</FontAwesomeIcon>
                    <span v-if="(typeof rightAddOn == 'string')" class="leading-none mb-0.5">{{ rightAddOn }}</span>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <FontAwesomeIcon
                v-if="form.errors[fieldName]"
                icon="fas fa-exclamation-circle"
                class="h-5 w-5 text-red-500"
                aria-hidden="true"
            />
            <FontAwesomeIcon
                v-if="form.recentlySuccessful"
                icon="fas fa-check-circle"
                class="h-5 w-5 text-green-500"
                aria-hidden="true"
            />
            <FontAwesomeIcon
                v-if="form.processing"
                icon="fad fa-spinner-third"
                class="h-5 w-5 animate-spin dark:text-gray-200"
            />
        </div>
        </div>
        <p
            v-if="form.errors[fieldName]"
            class="mt-2 text-sm text-red-600"
            :id="`${fieldName}-error`"
        >
            {{ form.errors[fieldName] }}
        </p>
    </div>
</template>



