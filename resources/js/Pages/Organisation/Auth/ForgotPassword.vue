<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { notify } from '@kyvg/vue3-notification'
import PureInput from '@/Components/Pure/PureInput.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { router } from '@inertiajs/vue3'

const isPasswordSame = ref(false)
const repeatPassword = ref('')

const formReset = useForm({
    password: '',
})

const submitResetPassword = () => {
    formReset.patch(route('org.passwords.update.password'),
    {
        onSuccess: () => {
            formReset.reset('password')
            repeatPassword.value = ''
            notify({
                title: "Success!",
                type: "success",
                text: "Reset password complete.",
            })
            router.visit('/dashboard')
        }
    })
}



watchEffect(() => {
    formReset.password == repeatPassword.value ? isPasswordSame.value = true : isPasswordSame.value = false
})
</script>

<template layout="OrgAppGuest">
    <!-- <pre>{{ usePage().props }}</pre> -->
    <!-- Forgot Password: if click the 'forgot password' -->
    <div class="space-y-4 text-gray-600">
        <!-- <div class="flex items-center gap-x-1 text-gray-500 cursor-pointer hover:text-gray-700" @click="">
            <FontAwesomeIcon icon="fal fa-arrow-left" class="w-2" aria-hidden="true" />
            <span class="text-xs">{{ trans("Back to login") }}</span>
        </div> -->

        <form class="space-y-8" @submit.prevent="submitResetPassword">
            <div class="text-center font-semibold text-xl">
                {{ trans("The Administrator Ask You To Reset password") }}
            </div>

            <div class="flex flex-col gap-y-4">
                <!-- Field: Password -->
                <div class="">
                    <label for="password">New Password</label>
                    <PureInput v-model="formReset.password" type="password" inputName="password" placeholder="Enter new password" />
                    <div v-if="formReset.errors.password">{{ formReset.errors.password }}</div>
                </div>

                <!-- Field: Repeat Password -->
                <div class="">
                    <label for="repeatPassword">Repeat New Password</label>
                    <PureInput v-model="repeatPassword" type="password" inputName="repeatPassword" placeholder="Repeat your new password" />
                    <!-- <div v-if="formReset.errors.repeatPassword">{{ formReset.errors.repeatPassword }}</div> -->
                    <div v-if="!isPasswordSame && repeatPassword && formReset.password" class="text-red-500 mt-1 text-sm">Password is not match</div>
                </div>
            </div>

            <div class="flex justify-center">
                <Button :style="isPasswordSame ? 'primary' : 'disabled'" :key="formReset.password + repeatPassword" :label="'Reset Password'" @click="submitResetPassword" class=""/>
            </div>
        </form>
    </div>
    <ValidationErrors />
</template>
