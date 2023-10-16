<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import {Head, useForm} from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'

const isPasswordSame = ref(false)
const repeatPassword = ref('')

const formReset = useForm({
    password: '',
})

const submitResetPassword = () => {
    formReset.patch(route('customer.reset-password.update'), {})
}



watchEffect(() => {
    formReset.password == repeatPassword.value ? isPasswordSame.value = true : isPasswordSame.value = false
})
</script>

<template layout="Public">
    <Head title="Reset Password"/>
    <div class="mt-16 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">

        <form class="space-y-8" @submit.prevent="submitResetPassword">
            <div class="text-center font-semibold text-xl">
                {{ trans("For security reasons please change your password") }}
            </div>

            <div class="flex flex-col gap-y-4">
                <!-- Field: Password -->
                <div class="">
                    <label for="password">{{ trans('New Password') }}</label>
                    <PureInput v-model="formReset.password" type="password" inputName="password" placeholder="Enter new password" />
                    <div v-if="formReset.errors.password">{{ formReset.errors.password }}</div>
                </div>

                <!-- Field: Repeat Password -->
                <div class="">
                    <label for="repeatPassword">{{ trans('Repeat New Password') }}</label>
                    <PureInput v-model="repeatPassword" type="password" inputName="repeatPassword" placeholder="Repeat your new password" />
                    <div v-if="!isPasswordSame && repeatPassword && formReset.password" class="text-red-500 mt-1 text-sm">Password is not match</div>
                </div>
            </div>

            <div class="flex justify-center">
                <Button :style="isPasswordSame ? 'primary' : 'disabled'" :key="formReset.password + repeatPassword" :label="'Reset Password'" @click="submitResetPassword" class=""/>
            </div>
        </form>
    </div>
    <ValidationErrors />
    </div>
</template>
