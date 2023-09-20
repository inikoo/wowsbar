<script setup lang="ts">
import BlogOne from '@/Components/CMS/BlocksContent/BlogSections/BlogOne.vue'
import BlogTwo from '@/Components/CMS/BlocksContent/BlogSections/BlogTwo.vue'
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import { computed, ref } from 'vue'

const dataBlogs = {
    title: '<p><strong><span style="font-size: 36px; color: rgb(75 85 99); font-family: Inter">Brewing Knowledge: The Ultimate Coffee Article Collection.</span></strong></p>',
    subtitle: '<p><span style="font-size: 20px; color: rgb(75 85 99)">A Java Jolt for Coffee Enthusiasts – Explore, Learn, and Savor.</span></p>',
    blogsList: [
        {
            id: 1,
            title: "<p><strong><span style=\"font-size: 20px\">The Science of Coffee Flavor</span></strong></p>",
            href: '#',
            description: "Unraveling 'The Science of Coffee Flavor': Journey through the chemistry and craftsmanship that define the diverse and delightful tastes in your daily cup of joe.",
            image: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y29mZmVlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60',
            date: 'Mar 16, 2020',
            datetime: '2020-03-16',
            category: { title: 'Marketing', href: '#' },
            author: {
                name: '<p><strong><span style="font-size: 12px; font-family: Inter">John Doe</span></strong></p>',
                role: 'Coffee Enthusiast',
                href: '#',
                image:
                    'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
            },
        },
        {
            id: 2,
            title: "<p><strong><span style=\"font-size: 20px\">The Art of Coffee Roastings</span></strong></p>",
            href: '#',
            description:
                "Delve into 'The Art of Coffee Roastings' as we unveil the alchemical process behind turning green beans into aromatic, flavorful coffee treasures. Discover the magic!",
            image:
                'https://images.unsplash.com/photo-1606791405792-1004f1718d0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fGNvZmZlZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
            date: 'Mar 16, 2020',
            datetime: '2020-03-16',
            category: { title: 'Marketing', href: '#' },
            author: {
                name: '<p><strong><span style="font-size: 12px; font-family: Inter">John Smith</span></strong></p>',
                role: 'Coffee Enjoyer',
                href: '#',
                image:
                    'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTV8fHBlb3BsZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=facearea&facepad=4&w=256&h=256&q=80',
            },
        },
        {
            id: 3,
            title: "<p><strong><span style=\"font-size: 20px\">The Aromatic World of Coffee</span></strong></p>",
            href: '#',
            description:
                "Embark on a Scented Odyssey: 'The Aromatic World of Coffee' Explores the Multifaceted Fragrances That Elevate Your Coffee Experience to a Sensory Delight.",
            image:
                'https://images.unsplash.com/photo-1498804103079-a6351b050096?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGNvZmZlZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
            author: {
                name: '<p><strong><span style="font-size: 12px; font-family: Inter">Jane Doe</span></strong></p>',
                role: 'Coffee Distributor',
                href: '#',
                image:
                    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTV8fHBlb3BsZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
            },
        },
    ]
}

const pageHead = {
    title: '<p><strong><span style="font-size: 36px">From the blog</span></strong></p>',
    description: "<p><span style=\"font-size: 16px\">Learn how to grow your business with our expert advice.</span></p>"
}

const blockTheme = ref(0)
const components = {
    0: BlogOne,
    1: BlogTwo,
}

const component = computed(() => {
    return components[blockTheme.value]
})

</script>

<template>
    <div>
        <ButtonTheme v-model="blockTheme" :label="['Simple', 'Card']" />
        <component :is="component" :dataBlogs="dataBlogs" class="shadow-lg"/>
    </div>
</template>