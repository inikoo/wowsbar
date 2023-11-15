export default {
    QueryLists: [
        { id: 1, title: "Email" },
        { id: 2, title: "Phone" },
        { id: 3, title: "Address" },
    ],

    FilterTags: [
        { id: 1, title: "All" },
        { id: 2, title: "Any" },
        { id: 3, title: "Not Set" },
    ],
    contact: ["Never", "Last Contact"],

    defaultValue: {
        query: ['email'],
        tag: {
            filter: "all",
            tag: ["seo", "ppc", "ssm", "developer"],
        },
        last_contact: {
            filter: "last contact",
            data: {
                range: "week",
                count: 1,
            },
        },
    },
};
