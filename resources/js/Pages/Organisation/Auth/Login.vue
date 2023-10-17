<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import Checkbox from '@/Components/Checkbox.vue'
import ValidationErrors from '@/Components/ValidationErrors.vue'
import { trans } from 'laravel-vue-i18n'
import PureInput from '@/Components/Pure/PureInput.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@fad/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faSpinnerThird)

const form = useForm({
    username: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('org.login'),
        {
        onFinish: () => form.reset('password'),
        });
}

</script>

<template layout="OrgAppGuest">

    <Head title="Login"/>
    <form class="space-y-6" @submit.prevent="submit" >
        <div>
            <label for="login" class="block text-sm font-medium text-gray-600">{{ trans('Username') }}</label>
            <div class="mt-1">
                <PureInput v-model="form.username" required autofocus inputName="username" placeholder="Enter username" />
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-600"> {{ trans('Password') }} </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <PureInput v-model="form.password" type="password" inputName="password" placeholder="Enter password" />
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <Checkbox name="remember-me" id="remember-me" v-model:checked="form.remember"/>
                <label for="remember-me" class="cursor-pointer ml-2 block text-sm text-gray-600 select-none"> {{ trans('Remember me') }} </label>
            </div>
        </div>

        <div>
            <button type="submit" id="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                {{ trans('Login') }}
                <FontAwesomeIcon icon="fad fa-spinner-third" class="ml-2 h-5 w-5 animate-spin dark:text-gray-200 opacity-0" :class="{'opacity-100': form.processing}"/>
            </button>
        </div>


    </form>

    <ValidationErrors/>

</template>
