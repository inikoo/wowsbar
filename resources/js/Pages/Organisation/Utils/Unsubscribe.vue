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
        successTitle: string
        successDescription: string
        button: string
        confirmationTitle?: string
    }
}>()

const onClickUnsubscribe = async () => {
    try {
        await axios.post(
            route('org.unsubscribe.mailshot.update', props.dispatchedEmail.ulid)
        )

        router.reload({
            only: ['dispatchedEmail'],  // only reload the props dispatchedEmail
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


</script>

<template >
    <Head title="Unsubscribe Email" />
    <!-- <pre>{{ props }}</pre> -->
    <div class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 text-gray-600 ">
            <div class="bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-violet-50 to-gray-50 relative isolate overflow-hidden px-6 py-24 shadow-xl border-2 border-violet-100 sm:rounded-2xl sm:px-24 xl:py-32">
                <template v-if="props.dispatchedEmail.is_unsubscribed">
                    <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight sm:text-4xl">
                        {{ message.successTitle }}
                    </h2>

                    <p class="mx-auto mt-2 max-w-2xl text-center text-lg leading-8 text-gray-500">
                        {{ message.successDescription }}
                    </p>

                    <div class="w-full text-center">
                        <FontAwesomeIcon icon='fas fa-check-circle' class='h-10 text-lime-400' aria-hidden='true' />
                    </div>
                </template>

                <!-- Section: Confirmation -->
                <div v-else class="mx-auto w-fit flex justify-center flex-col gap-y-8">
                    <h2 class="mx-auto max-w-2xl text-center text-3xl font-semibold tracking-tight">
                        {{ message.confirmationTitle }}
                    </h2>

                    <span class="mx-auto">
                        <Button @click="onClickUnsubscribe()" :style="'rainbow'" :label="message.button" size="" class="rounded-xl px-10 py-8 text-2xl" />
                    </span>
                </div>

                <p v-if="dispatchedEmail.is_test" class="mx-auto mt-2 max-w-2xl text-center text-sm leading-8 text-red-500">
                    {{ trans('This is a test mailshot, no action was taken and you can ignore this message.') }}
                </p>

            </div>
        </div>
    </div>
</template>
