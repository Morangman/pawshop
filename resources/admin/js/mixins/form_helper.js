export default {
    methods: {
        scrollToError () {
            this.$nextTick(() => {
                $('html, body').animate({
                    scrollTop: $('.error:visible:first').offset().top - 40 + 'px',
                }, 'slow')
            })
        },
        
        collectFormData (collection, outerKey = null) {
            for (const i in collection) {
                const value = collection[i]
                const innerKey = outerKey !== null ? `${outerKey}[${i}]` : i
                
                if (value instanceof Object) {
                    if (value instanceof File) {
                        this.formData.set(innerKey, value)
                    } else {
                        this.collectFormData(value, innerKey)
                    }
                } else if (value) {
                    this.formData.set(innerKey, value)
                }
            }
        },
        
        getErrorsByKey (key) {
            return this.errors !== undefined ? _.filter(this.errors, (v, k) => {
                return _.startsWith(k, key) === true;
            }) : [];
        },
    },
}
