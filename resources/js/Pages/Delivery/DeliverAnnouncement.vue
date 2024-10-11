<script setup lang='ts'>
import Promo1 from '@/Components/Workshop/Announcement/Templates/Promo/Promo1.vue'
import BlankLayout from '@/Layouts/BlankLayout.vue'

const props = defineProps<{
    announcement_data: {

    }
}>()

defineOptions({ layout: BlankLayout })

</script>

<template>
    <div>
        <Promo1 :announcementData="announcement_data" />
    </div>
</template>