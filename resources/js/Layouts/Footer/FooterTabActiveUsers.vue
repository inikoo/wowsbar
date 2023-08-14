<template>
    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-300"
        :class="[isTabActive == 'activeUsers' ? 'bg-gray-700' : '']"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
    {{ isTabActive }}
        <div class="relative text-xs flex items-center gap-x-1">
            <div class="animate-pulse ring-1 h-2 aspect-square rounded-full" :class="[activities.value.length > 0 ? 'bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">Active Users ({{ activities.value.length ?? 0 }})</span>
        </div>

        <FooterTab @pinTab="() => isTabActive = false" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
            <template #default>
                <div v-if="activities.value.length > 0" v-for="(option, index) in activities.value" class="flex justify-start py-1 px-2 gap-x-1.5 hover:bg-gray-700 cursor-default">
                    <img :src="`/media/${option.user.avatar_id}`" :alt="option.user.contact_name" srcset="" class="h-4 rounded-full shadow">
                    <p class="text-left text-gray-100">
                        <!-- <span class="font-semibold">{{ option.user.contact_name }}</span>  -->
                        <span class="font-semibold text-gray-100">{{ option.user.username }}</span> -
                        <span class="capitalize text-gray-300">{{ option.route.module }}</span>
                    </p>
                </div>
            </template>
        </FooterTab>
    </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue'
import { useLayoutStore } from "@/Stores/layout"
import { useDatabaseList } from "vuefire"
import { getDatabase, ref as dbRef } from "firebase/database"
import { initializeApp } from "firebase/app"
import { useFirebaseStore } from "@/Stores/firebase"
import FooterTab from '@/Components/Footer/FooterTab.vue';
const firebase = useFirebaseStore()
const activities = ref()

const firebaseApp = initializeApp(JSON.parse(firebase.credentials))
const db = getDatabase(firebaseApp)
const layout = useLayoutStore()

try {
    // const activitiesRef = dbRef(db, layout.tenant.code)
    activities.value = useDatabaseList(dbRef(db, layout.tenant.code))
} catch (error) {
    console.error("An error occurred while fetching data from Firebase:", error)
}

defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive'): void
}>()


</script>

<style scoped>

</style>