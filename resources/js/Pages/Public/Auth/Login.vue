<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 17 Oct 2023 11:41:30 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Checkbox from '@/Components/Checkbox.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { trans } from 'laravel-vue-i18n'
import PureInput from '@/Components/Pure/PureInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import TextInput from '@/Components/TextInput.vue'
import Password from '@/Components/Forms/Fields/Password.vue'
import { notify } from '@kyvg/vue3-notification'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowLeft } from '@fal'
import { faSpinnerThird } from '@fad'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowLeft, faSpinnerThird)

const emits = defineEmits<{
    (e: 'onSuccessLogin'): void
}>()

const isLoading = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const resetPassword = useForm({
    email: ''
})

const submitResetPassword = () => {
    resetPassword.post(route('public.password.email'), {
        onFinish: () => form.reset('email'),
        onSuccess: () => {
            notify({
                title: "We already send email to your email.",
                text: 'Please check and reset you password',
                type: "success"
            })
        },
    })
}

const submit = async () => {
    form.post(route('public.login'), {
        onStart: () => {
            isLoading.value = true
        },
        onFinish: () => {
            form.reset('password')
        },
        onSuccess: () => {
            emits('onSuccessLogin')
        },
        onError: (errors: any) => {
            isLoading.value = false
            notify({
                title: trans("Login failed"),
                text: errors.message,
                type: "error"
            })
        },

    })
}

const condition: Ref<string | boolean> = ref(false)
</script>

<template layout="Public">
    <Head title="Login" />

    <div class="mt-16 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form v-if="!condition" class="space-y-6" @submit.prevent="submit">
                <div>
                    <div class="">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" placeholder="johndoe@mail.com" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <!-- <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                autocomplete="new-password" /> -->
                    <Password :form=form fieldName='password' :showProcessing="false" placeholder="Enter your password" />
                    <div @click="condition = 'forgotPassword'"
                        class="text-xs mt-2 pl-1 text-gray-500 cursor-pointer hover:text-gray-700">
                        Forgot password?
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <Checkbox name="remember-me" id="remember-me" v-model:checked="form.remember" />
                        <label for="remember-me" class="cursor-pointer ml-2 block text-sm text-gray-600 select-none"> {{
                            trans('Remember me') }} </label>
                    </div>
                </div>

                <Button
                    type="submit"
                    id="submit"
                    :loading="isLoading"
                    full
                    label="Login"
                >
                    <!-- {{ isLoading }} -->
                    <!-- <FontAwesomeIcon icon="fad fa-spinner-third"
                        class="ml-2 h-5 w-5 animate-spin opacity-0"
                        :class="{ 'opacity-100': isLoading }" /> -->
                </Button>
                <div>
                </div>

                <div>
                    <p class="text-sm font-light text-gray-500">
                        {{ trans('Don`t have an account yet?') }}
                        <Link :href="route('public.register')"
                            class="font-bold text-primary-700 hover:underline">{{ trans('Register') }}
                        </Link>
                    </p>
                </div>
            </form>

            <!-- Forgot Password: if click the 'forgot password' -->
            <div v-show="condition === 'forgotPassword'" class="space-y-4">
                <div class="flex items-center gap-x-1 text-gray-500 cursor-pointer hover:text-gray-700"
                    @click="condition = false">
                    <FontAwesomeIcon icon='fal fa-arrow-left' class='w-2' aria-hidden='true' />
                    <span class="text-xs">{{ trans('Back to login') }}</span>
                </div>
                <form class="space-y-5" @submit.prevent="submitResetPassword">
                    <div class="flex flex-col">
                        <label for="email" class="text-center font-medium text-gray-600">{{ trans('Reset password')
                        }}</label>
                    </div>
                    <div class="flex flex-col">
                        <div class="mt-1">
                            <input v-model="resetPassword.email" name="email" autocomplete="email" type="email" required
                                class="text-gray-700 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                                placeholder="Enter your email" />
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ trans('Send link via e-mail') }}
                        </button>
                    </div>
                </form>
            </div>
            <ValidationErrors />
        </div>
    </div>
</template>

<style></style>