<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { loadCss } from "@/Composables/loadCss";
import Notification from '@/Components/Utils/Notification.vue'
import * as Klaro from 'klaro'

const header = usePage().props.structure.header;
const footer = usePage().props.structure.footer;
const user = usePage().props.auth.user;
let dynamicClasses = {
    header: "",
    footer: "",
};

const configKlaro = {
    version: 1,
    elementID: 'klaro',
    styling: {
        theme: ['light', 'bottom', 'left'],
    },
    noAutoLoad: false,
    htmlTexts: true,
    embedded: false,
    groupByPurpose: true,
    storageMethod: 'cookie',
    storageName: 'klaro',
    cookieDomain: 'awgifts.hr',
    cookieExpiresAfterDays: 30,
    default: false,
    mustConsent: false,
    acceptAll: true,
    hideDeclineAll: false,
    hideLearnMore: false,
    noticeAsModal: false,
    translations: {
        en: {
            consentModal: {
                title: 'Cookie Policy',
                description: 'We use cookies to give you the best experience on our website and to allow us to analyse site usage, enhance site navigation and improve our marketing efforts - for full details please see our <a href="https://www.awgifts.hr/privatnost">Privacy Policy</a>. You can change your mind at any time.',
            },
            inlineTracker: {
                description: 'Example of an inline tracking script',
            },
            externalTracker: {
                description: 'Example of an external tracking script',
            },
            adsense: {
                description: 'Displaying of advertisements (just an example)',
                title: 'Google Adsense Advertisement',
            },
            cloudflare: {
                description: 'Protection against DDoS attacks',
            },
            purposes: {
                analytics: {
                    title: 'Analytics',
                    description: 'Analytics cookies are used to see how visitors use the website, eg. analytics cookies. Those cookies cannot be used to directly identify a certain visitor.',
                },
                essentials: {
                    title: 'Essentials',
                    description: 'Essential cookies allow core website functionality such as user login and account management. The website cannot be used properly without necessary cookies.',
                },
                functionals: {
                    title: 'Functional',
                    description: 'Functionality cookies are used to remember visitor information on the website, eg. language, timezone, enhanced content.',
                },
                personalisation: {
                    title: 'Personalisation',
                    description: 'Personalisation cookies allow users to access web services with certain predefined elements, established through a series of criteria on the user’s computer; these may include language preferences, the type of browser used to access services, the regional configuration from where the service is accessed, etc.',
                },
                advertising: {
                    title: 'Advertising',
                    description: 'Advertising cookies are used to identify visitors between different websites, eg. content partners, banner networks. Those cookies may be used by companies to build a profile of visitor interests or show relevant ads on other websites.',
                },
                security: {
                    title: 'Security',
                },
                livechat: {
                    title: 'Live Chat',
                },
                styling: {
                    title: 'Styling',
                },
            },
        },
    },
    services: [
        {
            name: 'Google Ads',
            default: false,
            purposes: ['marketing'],
        },
        {
            name: 'Facebook Ads',
            default: false,
            purposes: ['marketing'],
        },
        {
            name: 'Google Analytics',
            default: false,
            purposes: ['analytics'],
            statistics: ['_ga'],
        },
    ],
};

// Declare Klaro
window.klaro = Klaro
window.klaroConfig = configKlaro
Klaro.setup(configKlaro)

onMounted(() => {
    const css = {
        header: header[0] ? loadCss(header[0].css) : [],
        footer: footer[0] ? loadCss(footer[0].css) : [],
    };
    for (const selector in css) {
        for (const property in css[selector]) {
            let classString = "";
            for (const c in css[selector][property]) {
                classString += `${c}: ${css[selector][property][c]};`;
                dynamicClasses[selector] += `${property} { ${classString} } `;
            }
        }
    }
    const styleElement = document.createElement("style");
    styleElement.textContent = dynamicClasses.header + dynamicClasses.footer;
    document.head.appendChild(styleElement);
    // set Login Button logic
    const loginButton = document.querySelectorAll(
        '*[data-wowsbar-element="login"]'
    );
    // console.log(user)
    if (user) {
        loginButton.forEach((button) => {
            button.innerHTML = 'Dashboard'
        });
    }
});
</script>

<template>
    <div v-html="header[0]?.html" ></div>
    <slot />
    <div v-html="footer[0]?.html"></div>

    <notifications
        dangerously-set-inner-html
        :max="3"
        width="500"
        classes="custom-style-notification"
        :pauseOnHover="true"    
    >
        <template #body="props">
            <Notification :notification="props" />  
        </template>
    </notifications>
</template>


<style>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
a {
    color: black;
    text-decoration: none !important;
}
</style>