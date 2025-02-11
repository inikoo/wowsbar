<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { Action } from '@/types/Action'
import { ref } from 'vue'

const props = defineProps<{
    action: Action
    dataToSubmit?: any
}>()

// console.log(props.action)
const isButtonLoading = ref<boolean | string>(false)
</script>

<template>
    <!-- Button Group -->
    <div v-if="action.type === 'buttonGroup' && action.buttonGroup?.length" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
        <slot v-for="(button, index) in action.buttonGroup" :name="'button' + index">
            <Link
                :href="`${button.route?.name ? route(button.route?.name, button.route?.parameters) : route(button.href?.name, button.href?.parameters)}`"
                class=""
                :method="button.route?.method ?? 'get'"
                
                @start="() => isButtonLoading = 'buttonGroup' + index"
                @finish="() => button.fullLoading ? false : isButtonLoading = false"
                @error="() => button.fullLoading ? isButtonLoading = false : false"
            >
                <Button
                    :style="button.style"
                    :loading="isButtonLoading === 'buttonGroup' + index"
                    :label="button.label"
                    :icon="button.icon"
                    :iconRight="button.iconRight"
                    :key="`ActionButton${button.label}${button.style}`" :tooltip="button.tooltip"
                    class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                </Button>
            </Link>
        </slot>
    </div>

    <!-- Button -->
    <Link v-else-if="action.route"
        :href="action.route?.name ? route(action.route?.name, action.route?.parameters) : action.href?.name ? route(action.href?.name, action.href?.parameters) : '#'"
        :method="action.route?.method ?? 'get'"
        :as="action.route?.method ? 'button' : undefined"
        :data="action.route?.method !== 'get' ? dataToSubmit : null"
        @start="() => isButtonLoading = 'buttonGroup' + action.route?.name"
        @finish="() => action.fullLoading ? false : isButtonLoading = false"
        @error="() => action.fullLoading ? isButtonLoading = false : false"
    >
        <Button
            :style="action.style"
            :label="action.label"
            :icon="action.icon"
            :loading="isButtonLoading === 'buttonGroup' + action.route?.name"
            :iconRight="action.iconRight"
            :key="`ActionButton${action.label}${action.style}`" :tooltip="action.tooltip"
        />
    </Link>
</template>
