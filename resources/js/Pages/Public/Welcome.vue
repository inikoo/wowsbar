<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Hero as heroType } from "@/types/hero";
import SideImage from "./PageComponents/SideImage.vue";
import { ref } from 'vue'
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
} from '@headlessui/vue'
import { MinusSmallIcon, PlusSmallIcon } from '@heroicons/vue/24/outline'
import { CheckIcon } from '@heroicons/vue/20/solid'

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

const data = [
    {
        dataRow: [
            {
                type: "banner",
                img: {
                    src: "https://tailwindui.com/img/ecommerce-images/home-page-01-hero-full-width.jpg",
                    alt: "image",
                },
                title: "WOWSBAR",
                subtitle:
                    "The new arrivals have, well, newly arrived. Check out the latest options from our summer small-batch release while they re still in stock.",
                button: {
                    text: "Read More",
                    link: "",
                },
            },
        ],
    },
    {

        dataRow: [
            {
                type: "colllection",
                title: "Shop by Collection",
                subtitle:
                    "Each season, we collaborate with world-class designers to create acollection inspired by the natural world.",
                collections: [
                    {
                        name: "Handcrafted Collection",
                        href: "#",
                        imageSrc:
                            "https://tailwindui.com/img/ecommerce-images/home-page-01-collection-01.jpg",
                        imageAlt:
                            "Brown leather key ring with brass metal loops and rivets on wood table.",
                        description:
                            "Keep your phone, keys, and wallet together, so you can lose everything at once.",
                    },
                    {
                        name: "Organized Desk Collection",
                        href: "#",
                        imageSrc:
                            "https://tailwindui.com/img/ecommerce-images/home-page-01-collection-02.jpg",
                        imageAlt:
                            "Natural leather mouse pad on white desk next to porcelain mug and keyboard.",
                        description:
                            "The rest of the house will still be a mess, but your desk will look great.",
                    },
                    {
                        name: "Focus Collection",
                        href: "#",
                        imageSrc:
                            "https://tailwindui.com/img/ecommerce-images/home-page-01-collection-03.jpg",
                        imageAlt:
                            "Person placing task list card into walnut card holder next to felt carrying case on leather desk pad.",
                        description:
                            "Be more productive than enterprise project managers with a single piece of paper.",
                    },
                ],
            },
        ],
    },
    {
        dataRow: [
            {
                type: "image",
                src: "https://tailwindui.com/img/ecommerce-images/product-page-03-product-01.jpg",
                alt: "test",
            },
            {
                type: "text",
                title: "title",
                subtitle: "subtitle",
                description:
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            },
            {
                type: "accordion",
                title: "title",
                subtitle: "subtitle",
                details: [
                    {
                        name: "SEO",
                        items: [
                            "As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks",
                        ],
                    },
                    {
                        name: "PPC",
                        items: [
                            "As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks",
                        ],
                    },
                    {
                        name: "SSM",
                        items: [
                            "As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks",
                        ],
                    },
                ],
            },
        ],
    },
    {
        dataRow: [
            {
                type: "image",
                src: "https://tailwindui.com/img/ecommerce-images/product-page-03-product-01.jpg",
                alt: "test",
            },
            {
                type: "text",
                title: "title",
                subtitle: "subtitle",
                description:
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            },
        ]
    },
    {
        dataRow: [
            {
                type: 'pricing',
                frequencies: [
                    { value: 'monthly', label: 'Monthly', priceSuffix: '/month' },
                    { value: 'annually', label: 'Annually', priceSuffix: '/year' },
                ],
                tiers: [
                    {
                        name: 'Hobby',
                        id: 'tier-hobby',
                        href: '#',
                        price: { monthly: '$15', annually: '$144' },
                        description: 'The essentials to provide your best work for clients.',
                        features: ['5 products', 'Up to 1,000 subscribers', 'Basic analytics'],
                        mostPopular: false,
                    },
                    {
                        name: 'Freelancer',
                        id: 'tier-freelancer',
                        href: '#',
                        price: { monthly: '$30', annually: '$288' },
                        description: 'The essentials to provide your best work for clients.',
                        features: ['5 products', 'Up to 1,000 subscribers', 'Basic analytics', '48-hour support response time'],
                        mostPopular: false,
                    },
                    {
                        name: 'Startup',
                        id: 'tier-startup',
                        href: '#',
                        price: { monthly: '$60', annually: '$576' },
                        description: 'A plan that scales with your rapidly growing business.',
                        features: [
                            '25 products',
                            'Up to 10,000 subscribers',
                            'Advanced analytics',
                            '24-hour support response time',
                            'Marketing automations',
                        ],
                        mostPopular: true,
                    },
                    {
                        name: 'Enterprise',
                        id: 'tier-enterprise',
                        href: '#',
                        price: { monthly: '$90', annually: '$864' },
                        description: 'Dedicated support and infrastructure for your company.',
                        features: [
                            'Unlimited products',
                            'Unlimited subscribers',
                            'Advanced analytics',
                            '1-hour, dedicated support response time',
                            'Marketing automations',
                            'Custom reporting tools',
                        ],
                        mostPopular: false,
                    },
                ],

            }
        ]
    },
    {
        dataRow: [
            {
                type: "footer",
                collumn : [
                   {
                    type : 'description',
                    title : 'Description',
                    text : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                   },
                   {
                    type : 'link',
                    title : 'Link',
                    data : [
                        {label : 'test', link:'', icon : ''},
                        {label : 'test1', link:'', icon : ''},
                        {label : 'test2', link:'',  icon : ''},
                        {label : 'test3', link:'',  icon : ''},
                        {label : 'test4', link:'',  icon : ''},
                    ]
                   },
                   {
                    type : 'link',
                    title : 'Link',
                    data : [
                        {label : 'test', link:'', icon : ''},
                        {label : 'test1', link:'', icon : ''},
                        {label : 'test2', link:'',  icon : ''},
                        {label : 'test3', link:'',  icon : ''},
                        {label : 'test4', link:'',  icon : ''},
                    ]
                   }
                ]
            },
        ],
    },

];





const props = defineProps<{
    hero: heroType
}>()
</script>

<template layout="Public">
    <!-- <Head title="Homepage"/> -->
    <!-- <Hero :hero="hero"></Hero> -->

    <div class="bg-white">
        <div v-for="(item, index) of data" :key="index">
            <SideImage :data="item" />
        </div>      
    </div>
</template>
