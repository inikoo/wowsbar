import { trans } from "laravel-vue-i18n";
export default {
    QueryLists: [
        { label:  trans("Email") , value: "email" },
        { label: trans("Phone") , value: "phone" },
        { label: trans("Address") , value: "address" },
    ],
    FilterTags: [
        { label:  trans("All") , value: "all" },
        { label:  trans("Any"), value: "any" },
    ],
    contact: [
        { label: trans("Last Contact"), value: true },
        { label: trans("Never"), value: false },
    ],


    schemaForm: [
        {
            label: "Propspect by",
            name: "propspect_by",
            information : "filter deliveries based on the prospect",
            value : {
                by : []
            },
        },
        {
            label: "Tags",
            name: "tag",
            information : "Filter by SEO tags",
            value : {
                tags : [],
                state : 'all'
            },
        },
        {
            label: "Last contacted",
            name: "last_contact",
            information : "filter recipients based on the last mailshot sent to them",
            value : {
                state : false,
                data: {
                    unit: "week",
                    quantity: 1,
                },
            }
        },
    ],

};
