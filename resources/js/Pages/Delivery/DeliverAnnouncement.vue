<script setup lang='ts'>
import AnnouncementPromo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import BlankLayout from '@/Layouts/BlankLayout.vue'
import { nextTick, onMounted, provide, ref } from 'vue'

const props = defineProps<{
    announcement_data: {

    }
}>()

defineOptions({ layout: BlankLayout })


onMounted(async () => {
    const height = document.getElementById('announcement_delivery_component')?.clientHeight || 0; // or other method to get the height
    const dataSendToIframe = {
        height: height,
        y: props.announcement_data?.container_properties?.position?.y 
    }
    console.log('send to Iframe', dataSendToIframe)
    window.parent.postMessage(dataSendToIframe, '*'); // Send height to parent
})

provide('isOnPublishState', true)

const withIframe = new URL(window.location.href).searchParams.get('iframe')

console.log('dsadsa', withIframe)
</script>

<template>
    <div v-if="withIframe"
        :style="propertiesToHTMLStyle(announcement_data.container_properties, { toRemove: styleToRemove})"
    >
        <!-- <Promo1 :announcementData="announcement_data" /> -->
        <!-- <pre>{{announcement_data.container_properties.position.y}}</pre> -->
        <component
            id="announcement_delivery_component"
            :is="getAnnouncementComponent(announcement_data.template_code)"
            :announcementData="announcement_data"
        />
    </div>

    <component
        v-else
        id="announcement_delivery_component"
        :is="getAnnouncementComponent(announcement_data.template_code)"
        :announcementData="announcement_data"
    />
    
<!-- <br>
    <div class="block">
        <AnnouncementPromo1 />
    </div> -->
</template>