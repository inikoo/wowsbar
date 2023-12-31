<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 14 Dec 2023 23:09:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang='ts'>
import { Head } from '@inertiajs/vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle } from '@fas'
import axios from 'axios'
import { library } from '@fortawesome/fontawesome-svg-core'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { notify } from '@kyvg/vue3-notification'
import { router } from "@inertiajs/vue3"
import { trans } from 'laravel-vue-i18n'

library.add(faCheckCircle)

const props = defineProps<{
    dispatchedEmail: {
        is_unsubscribed: boolean
        ulid: string
        is_test: boolean
    }
    message: {
        title: string
        description: string
        caution: string
    }
}>()

const onClickUnsubscribe = async () => {
    try {
        const response = await axios.post(
            route('org.unsubscribe.mailshot.update', props.dispatchedEmail.ulid)
        )
        // console.log(response)

        router.reload(
        {
            only: ['dispatchedEmail'],  // only reload the props dispatchedEmail
            // onSuccess: () => {
            //     currentTab.value = tabSlug;
            // },
        }
    )

    } catch (error: any) {
        notify({
            title: "Failed to unsubscribe",
            text: error,
            type: "error"
        });
    }
}

// TODO create a MASSIVE button [Confirm unsubscribe]   and call by POST  route(org.unsubscribe.mailshot.update,dispatchedEmail.ulid)
// if dispatchedEmail.is_test is tru just disabke the button
</script>

<template >
    <Head title="Unsubscribe Email" />
    <!-- <pre>{{ props }}</pre> -->
    <div class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 text-gray-600 ">
            <div class="bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-violet-50 to-gray-50 relative isolate overflow-hidden px-6 py-24 shadow-xl border-2 border-violet-100 sm:rounded-2xl sm:px-24 xl:py-32">
                <template v-if="props.dispatchedEmail.is_unsubscribed">
                    <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight sm:text-4xl">
                        {{ message?.title ?? 'You have been unsubscribed' }}
                    </h2>
                    <p v-if="message?.description" class="mx-auto mt-2 max-w-2xl text-center text-lg leading-8 text-gray-500">
                        {{ message?.description }}
                    </p>
                    <p v-if="message?.caution" class="mx-auto mt-2 max-w-2xl text-center text-md leading-8 text-red-500 font-bold">
                        {{ message?.caution }}
                    </p>
                    <div class="w-full text-center">
                        <FontAwesomeIcon icon='fas fa-check-circle' class='h-10 text-lime-400' aria-hidden='true' />
                    </div>
                </template>

                <!-- Section: Confirmation -->
                <div v-else class="mx-auto w-fit flex justify-center flex-col gap-y-4">
                    <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight">
                        {{ trans('Please confirm you unsubscription') }}
                    </h2>
                    
                    <span class="mx-auto">
                        <Button @click="onClickUnsubscribe()" :style="'rainbow'" label="Unsubscribe" size="xl" />
                    </span>
                    
                    <p v-if="dispatchedEmail.is_test" class="mx-auto mt-2 max-w-2xl text-center text-sm leading-8 text-red-500">
                        {{ trans('This is a test mailshot, no action was taken and you can ignore this message.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
