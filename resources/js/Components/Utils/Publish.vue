<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import Popover from "@/Components/Utils/Popover.vue"
import Button from '@/Components/Elements/Buttons/Button.vue'
import { watch } from 'vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { faAsterisk } from '@/../private/pro-solid-svg-icons'
import { faRocketLaunch } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faSpinnerThird, faAsterisk, faRocketLaunch)

const props = defineProps<{
    isHashSame: boolean  // is current Hash is same with published Hash
    currentHashData: string
    emptyDataHash: string  // md5 hash from empty data
    isLoading: boolean
    saveFunction: Function
    modelValue: string
    firstPublish: boolean  // Boolean state for indicate first publish or not
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

</script>

<template>
    <div class="flex items-center gap-2 relative" tabindex="-1">

        <!-- If first time Publishing, the Popover is not appear -->
        <Button v-if="firstPublish" label="Publish"
            :style="currentHashData == emptyDataHash ? 'disabled' : isHashSame ? 'disabled' : 'primary'"
            :key="currentHashData" icon="far fa-rocket-launch" @click="saveFunction()" />

        <!-- Popover (comment section) is appear if it 2nd publishing or more -->
        <Popover v-else>
            <template #button="{ isOpen }">
                <!-- Style: Compare the hash from current data with hash from empty data -->
                <Button v-if="!isOpen" label="Publish" :style="currentHashData == 'emptyDataHash'
                    ? 'disabled'
                    : isHashSame
                        ? 'disabled'
                        : isOpen
                            ? 'cancel'
                            : 'primary'" :key="currentHashData" icon="far fa-rocket-launch" />
                <Button v-else :style="`cancel`" icon="fal fa-times" label="Cancel" />
            </template>

            <!-- Section: Popover -->
            <template #content>
                <div>
                    <div class="inline-flex items-start leading-none">
                        <FontAwesomeIcon :icon="'fas fa-asterisk'" class="font-light text-[12px] text-red-400 mr-1" />
                        <span class="capitalize">{{ trans('comment') }}</span>
                    </div>
                    <div class="py-2.5">
                        <textarea rows="3" :value="modelValue"
                            @input="$emit('update:modelValue', $event.target?.value)"
                            class="block w-64 lg:w-96 rounded-md shadow-sm dark:bg-gray-600 dark:text-gray-400 border-gray-300 dark:border-gray-500 focus:border-gray-500 focus:ring-gray-500 sm:text-sm" />
                    </div>
                    <div class="flex justify-end">
                        <Button size="xs" @click="saveFunction()" icon="far fa-rocket-launch" label="Publish"
                            :key="modelValue.length" :style="modelValue.length ? 'primary' : 'disabled'">
                            <template #icon>
                                <FontAwesomeIcon v-if="isLoading" icon='fad fa-spinner-third' class='animate-spin'
                                    aria-hidden='true' />
                                <FontAwesomeIcon v-else icon='far fa-rocket-launch' class='' aria-hidden='true' />
                            </template>
                        </Button>
                    </div>
                </div>
            </template>
        </Popover>
    </div>
</template>

<style scoped></style>