<script setup lang='ts'>
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import BlankLayout from '@/Layouts/BlankLayout.vue'

const props = defineProps<{
    announcement_data: {

    }
}>()

defineOptions({ layout: BlankLayout })
</script>

<template>
    <div>
        <!-- <Promo1 :announcementData="announcement_data" /> -->
        <component
            :is="getAnnouncementComponent(announcement_data.code)"
            :announcementData="announcement_data"
        />

    </div>
</template>