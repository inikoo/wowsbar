export const footerTheme1 = {
    code: "FooterTheme1",
    name: "Footer 1",
    data: {
        footer: {
            column: {
                column_1: {
                    name: "column 1",
                    key: "1",
                    data: [
                        {
                            name: "Help",
                            id: 1,
                            data: [
                                { name: "Contact Us", id: 2 },
                                { name: "Delivery", id: 3 },
                                { name: "Returns Policy", id: 4 }
                            ]
                        },
                        {
                            name: "About AW",
                            id: 5,
                            data: [
                                { name: "AW Beginning", id: 6 },
                                { name: "Business Ethics", id: 7 },
                                { name: "Our Brands", id: 8 },
                                { name: "Subscribe to the Newsletter", id: 9 }
                            ]
                        },
                        {
                            name: "Showroom",
                            id: 10,
                            data: [
                                { name: "Book Showroom Appointment", id: 11 }
                            ]
                        },
                        {
                            name: "Customer Service",
                            id: 12,
                            data: [
                                { name: "+44 (0) 1142 729 165", id: 13 },
                                { name: "care@ancientwisdom.biz", id: 14 }
                            ]
                        },
                        { name: "Reviews", id: 15, data: [] },
                        { name: "FAQ", id: 16, data: [] }
                    ]
                },
                column_2: {
                    name: "column 2",
                    key: "2",
                    data: [
                        {
                            name: "Why Choose AW?",
                            id: 17,
                            data: [
                                { name: "No Minimum Order", id: 18 },
                                { name: "First Order Bonus", id: 19 },
                                { name: "Buy Now, Pay Later", id: 20 },
                                { name: "Volume Discounts", id: 21 }
                            ]
                        },
                        {
                            name: "Membership",
                            id: 22,
                            data: [
                                { name: "Join The Gold Reward Membership", id: 23 }
                            ]
                        },
                        {
                            name: "Discover",
                            id: 24,
                            data: [
                                { name: "Catalogue - Live Stock Feeds", id: 25 },
                                { name: "AW History & The Phoenix Effect", id: 26 },
                                { name: "Distribution Centre in Europe", id: 27 },
                                { name: "David's Travel Blog", id: 28 }
                            ]
                        },
                        {
                            name: "Legal",
                            id: 29,
                            data: [
                                { name: "Terms & Conditions", id: 30 },
                                { name: "Privacy Policy", id: 31 },
                                { name: "Cookies Policy", id: 32 }
                            ]
                        }
                    ]
                },
                column_3: {
                    name: "column 3",
                    key: "3",
                    data: [
                        {
                            name: "Our Services",
                            id: 33,
                            data: [
                                { name: "DROPSHIPPING", id: 34 },
                                { name: "FULFILMENT", id: 35 },
                                { name: "DIGITAL MARKETING", id: 36 }
                            ]
                        },
                        {
                            name: "AW Partners",
                            id: 37,
                            data: [
                                { name: "Agnes + Cat", id: 38 },
                                { name: "Agnes + Cat", id: 39 },
                                { name: "AW - Aromatics", id: 40 },
                                { name: "AW - Portugal", id: 41 },
                                { name: "AW - Germany", id: 42 },
                                { name: "AW - Slovakia", id: 43 },
                                { name: "AW - España", id: 44 },
                                { name: "Agnes + France", id: 45 },
                                { name: "Agnes + Poland", id: 46 },
                                { name: "Agnes + Austria", id: 47 },
                                { name: "Agnes + Europe", id: 48 },
                                { name: "Agnes + Romania", id: 49 },
                                { name: "Agnes + Czechia", id: 50 },
                                { name: "Agnes + Italy", id: 51 }
                            ]
                        }
                    ]
                },
                column_4: {
                    name: "column 4",
                    key: "4",
                    data: {
                        textBox1: "Ancient Wisdom Marketing Ltd. Affinity Park, Europa Drive Sheffield, S9 1XT",
                        textBox2: "<p>Vat No: GB764298589  </br> Reg. No: 04108870</p>",
                        textBox3: "Subscribe to the WhatsApp messages and benefit from exclusive discounts."
                    }
                }
            },
            usePayment: true,
            useSocial: true,
            copyRight: "Copyright © 2024 Aurora. All rights reserved. Terms of Use Privacy Policy",
            PaymentData: {
                data: [
                    {
                        name: "Checkout.com",
                        value: "checkout.com",
                        image: "https://www.linqto.com/wp-content/uploads/2023/04/logo_2021-11-05_19-04-11.530.png"
                    }
                ]
            },
            socialData: [
                { label: "Facebook", icon: ["fab", "facebook-f"], link: "" },
                { label: "Instagram", icon: "fab fa-instagram", link: "" },
                { label: "Tik Tok", icon: "fab fa-tiktok", link: "" },
                { label: "Pinterest", icon: "fab fa-pinterest", link: "" },
                { label: "Youtube", icon: "fab fa-youtube", link: "" },
                { label: "Linkedin", icon: "fab fa-linkedin-in", link: "" }
            ]
        },
        bluprint: [
            {
                name: "Column",
                key: "column",
                type: "column",
                icon: "far fa-line-columns",
                bluprint: [
                    {
                        name: "Column",
                        key: "column",
                        type: "footerColumn"
                    }
                ]
            },
            {
                name: "Payments",
                key: "PaymentData",
                type: "payment_templates",
                icon: "far fa-money-bill",
                bluprint: [
                    {
                        name: "Payments",
                        key: "PaymentData",
                        type: "payment_templates"
                    }
                ]
            },
            {
                name: "Social Media",
                key: "socialData",
                type: "socialMedia",
                icon: "far fa-icons",
                bluprint: [
                    {
                        name: "Social Media",
                        key: "socialData",
                        type: "socialMedia"
                    }
                ]
            }
        ]
    }
};
