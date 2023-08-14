<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faLanguage } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faLanguage)
import { useLocaleStore } from "@/Stores/locale"
import FooterTab from '@/Components/Footer/FooterTab.vue'

const locale = useLocaleStore()

defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive'): void
}>()

</script>

<template>
    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 cursor-pointer text-gray-300"
        :class="[isTabActive == 'language' ? 'bg-gray-700' : ''] "
        @click="isTabActive == 'language' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'language')"
    >
        <FontAwesomeIcon icon="fal fa-language" class="text-xs mr-1 h-5 " />
        <div class="h-full font-extralight text-xs flex items-center leading-none">
            {{ locale.language.code }}
        </div>
        <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive === 'language'" :tabName="`language`">
            <template #default>
                <div v-for="(option, index) in locale.languageOptions" :class="[ locale.language.id == index ? 'bg-gray-400 text-gray-100' : 'text-gray-100 hover:bg-gray-500', 'grid py-1.5']"
                    @click="locale.language.id = Number(index), locale.language.name = option.label"
                >
                    {{ option.label }}
                </div>
            </template>
        </FooterTab>
    </div>
</template>