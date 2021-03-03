<template>
    <div>
        <a :href="$r('cart')" class="header-cart">
            <div class="count">{{ orders ? ctn : 0 }}</div>
            <span>My box</span>
        </a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                orders: JSON.parse(localStorage.getItem("orders")),
                ctn: 0,
            };
        },

        created(){
            if (this.orders && this.orders['order'].length) {
                _.each(this.orders['order'], (key, value) => {
                    if(key) {
                        this.ctn += parseInt(key.ctn);
                    }
                });
            }
        }
    }
</script>
