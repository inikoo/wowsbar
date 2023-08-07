<script setup lang="ts">
import { ref, Ref } from 'vue'
import {Head, Link, useForm} from '@inertiajs/vue3'
import Password from '@/Components/Auth/LoginPassword.vue'
import Checkbox from '@/Components/Checkbox.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowLeft } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowLeft)

const form = useForm({
    username: '',
    password: '',
    remember: false,
})

const resetPassword = useForm({
    email: ''
})

const submit = () => {
    form.post(route('landlord.login'), {
        onFinish: () => form.reset('password'),
    })
}

const condition: Ref<string | boolean> = ref(false)
</script>

<template layout="LandlordAuthActions">

    <Head title="Login"/>
    <form v-if="!condition" class="space-y-6" @submit.prevent="submit" >
        <div>
            <label for="login" class="block text-sm font-medium text-gray-600">{{ trans('Username') }}</label>
            <div class="mt-1">
                <input v-model="form.username" id="username" name="username" autocomplete="username" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"/>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-600"> {{ trans('Password') }} </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <Password id="password" name="password" v-model="form.password"/>
            </div>
            <div @click="condition = 'forgotpassword'" class="text-xs mt-2 pl-1 text-gray-500 cursor-pointer hover:text-gray-700">
                Forgot password?
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <Checkbox name="remember-me" id="remember-me" v-model:checked="form.remember"/>
                <label for="remember-me" class="cursor-pointer ml-2 block text-sm text-gray-600"> {{ trans('Remember me') }} </label>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                {{ trans('Login') }}
            </button>
        </div>
        
        <div>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ trans('Don`t have an account yet?') }}
                <Link :href="route('landlord.register')" class="font-bold text-primary-700 hover:underline dark:text-primary-500">{{ trans('Register') }}</Link>
            </p>
        </div>
    </form>

    <!-- Forgot Password: if click the 'forgot password' -->
    <div v-show="condition === 'forgotpassword'" class="space-y-4">
        <div class="flex items-center gap-x-1 text-gray-500 cursor-pointer hover:text-gray-700" @click="condition = false">
            <FontAwesomeIcon icon='fal fa-arrow-left' class='w-2' aria-hidden='true' />
            <span class="text-xs">{{ trans('Back to login') }}</span>
        </div>
        <form class="space-y-5">
            <div class="flex flex-col">
                <label for="email" class="text-center font-medium text-gray-600">{{ trans('Reset password') }}</label>
            </div>
            <div class="flex flex-col">
                <div class="mt-1">
                    <input v-model="resetPassword.email" id="email" name="email" autocomplete="email" type="email" required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                        placeholder="Enter your email"
                    />
                    <div v-if="resetPassword.errors.email">{{ resetPassword.errors.email }}</div>
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

    <ValidationErrors/>

</template>
