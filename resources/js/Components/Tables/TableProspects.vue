<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { Prospect } from "@/types/prospect"
import { trans } from "laravel-vue-i18n"
import Multiselect from '@vueform/multiselect'
import axios from 'axios'
import { notify } from '@kyvg/vue3-notification'
import Tag from '@/Components/Tag.vue'

const props = defineProps<{
    data: object,
    tab?: string
    tagsList: []
}>()


function prospectRoute(prospect: Prospect) {
    // console.log(route().current());
    switch (route().current()) {
        case 'org.crm.shop.prospects.index':
            return route(
                'org.crm.shop.prospects.show',
                [prospect.shop.slug, prospect.slug]);
        default:
            return route(
                'org.crm.prospects.show',
                [prospect.slug]);
    }
}

const tagsListTemp = ref(props.tagsList)

// Add new Tag
const addNewTag = async (option: {label: string, value: string}) => {
    console.log(option)
    try {
        const response = await axios.post(route('org.models.tag.store'),
            { name: option.value },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )
        tagsListTemp.value.push(option.label)
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

// On update data Tags
const updateTagItemTable = async (idTag: number, idData: number) => {
    try {
        const response = await axios.post(route('org.models.prospect.tag.attach', idData),
            { tags: idTag },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )
        // console.log("response", response)
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
    <!-- {{ tagsListTemp }} -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: prospect }">
            <Link v-if="prospect.name" :href="prospectRoute(prospect)" class="special-underline">
                <span >{{ prospect['name'] }}</span>
            </Link>
            <span v-else class="italic opacity-50">{{ trans('Unknown') }}</span>
        </template>

        <!-- Multiselect -->
        <template #cell(tags)="{ item }">
            <div class="min-w-[200px]">
                <Multiselect v-model="item.tags"
                    mode="tags"
                    placeholder="Select the tag"
                    noOptionsText="Type to add tags"
                    valueProp="id"
                    trackBy="name"
                    label="name"
                    @change="(idTag) => updateTagItemTable(idTag, item.slug)"
                    :close-on-select="false"
                    :searchable="true"
                    :create-option="true"
                    :on-create="addNewTag"
                    :options="tagsList"
                >
                    <template #tag="{ option, handleTagRemove, disabled }">
                        <!-- {{ option }} -->
                        <div class="px-0.5 py-0.5">
                            <Tag :theme="option.id" :label="option.name" closeButton @click.prevent="handleTagRemove(option, $event)" />
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

.multiselect-tag-remove-icon {
    @apply text-lime-800
}
</style>