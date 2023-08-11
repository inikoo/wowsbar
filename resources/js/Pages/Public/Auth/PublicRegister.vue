<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'

const form = useForm({
    contact_name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('public.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template layout="LandlordAuthActions">
    <Head title="Register" />

    <form @submit.prevent="submit" class="space-y-6">
        <!-- Field: Name -->
        <div>
            <InputLabel for="name" value="Name" />
            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.contact_name" required autofocus
                autocomplete="name" />

            <InputError class="mt-2" :message="form.errors.contact_name" />
        </div>

        <!-- Field: Email -->
        <div class="">
            <InputLabel for="email" value="Email" />
            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                autocomplete="username" />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- Field: Password -->
        <div class="">
            <InputLabel for="password" value="Password" />
            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <!-- Field: Confirm Password -->
        <div class="">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                v-model="form.password_confirmation" required autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="">
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ ('Register') }}
            </button>
        </div>

        <div class="">
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ trans('Already registered?') }}
                <Link :href="route('public.login')"
                    class="font-bold text-primary-700 hover:underline dark:text-primary-500">{{ trans('Login') }}</Link>
            </p>
        </div>
    </form>
</template>
