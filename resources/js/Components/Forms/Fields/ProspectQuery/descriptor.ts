export default {
    QueryLists: [{label : "Email" , value : 'email'}, {label : 'Phone' , value : 'phone'}, {label : "Address" , value : 'address'}],
    FilterTags: [{label : "All" , value : 'all'},{label : "Any" , value : 'any'},{label : "Not Set" , value : 'not_set'}],
    contact: [ {label : "Last Contact" , value : true}, {label : "Never" , value : false} ],
    defaultValue: {
        query: [],
        tag: {
            state: undefined,
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
