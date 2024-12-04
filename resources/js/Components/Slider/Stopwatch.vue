<script setup lang="ts">
import { ref, watchEffect, onMounted, watch } from "vue";
import { useTimer } from "vue-timer-hook";
import { get } from "lodash";
import { useRemoveHttps } from '@/Composables/useRemoveHttps';

// Define props
const props = defineProps<{
  data?: {
    date: string;
    textAlign?: "left" | "right";
    style?: Record<string, any>;
    linkOfText?: string;
    title?: string;
    subtitle?: string;
  };
}>();

// Reactive state
const isExpired = ref(false);
let timer = ref<ReturnType<typeof useTimer>>();

// Initialize timer when the component mounts
const initializeTimer = (date: string | undefined) => {
  if (!date) {
    console.warn("Date is undefined or invalid!");
    return;
  }
  const targetDate = new Date(date);
  const time = new Date(targetDate.getTime());
  timer.value = useTimer(time);
  isExpired.value = false;
};

// Watch for changes in `props.data.date`
watch(
  () => props.data?.date,
  (newDate) => {
    initializeTimer(newDate); // Reinitialize timer on date change
  },
  { immediate: true } // Trigger on component mount
);

// Watch the timer's expiration status
onMounted(() => {
  watchEffect(() => {
    if (timer.value?.isExpired) {
      console.warn("Timer has expired!");
      isExpired.value = true;
    }
  });
});
</script>


<template>
    <div
      class="absolute px-4 lg:px-6"
      :class="[{
        'left-0 text-left': data?.textAlign == 'left',
        'right-0 text-right': data?.textAlign == 'right',
      }]"
      :style="`text-shadow : ${
        get(data, ['style', 'textShadow']) ? '2px 2px black;' : 'none'
      }`"
    >
      <!-- Timer Section -->
      <div
        v-if="!isExpired"
        :style="{ ...data?.style }"
        :class="[data?.style?.fontSize?.fontTitle ?? 'text-[25px] lg:text-[44px]']"
        class="text-gray-100 drop-shadow-md leading-none font-bold tabular-nums"
      >
        <span>{{ timer?.days || '0' }}</span>:<span>{{ (timer?.hours || '0').toString().padStart(2, '0') }}</span>:<span>{{ (timer?.minutes || '0').toString().padStart(2, '0') }}</span>:<span>{{ (timer?.seconds || '0').toString().padStart(2, '0') }}</span>
      </div>
  
      <!-- Expired Section -->
      <component v-else :is="data?.linkOfText ? 'a' : 'div'" v-if="data?.title || data?.subtitle" :href="`https://${useRemoveHttps(data?.linkOfText)}`" target="_top">
        <div v-if="data?.title" :style="{ ...data?.style }" :class="[data?.style?.fontSize?.fontTitle ?? 'text-[25px] lg:text-[44px]']" class="text-gray-100 drop-shadow-md leading-none font-bold">{{ data?.title }}</div>
        <div v-if="data?.subtitle" :style="{...data?.style}" :class="[data?.style?.fontSize?.fontSubtitle ?? 'text-[12px] lg:text-[20px]']" class="text-gray-300 drop-shadow leading-none tracking-widest">{{ data?.subtitle }}</div>
      </component>
    </div>
  </template>
  
