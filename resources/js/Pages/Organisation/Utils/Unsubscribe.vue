<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 14 Dec 2023 23:09:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang='ts'>
import { Head } from '@inertiajs/vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle } from '@fas'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheckCircle)

const props = defineProps<{
    dispatchedEmail: object
    message: {
        title: string
        description: string
        caution: string
    }
}>()
// TODO create a MASSIVE button [Confirm unsubscribe]   and call by POST  route(org.unsubscribe.mailshot.update,dispatchedEmail.ulid)
// if dispatchedEmail.is_test is tru just disabke the button
</script>

<template >
    <Head title="Unsubscribe Emails"/>
    <div class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 text-gray-600 ">
            <div class="bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-violet-50 to-gray-50 relative isolate overflow-hidden px-6 py-24 shadow-xl border-2 border-violet-100 sm:rounded-2xl sm:px-24 xl:py-32">
                <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight sm:text-4xl">
                    {{ message?.title ?? 'You have been unsubscribed' }}
                </h2>
                <p class="mx-auto mt-2 max-w-2xl text-center text-lg leading-8 text-gray-500">
                    {{ message?.description }}
                </p>
                <p class="mx-auto mt-2 max-w-2xl text-center text-md leading-8 text-red-500 font-bold">
                    {{ message?.caution }}
                </p>
                <div class="w-full text-center">
                    <FontAwesomeIcon icon='fas fa-check-circle' class='h-10 text-lime-400' aria-hidden='true' />
                </div>
            </div>
        </div>
    </div>
</template>
