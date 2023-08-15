<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Hero from '@/Components/Marketing/Hero.vue';
import { Hero as heroType } from "@/types/hero";
import { ref } from 'vue'
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue'
import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'
import SideImage from './PageComponents/SideImage.vue'






const footerNavigation = {
    shop: [
        { name: 'Bags', href: '#' },
        { name: 'Tees', href: '#' },
        { name: 'Objects', href: '#' },
        { name: 'Home Goods', href: '#' },
        { name: 'Accessories', href: '#' },
    ],
    company: [
        { name: 'Who we are', href: '#' },
        { name: 'Sustainability', href: '#' },
        { name: 'Press', href: '#' },
        { name: 'Careers', href: '#' },
        { name: 'Terms & Conditions', href: '#' },
        { name: 'Privacy', href: '#' },
    ],
    account: [
        { name: 'Manage Account', href: '#' },
        { name: 'Returns & Exchanges', href: '#' },
        { name: 'Redeem a Gift Card', href: '#' },
    ],
    connect: [
        { name: 'Contact Us', href: '#' },
        { name: 'Twitter', href: '#' },
        { name: 'Instagram', href: '#' },
        { name: 'Pinterest', href: '#' },
    ],
}


const Banner = [
    {
        type: 'banner',
        img: {
            src: "https://tailwindui.com/img/ecommerce-images/home-page-01-hero-full-width.jpg",
            alt: 'image'
        },
        title: 'WOWSBAR',
        subtitle: 'The new arrivals have, well, newly arrived. Check out the latest options from our summer small-batch release while they re still in stock.',
        button: {
            text: 'Read More',
            link: ''
        }
    },
]

const Collection = [
    {
        type: 'colllection',
        title: 'Shop by Collection',
        subtitle: 'Each season, we collaborate with world-class designers to create acollection inspired by the natural world.',
        collections: [
            {
                name: 'Handcrafted Collection',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-01.jpg',
                imageAlt: 'Brown leather key ring with brass metal loops and rivets on wood table.',
                description: 'Keep your phone, keys, and wallet together, so you can lose everything at once.',
            },
            {
                name: 'Organized Desk Collection',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-02.jpg',
                imageAlt: 'Natural leather mouse pad on white desk next to porcelain mug and keyboard.',
                description: 'The rest of the house will still be a mess, but your desk will look great.',
            },
            {
                name: 'Focus Collection',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-03.jpg',
                imageAlt: 'Person placing task list card into walnut card holder next to felt carrying case on leather desk pad.',
                description: 'Be more productive than enterprise project managers with a single piece of paper.',
            },
        ]
    },
]

const setRowData = [
    {
        type: 'image',
        src: 'https://tailwindui.com/img/ecommerce-images/product-page-03-product-01.jpg',
        alt: 'test'
    },
    {
        type: 'text',
        title: 'title',
        subtitle: 'subtitle',
        description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
    },
    {
        type: 'accordion',
        title: 'title',
        subtitle: 'subtitle',
        details: [
            {
                name: 'SEO',
                items: [
                    'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
                ],
            },
            {
                name: 'PPC',
                items: [
                    'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
                ],
            },
            {
                name: 'SSM',
                items: [
                    'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
                ],
            },
        ],
    }
]


const props = defineProps<{
    hero: heroType
}>()
</script>

<template layout="Public">
    <!-- <Head title="Homepage"/> -->
    <!-- <Hero :hero="hero"></Hero> -->

    <div class="bg-white">
        <SideImage :data="Banner" />
        <SideImage :data="setRowData.filter((item) => item.type != 'text')" />
        <SideImage :data="Banner" />
        <SideImage :data="Collection" />
        <SideImage :data="setRowData" />

        <footer aria-labelledby="footer-heading" class="bg-gray-100 grid grid-cols-3 items-start pb-10 px-10 gap-x-16 text-gray-700">
            <div class="flex flex-col justify-center gap-y-6">
                <h3 class="text-3xl font-semibold text-center text-green-600">Company</h3>
                <div class="text-lg">
                    <p>AW Advantage</p>
                    <p>Trading brand of: Ancient Wisdom s.r.o.,</p>
                    <p>Zavarska 10/G, 917 01 Trnava, Slovakia, EU</p>
                    <p>VAT: SK2120525440 Company</p>
                    <p>Reg: 50920600</p>
                </div>
            </div>
            <div class="flex flex-col justify-center gap-y-6">
                <h3 class="text-3xl font-semibold text-center text-green-600">Offices</h3>
                <div class="">
                    <p class="font-semibold text-xl">Europe</p>
                    <span>Ancient Wisdom s.r.o.,<br>CTPark Trnava, Prilohy 57, Zavar, Slovakia, EU</span>
                </div>
            </div>
            <div class="flex flex-col justify-center gap-y-6">
                <h3 class="text-3xl font-semibold text-center text-green-600">Contact</h3>
                <div class="text-lg">
                    <p>AW Advantage</p>
                    <p>Trading brand of: Ancient Wisdom s.r.o.,</p>
                    <p>Zavarska 10/G, 917 01 Trnava, Slovakia, EU</p>
                    <p>VAT: SK2120525440 Company</p>
                    <p>Reg: 50920600</p>
                </div>
            </div>
        </footer>
    </div>
</template>



