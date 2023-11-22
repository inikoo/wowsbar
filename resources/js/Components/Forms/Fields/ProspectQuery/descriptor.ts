export default {
    QueryLists: [
        { label: "Email", value: "email" },
        { label: "Phone", value: "phone" },
        { label: "Address", value: "address" },
    ],
    FilterTags: [
        { label: "All", value: "all" },
        { label: "Any", value: "any" },
    ],
    contact: [
        { label: "Last Contact", value: true },
        { label: "Never", value: false },
    ],

 /*    defaultValue: {
        query: [],
        tag: {
            state: "all",
            tags: [],
        },
        last_contact: {
            state: false,
            data: {
                unit: "week",
                quantity: 1,
            },
        },
    }, */

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
