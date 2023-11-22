<script setup lang="ts">
import { get, set, isArray, cloneDeep } from 'lodash'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue'
import Multiselect from "@vueform/multiselect"

const props = withDefaults(defineProps<{
    value: any
    fieldName: any
}>(), {})

</script>

<template>
    <SwitchGroup as="div" class="flex items-center">
        <Switch v-model="value[fieldName].state"
            :class="[value[fieldName].state ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
            <span aria-hidden="true"
                :class="[value[fieldName].state ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
        </Switch>
        <SwitchLabel as="span" class="ml-3 text-sm">
            <span class="font-medium text-gray-900">{{ value[fieldName].state ? 'Last contact' : 'Never' }}</span>
        </SwitchLabel>
    </SwitchGroup>


    <div v-if="value[fieldName].state" class="flex flex-col gap-y-2 mt-4">
        <div class="flex gap-x-2">
            <div class="w-20">
                <PureInput type="number" :minValue="1" :caret="false" placeholder="range"
                    v-model="value[fieldName].data.quantity" />
            </div>
            <div class="w-full">
                <Multiselect :options="['day', 'week', 'month']" placeholder="Pick a range"
                    v-model="value[fieldName].data.unit" :can-clear="false" />
            </div>
        </div>
    </div>
</template>

<style>
</style>

