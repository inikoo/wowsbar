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
            label:  trans("Prospect with"),
            name: "prospect_with",
            information : trans("filter deliveries based on the prospect"),
            value : {
                by : []
            },
        },
        {
            label: trans("Tags"),
            name: "tag",
            information :  trans("Filter by SEO tags"),
            value : {
                tags : [],
                state : 'all'
            },
        },
        {
            label:  trans("Last contacted"),
            name: "prospect_last_contacted",
            information : trans("filter recipients based on the last mailshot sent to them"),
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
