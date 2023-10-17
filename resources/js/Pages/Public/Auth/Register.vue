<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 17 Oct 2023 11:48:56 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import Password from '@/Components/Forms/Fields/Password.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@fad/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faSpinnerThird)

const form = useForm({
    contact_name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('customer.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template layout="Public">
    <Head title="Register" />
    <div class="mt-16 sm:mx-auto sm:w-full sm:max-w-md">
    <form @submit.prevent="submit" class="space-y-6">
        <!-- Field: Name -->
        <div>
            <InputLabel for="name" value="Name"  />
            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.contact_name" required autofocus
               autocomplete="name" placeholder="John Doe"/>

            <InputError  class="mt-2" :message="form.errors.contact_name" />
        </div>

        <!-- Field: Email -->
        <div class="">
            <InputLabel for="email" value="Email" />
            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                autocomplete="username" placeholder="johndoe@mail.com" />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- Field: Password -->
        <div class="">
            <InputLabel for="password" value="Password" />
            <!-- <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                autocomplete="new-password" /> -->
            <Password :form=form fieldName='password' :showProcessing="false" placeholder="Enter your password" />
        </div>

        <!-- Field: Confirm Password -->
        <div class="">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <!-- <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                v-model="form.password_confirmation" required autocomplete="new-password" /> -->
            <Password :form=form fieldName='password_confirmation' :showProcessing="false" placeholder="Re-enter your password" />
        </div>

        <!-- Button: Submit register -->
        <div class="">
            <button type="submit"  id="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                :class="{ 'opacity-50 cursor-not-allowed': form.processing }" :disabled="form.processing">
                <div class="pl-3">{{ trans('Register') }}</div>
                <FontAwesomeIcon icon="fad fa-spinner-third" class="ml-2 h-5 w-5 animate-spin dark:text-gray-200 opacity-0" :class="{'opacity-100': form.processing}" />
            </button>
        </div>

        <div class="">
            <div class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ trans('Already registered?') }}
                <Link :href="route('public.login')" id="login-link"
                    class="font-bold text-primary-700 hover:underline dark:text-primary-500">{{ trans('Login') }}</Link>
            </div>
        </div>
    </form>
    </div>
</template>
