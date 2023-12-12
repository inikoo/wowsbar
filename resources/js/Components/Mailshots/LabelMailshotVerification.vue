<script setup lang='ts'>
import { Link } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faExclamationTriangle } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExclamationTriangle)

const props = defineProps<{
    message?: string
}>()

const routeSetting = route(
    'org.crm.shop.prospects.mailshots.index',
    {
        ...route().v().params,
        '_query': {
            'tab': 'settings',
            'section': 'sender_email'  // to automatically open tab Sender Email
        }
    }
)
</script>

<template>
    <div class="px-5 pt-1.5 pb-1 w-full border-b border-yellow-500/20 bg-yellow-500/10 flex items-center gap-x-2 text-gray-500 text-sm">
        <FontAwesomeIcon icon='fal fa-exclamation-triangle' class='h-4 text-gray-500' aria-hidden='true' />
        <div class="">{{ message ?? trans('Please set your Sender Email before start sending Mailshots.')}}</div>
        👉 <Link v-if="!message" :href="routeSetting" class="underline hover:text-gray-600 cursor-pointer font-semibold">{{ trans('Go to settings') }}</Link>
    </div>
</template>
