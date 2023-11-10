<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 17 Oct 2023 11:41:30 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import PurePassword from '@/Components/Pure/PurePassword.vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowLeft } from '@fal/'
import { faSpinnerThird } from '@fad/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowLeft, faSpinnerThird)

const props = defineProps<{
    email: string
    password: string
    passwordRepeat: string

    emailField: {
        value: string
        status: string | boolean
        description: string
    }
    passwordField: {
        value: string
        valueRepeat: string
        description: string
    }
}>()

const emits = defineEmits<{
    (e: 'onSuccessLogin'): void
    (e: 'update:email', value: string): void
    (e: 'update:password', value: string): void
    (e: 'update:passwordRepeat', value: string): void
    (e: 'loginSuccess'): void
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


// On click check email registered or not
const onCheckEmail = async () => {
    try {
        const response = await axios.post(
            route('public.appointment.check.email'),
            {
                email: props.emailField.value
            }
        )
        props.emailField.status = 'success'
        props.emailField.description = 'Email is registered.'
        console.log(response.data)
    }
    catch (error: any) {
        props.emailField.status = 'error'
        props.emailField.description = 'Email is not registered yet.'
        console.log('error', error)
    }
}

const submitForm = () => {
    emits('loginSuccess')
}

const validateEmail = (email: string) => {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)
}

onMounted(() => {
    usePage().props.auth.user ? emits('loginSuccess') : false
})
</script>

<template layout="Public">
    <div class="flex flex-col gap-y-4">
        <div class="flex flex-col gap-x-2">
            <h3 class="text-sm font-semibold leading-3 text-gray-500">Enter your email</h3>
            <div class="flex gap-x-2 space-y-1 pl-0.5">
                <PureInput
                    :modelValue="email"
                    @update:modelValue="(val) => emits('update:email', val)"
                    placeholder="Input your email"
                    type="email"
                    @input="(emailField.status = false, emailField.description = '')"    
                />
                <div class="flex items-center h-full">
                    <Button
                        v-if="(typeof emailField.status != 'string')"
                        @click="onCheckEmail"
                        :key="email"
                        label="Check"
                        :style="validateEmail(email) ? `secondary` : 'disabled'"
                    />
                </div>
            </div>
            <div class="">
                <p v-if="!validateEmail(email)" class="text-xs italic text-red-500 mb-0">*Not a valid email.</p>
                <!-- <p v-if="validateEmail(email)" class="text-xs italic text-gray-500">{{ emailField.description }}</p> -->
            </div>
        </div>

        <!-- Section: Password and Submit -->
        <div class="flex gap-y-4 flex-col">
            <div v-if="emailField.status == 'success'" class="">
                <h3 class="text-sm font-semibold leading-3 text-gray-500">Your account is exist. Let's login!</h3>
                <PurePassword :modelValue="password" @update:modelValue="(val) => emits('update:password', val)" />
            </div>
            <div v-if="emailField.status == 'error'" class="space-y-1">
                <div class="">
                    <div class="text-xxs font-thin leading-none text-gray-500 italic mb-1">Looks like you don't have the account yet. Let's register!</div>
                    <div class="text-sm font-semibold leading-none text-gray-500">Enter your password</div>
                </div>
                <PurePassword :modelValue="password" @update:modelValue="(val) => emits('update:password', val)" />
                <PurePassword :modelValue="passwordRepeat" @update:modelValue="(val) => emits('update:passwordRepeat', val)" placeholder="Repeat your password" />
                <p v-if="passwordField.value != passwordField.valueRepeat" class="text-red-500 italic text-sm">*Password is not match</p>
            </div>
            <div v-if="typeof emailField.status === 'string'">
                <Button
                    @click="submitForm"
                    :style="'primary'"
                    :label="
                        emailField.status == 'error' ? 'register' : 'login'
                    "
                />
            </div>
        </div>
        <ValidationErrors />
    </div>
</template>