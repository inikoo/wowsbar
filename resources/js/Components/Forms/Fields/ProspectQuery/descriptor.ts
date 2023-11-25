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


    schemaForm: [
        {
            label: "Prospect with",
            name: "prospect_with",
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
            name: "prospect_last_contacted",
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
