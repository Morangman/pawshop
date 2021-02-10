import { DateTime } from 'luxon';

export default {
    data() {
        return {

        };
    },

    methods: {
        getQueryParams() {
            const parameters = _.pickBy(this.filters, _.identity);
    
            if (parameters.date_value !== null) {
                const dateRange = this.handleDateFilter(parameters.date_value);
        
                if (dateRange) {
                    parameters.date_range = dateRange;
                }
            }
            
            return parameters;
        },
        
        highlightSearchResult(resultString, queryString) {
            const regex = new RegExp(queryString, 'gi')

            return resultString !== undefined
                ? resultString.replace(regex, (substring) => {
                    return `<strong>${substring}</strong>`
                })
                : ''
        },

        getWrappedName(name, filter = 'name', shouldTruncate = true) {
            const tName = shouldTruncate ? _.truncate(name, { length: 50 }) : name;

            return this.filters[filter]
                ? this.highlightSearchResult(tName, this.filters[filter])
                : tName
        },

        formatDate(date, format) {
            return DateTime.fromSQL(date).toFormat(format);
        },
    },
}
