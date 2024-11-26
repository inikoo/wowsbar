<script setup lang='ts'>
import { inject, nextTick, onMounted, ref, toRaw } from 'vue'
import Select from 'primevue/select'
import InputText from 'primevue/inputtext'
import InputGroup from 'primevue/inputgroup'
import InputGroupAddon from 'primevue/inputgroupaddon'
import Tag from '@/Components/Tag.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { cloneDeep, get, remove, set } from 'lodash'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useCopyText } from '@/Composables/useCopyText'
import { usePage } from '@inertiajs/vue3'
import { faLink } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { trans } from 'laravel-vue-i18n'
library.add(faLink)

const props = defineProps<{
    domain: string
}>()

const emits = defineEmits<{
    (e: 'onMounted'): void
}>()

const announcementData = inject('announcementData', {})
const announcementDataSettings = announcementData.settings
// console.log('kkkk', announcementData.settings)

// Section: target_pages
const specificNew = ref({
    will: 'show', // 'hide'
    when: 'contain', // 'matches'
    url: ''
})
const addSpecificPage = async () => {
    const newTargetPage = cloneDeep(specificNew.value)
    
    if (Array.isArray(announcementDataSettings?.target_pages?.specific)) {
        announcementDataSettings.target_pages.specific.push(newTargetPage)
    } else {
        set(announcementDataSettings, ['target_pages', 'specific'], [newTargetPage])
    }

    console.log('opopop', get(announcementDataSettings, ['target_pages', 'specific']))
    
    await nextTick()
    specificNew.value.url = ''
}
const onDeleteSpecific = (specIndex: number) => {
    remove(announcementDataSettings.target_pages.specific, (item, index) => {
        return index == specIndex
    })

}

onMounted(() => {
    // Set default value target_pages
    if (!announcementDataSettings.target_pages) {
        set(announcementDataSettings, 'target_pages', {
            type: 'all', // 'specific'
            specific: []
        })
    }

    // Set default value target_users
    if(!announcementDataSettings.target_users) {
        set(announcementDataSettings, 'target_users', {
            auth_state: 'all', // 'login' || 'logout'
        })
    }

    emits('onMounted')
})

const announcementScheduled = ref({
    type: 'now', // 'scheduled'
    scheduled: {
        start_date: '',
        finish_date: '',
    }
})

// const getAnnouncementScriptUrl = () => {
//     let xxx
//     if (usePage().props.environment === 'local') {
//         xxx = `delivery.wowsbar.test`
//     } else if (usePage().props.environment === 'staging') {
//         xxx = `https://delivery-staging.wowsbar.com`
//     } else if (usePage().props.environment === 'production') {
//         xxx = `https://delivery.wowsbar.com`
//     }

//     return window.location.origin + `/announcement.min.js?ulid=${announcementData.ulid}&delivery=${xxx}`
// }

const settingsUser = ref({
    authState: 'all', // 'logout' || 'all'
})

</script>

<template>
    <!-- <div>
        <div class="text-lg">{{ trans("Put this script in your website and you will be fine") }}😀</div>
        <div class="bg-white border border-gray-300 flex items-center justify-between gap-x-3 rounded-lg md:w-fit max-w-full mb-6">
            <a :href="getAnnouncementScriptUrl()" target="_blank" class="pl-4 md:pl-5 inline-block py-2 text-base text-gray-400 truncate text-ellipsis hover:text-gray-600">{{ getAnnouncementScriptUrl() }}</a>
            <Button :style="'secondary'" class="" size="xl" @click="useCopyText(getAnnouncementScriptUrl())" title="Copy url to clipboard">
                <FontAwesomeIcon icon='fal fa-link' class='text-gray-500' aria-hidden='true' />
            </Button>
        </div>
    </div> -->

    <!-- Section: Page -->
    <fieldset class="mb-6 bg-white px-7 pt-4 pb-7 border border-gray-200 rounded-xl">
        <div class="text-xl font-semibold">{{ trans("Page") }}</div>
        <p class="text-sm/6 text-gray-600">
            {{ trans("Select where the Announcement will be displayed") }}
        </p>
        <div class="mt-2">
            <div class="flex items-center gap-x-3">
                <input
                    value="all"
                    @input="(val: string) => set(announcementDataSettings, 'target_pages.type', val.target.value)"
                    :checked="announcementDataSettings?.target_pages?.type ==  'all'"
                    id="pages-all"
                    name="input-target"
                    type="radio"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="pages-all"
                    class="cursor-pointer block font-medium ">{{ trans("All pages") }}</label>
            </div>
            <div class="flex items-center gap-x-3">
                <input
                    value="specific"
                    @input="(val: string) => set(announcementDataSettings, 'target_pages.type', val.target.value)"
                    :checked="announcementDataSettings?.target_pages?.type ==  'specific'"
                    id="pages-specific"
                    name="input-target"
                    type="radio"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="pages-specific" class="cursor-pointer block font-medium ">
                    {{ trans("Specific page") }}
                </label>

            </div>

            <!-- Section: Target specific -->
            <div v-if="announcementDataSettings?.target_pages?.type == 'specific'" class="mt-2 space-y-4">
                <div class="flex gap-x-4 items-center">
                    <div>The announcement should</div>
                    <div class="w-24">
                        <Select v-model="specificNew.will" :options="['show', 'hide']" placeholder="Select a City" class="w-full" />
                    </div>
                    <div>if URL:</div>
                </div>

                <div>
                    <div class="flex gap-x-2">
                        <div class="w-32">
                            <Select
                                v-model="specificNew.when"
                                :options="[
                                    'contain',
                                    // 'matches'
                                ]"
                                placeholder="When?"
                                class="w-full"
                            />
                        </div>
                        <div class="min-w-80 w-fit max-w-96">
                            <InputGroup v-show="specificNew.when === 'matches'">
                                <InputGroupAddon>{{ domain }}/</InputGroupAddon>
                                <InputText @keydown.enter="() => specificNew.url ? addSpecificPage() : ''" type="text" v-model="specificNew.url" placeholder="blog/subpage" />
                            </InputGroup>
                            <InputText
                                v-show="specificNew.when === 'contain'"
                                @keydown.enter="() => specificNew.url ? addSpecificPage() : ''"
                                v-model="specificNew.url"
                                fluid
                                type="text"
                                placeholder="blog/subpage"
                                class="placeholder:text-gray-200"
                            />
                        </div>
                        <Button @click="() => addSpecificPage()" :style="'secondary'" label="Add" :disabled="!specificNew.url" />
                    </div>

                    <div v-show="specificNew.when === 'matches'" class="text-xs italic mt-2 text-gray-500">
                        <FontAwesomeIcon icon='fal fa-info-circle' class='text-sm' fixed-width aria-hidden='true' />
                        Put '*' to select the subpages as well (example: blog/subpage/* will affect blog/subpage/aromatheraphy/diffuser/lavender)
                    </div>
                </div>

                <!-- Section: Show URL list -->
                <div v-if="announcementDataSettings?.target_pages?.specific?.filter(item => item.will === 'show').length">
                    <div>Show ({{ announcementDataSettings.target_pages.specific.filter(item => item.will === 'show').length }}):</div>
                    <TransitionGroup name="list" tag="ul" class="bg-slate-200 px-2 py-2 rounded">
                        <template v-for="(spec, specIndex) in announcementDataSettings.target_pages.specific" :key="`${spec.will}${spec.when}${spec.url}`">
                            <li v-if="spec.will === 'show'" class="list-disc list-inside">
                                <template v-if="spec.when === 'contain'">If <span class="italic">contain</span> <span class="font-bold">{{ spec.url }}</span> in the <Tag label="URL" /></template>
                                <template v-if="spec.when === 'matches'">If <Tag label="URL" /><span class="italic">matches</span> in <span class="font-bold">{{ spec.url }}</span> </template>
                                <div @click="() => onDeleteSpecific(specIndex)" class="px-1 py-px inline cursor-pointer text-red-300 hover:text-red-500">
                                    <FontAwesomeIcon icon='fal fa-times' class='' fixed-width aria-hidden='true' />
                                </div>
                            </li>
                        </template>
                    </TransitionGroup>
                </div>

                <!-- Section: Hide URL list -->
                <div v-if="announcementDataSettings?.target_pages?.specific?.filter(item => item.will === 'hide').length">
                    <div>Hide ({{ announcementDataSettings.target_pages.specific.filter(item => item.will === 'hide').length }}):</div>
                    <TransitionGroup name="list" tag="ul" class="bg-slate-200 px-2 py-2 rounded">
                        <template v-for="(spec, specIndex) in announcementDataSettings.target_pages.specific" :key="`${spec.will}${spec.when}${spec.url}`">
                            <li v-if="spec.will === 'hide'" class="list-disc list-inside">
                                <template v-if="spec.when === 'contain'"><span class="italic">contain</span> <span class="font-bold">{{ spec.url }}</span> in the <Tag label="URL" /></template>
                                <template v-if="spec.when === 'matches'"><Tag label="URL" /><span class="italic">matches</span> in <span class="font-bold">{{ spec.url }}</span> </template>
                                <div @click="() => onDeleteSpecific(specIndex)" class="px-1 py-px inline cursor-pointer text-red-300 hover:text-red-500">
                                    <FontAwesomeIcon icon='fal fa-times' class='' fixed-width aria-hidden='true' />
                                </div>
                            </li>
                        </template>
                    </TransitionGroup>
                </div>

            </div>
        </div>
    </fieldset>

    <!-- Section: target_users -->
    <fieldset class="mb-6 bg-white px-7 pt-4 pb-7 border border-gray-200 rounded-xl">
        <div class="text-xl font-semibold">User</div>
        <p class="text-sm/6 text-gray-600">
            {{ trans("Select who will receive the Announcement") }}
        </p>
        <div class="mt-2">
            <div class="flex items-center gap-x-3">
                <input
                    value="all"
                    @input="(val: string) => set(announcementDataSettings, 'target_users.auth_state', val.target.value)"
                    :checked="settingsUser.authState === 'all'"
                    id="auth-both"
                    name="input-auth-state"
                    type="radio"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="auth-both" class="cursor-pointer block font-medium ">
                    {{ trans("Both") }}
                </label>
            </div>
            
            <div class="flex items-center gap-x-3">
                <input
                    value="login"
                    @input="(val: string) => set(announcementDataSettings, 'target_users.auth_state', val.target.value)"
                    :checked="settingsUser.authState === 'login'"
                    id="auth-login"
                    name="input-auth-state"
                    type="radio"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="auth-login" class="cursor-pointer block font-medium ">
                    {{ trans("Visitor logged in") }}
                </label>
            </div>

            <div class="flex items-center gap-x-3">
                <input
                    value="logout"
                    @input="(val: string) => set(announcementDataSettings, 'target_users.auth_state', val.target.value)"
                    :checked="settingsUser.authState === 'logout'"
                    id="auth-logout"
                    name="input-auth-state"
                    type="radio"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label for="auth-logout" class="cursor-pointer block font-medium ">
                    {{ trans("Visitor logged out") }}
                </label>

            </div>
        </div>
    </fieldset>
</template>

<style lang="css" scoped>
:deep(.p-inputtext::placeholder) {
    color: #9ca3af;
}
</style>