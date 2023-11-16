export default {
    QueryLists: [ 'Email','Phone','Address'],
    FilterTags: ["All","Any","Not Set"],
    contact: [ {label : "Last Contact" , value : true}, {label : "Never" , value : false} ],
    defaultValue: {
        query: ['Email'],
        tag: {
            state: "All",
            tags: [],
        },
        last_contact: {
            state: false,
            data: {
                unit: 'day',
                quantity: 1,
            },
        },
    },
};
