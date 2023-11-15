<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, Ref} from 'vue'
import {Link} from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import {Prospect} from "@/types/prospect"
import {trans} from "laravel-vue-i18n"
import Multiselect from '@vueform/multiselect'
import axios from 'axios'
import {notify} from '@kyvg/vue3-notification'
import CopyButton from '@/Components/Utils/CopyButton.vue'

import Tag from '@/Components/Tag.vue'
import Icon from "@/Components/Icon.vue";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faPoo, faCommentDots, faSeedling, faCommentExclamation, faSignIn, faFileInvoice} from '@fal/'

library.add(faPoo, faCommentDots, faSeedling, faCommentExclamation, faSignIn, faFileInvoice)


interface tag {
    id: number
    slug: string
    name: string
    type: boolean
}

const props = defineProps<{
    data: object,
    tab?: string
    tagsList: tag[]
}>()


function prospectRoute(prospect: Prospect) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.index':
        case 'org.crm.shop.prospects.tags.show':
            return route(
                'org.crm.shop.prospects.show',
                [route().params['shop'],
                    prospect.slug]);
        default:
            return route(
                'org.crm.prospects.show',
                [prospect.slug]);
    }
}
console.log(props.tagsList)
const tagsListTemp: Ref<tag[]> = ref(props.tagsList)
const maxId = ref(Math.max(...tagsListTemp.value.map(item => item.id)))

// Add new Tag
const addNewTag = async (option: tag) => {
    // console.log(option)
    try {
        const response: any = await axios.post(route('org.models.prospect.tag.store'),
            {name: option.name},
            {
                headers: {"Content-Type": "multipart/form-data"},
            }
        )
        // console.log(response.data)
        maxId.value++
        tagsListTemp.value.push(response.data.data)  // (manipulation) Add new data to reactive data
        return option
    } catch (error: any) {
        notify({
            title: "Failed to add new tag",
            text: error,
            type: "error"
        })
        return false
    }
}

// On update data Tags (add tag or delete tag)
const updateTagItemTable = async (idTag: number[], idData: number) => {
    try {
        const response = await axios.post(route('org.models.prospect.tag.attach', idData),
            {tags: idTag},
            {
                headers: {"Content-Type": "multipart/form-data"},
            }
        )
    } catch (error: any) {
        notify({
            title: "Failed to update tag",
            text: error,
            type: "error"
        })
        return false
    }
}
</script>

<template>
    <!-- <pre>{{ tagsListTemp }}</pre> -->
    <Table :resource="data" :name="tab" class="mt-5">

        <template #cell(state)="{ item: banner }">
            <Icon :data="banner['state_icon']" class="px-1" />
        </template>

        <template #cell(name)="{ item: prospect }">
            <div class="flex items-center">
                <Link v-if="prospect.name" :href="prospectRoute(prospect)"
                    class="py-1"
                    :class="[`specialUnderlineOrg`]"
                >
                    <span>{{ prospect['name'] }}</span>
                </Link>
                <span v-else class="italic opacity-50">{{ trans('Unknown') }}</span>
                <CopyButton :text="prospect.name" />
            </div>
        </template>

        <template #cell(email)="{ item: prospect }">
            <div class="flex items-center gap-x-0.5">
                <span class="text-gray-500">{{ prospect.email }}</span>
                <CopyButton :text="prospect.email" />
            </div>
        </template>

        <!-- Multiselect -->
        <template #cell(tags)="{ item }">
            <div class="min-w-[200px]">
                <Multiselect v-model="item.tags"
                    mode="tags"
                    placeholder="Select the tag"
                    valueProp="name"
                    trackBy="name"
                    label="name"
                    @change="(idTag) => (updateTagItemTable(idTag, item.id))"
                    :close-on-select="false"
                    :searchable="true"
                    :create-option="true"
                    :on-create="addNewTag"
                    :caret="false"
                    :options="tagsListTemp"
                    noResultsText="No one left. Type to add new one."
                    appendNewTag
                >
                    <template #tag="{ option, handleTagRemove, disabled }: {option: tag, handleTagRemove: Function, disabled: boolean}">
                        <div class="px-0.5 py-[3px]">
                            <Tag
                                :theme="option.id ?? maxId"
                                :label="option.name"
                                :closeButton="true"
                                :stringToColor="true"
                                size="sm"
                                @onClose="(event) => handleTagRemove(option, event)"
                            />
                        </div>
                    </template>
                </Multiselect>
            </div>
        </template>
    </Table>
</template>


<style src="@vueform/multiselect/themes/default.css"></style>

<style lang="scss">
.multiselect-tags-search {
    @apply focus:outline-none focus:ring-0
}

.multiselect.is-active {
    @apply shadow-none
}

// .multiselect-tag {
//     @apply bg-gradient-to-r from-lime-300 to-lime-200 hover:bg-lime-400 ring-1 ring-lime-500 text-lime-600
// }

.multiselect-tags {
    @apply m-0.5
}

.multiselect-tag-remove-icon {
    @apply text-lime-800
}
</style>
