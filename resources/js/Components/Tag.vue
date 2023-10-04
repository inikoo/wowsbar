<script setup>
import { computed } from 'vue'

const props = defineProps({
    // color: {
    //     type: String,
    //     default: 'gray'
    // },
    size: {
        type: String,
        default: 'xs'
    },
    theme: {
        type: Number,
        default: 99
    }
})

const listTheme = {
    1: 'bg-blue-100 border border-blue-200 text-blue-500',
    2: 'bg-orange-100 border border-orange-200 text-orange-500',
    3: 'bg-green-100 border border-green-200 text-green-500',
    4: 'bg-yellow-200 border border-yellow-300 text-yellow-600',
    5: 'bg-indigo-100 border border-indigo-200 text-indigo-500',
    6: 'bg-pink-100 border border-pink-200 text-pink-500',
    7: 'bg-red-100 border border-red-200 text-red-500',
    8: 'bg-amber-100 border border-amber-200 text-amber-500',
    9: 'bg-lime-100 border border-lime-200 text-lime-500',
    10: 'bg-teal-100 border border-teal-200 text-teal-500',
    11: 'bg-purple-100 border border-purple-200 text-purple-500',
    12: 'bg-rose-100 border border-rose-200 text-rose-500',
    13: 'bg-violet-100 border border-violet-200 text-violet-500',
    14: 'bg-emerald-100 border border-emerald-200 text-emerald-500',
    15: 'bg-fuchsia-100 border border-fuchsia-200 text-fuchsia-500',
    16: 'bg-cyan-100 border border-cyan-200 text-cyan-500',
    17: 'bg-sky-100 border border-sky-200 text-sky-500',
    99: 'bg-slate-100 border border-slate-200 text-slate-500',
}

const compTheme = computed(() => {
    return listTheme[props.theme]
})
</script>

<template>
    <div class="inline-block rounded px-2 py-1 w-fit font-medium border" :class="[`text-${size}`, compTheme]">
        <slot />
    </div>
</template>
