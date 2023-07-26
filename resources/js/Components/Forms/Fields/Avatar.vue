<script setup>
import { trans } from "laravel-vue-i18n"
import { ref } from "vue"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faExclamationCircle, faCheckCircle } from "@/../private/pro-solid-svg-icons"
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

library.add(faSpinnerThird, faExclamationCircle, faCheckCircle, faSpinnerThird)

const props = defineProps(['form', 'fieldName', 'options'])
const temporaryAvatar = ref(props.form.avatar)

const avatarUploaded = (file) => {
    props.form.avatar = file
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = (e) => {
        temporaryAvatar.value = e.target.result
    }
}

</script>

<template>
    <div class=" w-fit">
        <!-- Avatar Button: Small view -->
        <div class="mt-1 lg:hidden">
            <div class="flex items-center">
                <div class="inline-block h-12 w-12 flex-shrink-0 overflow-hidden rounded-full" aria-hidden="true">
                    <img id="avatar_mobile" class="h-full w-full rounded-full" :src="temporaryAvatar" alt="" />
                </div>
                <div class="ml-5 rounded-md shadow-sm">
                    <div
                        class="group relative flex items-center justify-center rounded-md dark:bg-gray-600 border border-gray-300 dark:border-gray-500 py-2 px-3 focus-within:ring-2 focus-within:ring-gray-500 focus-within:ring-offset-2 hover:bg-gray-50">
                        <label for="input-avatar-small"
                            class="pointer-events-none relative text-sm font-medium leading-4 text-gray-700 dark:text-gray-400">
                            <span>{{ trans("Change") }}</span>
                        </label>
                        <input id="input-avatar-small" name="user-photo" type="file"
                            @input="avatarUploaded($event.target.files[0])"
                            class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Avatar Button: Large view -->
        <div class="relative hidden overflow-hidden rounded-full lg:block">
            <img class="relative h-40 w-40 rounded-full" :src="temporaryAvatar" alt="" />
            <label id="input-avatar-large-mask" for="input-avatar-large"
                class="absolute inset-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 text-sm font-medium text-white opacity-0 hover:opacity-100">
                <span>{{ trans("Change") }}</span>
                <input type="file" @input="avatarUploaded($event.target.files[0])" id="input-avatar-large" name="input-avatar-large" accept="image/*"
                    class="absolute inset-0 h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
            </label>
        </div>

        <!-- Icon: Error, Success, Loading -->
        <div class="absolute top-2 right-0 pr-3 flex items-center pointer-events-none">
            <FontAwesomeIcon v-if="form.errors[fieldName]" icon="fas fa-exclamation-circle" class="h-5 w-5 text-red-500" aria-hidden="true" />
            <FontAwesomeIcon v-if="form.recentlySuccessful" icon="fas fa-check-circle" class="h-5 w-5 text-green-500" aria-hidden="true" />
            <FontAwesomeIcon v-if="form.processing" icon="fad fa-spinner-third" class="h-5 w-5 animate-spin dark:text-gray-200"/>
        </div>
        
        <div v-if="props.form.errors.avatar" class="text-red-700">
            {{ props.form.errors.avatar }}
        </div>
    </div>
</template>


