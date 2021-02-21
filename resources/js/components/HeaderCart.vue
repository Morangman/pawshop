<template>
    <div>
        <a :href="$r('cart')" class="header-cart">
            <div class="count">{{ orders ? orders['order'].length : 0 }}</div>
            <span>My box</span>
        </a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                orders: JSON.parse(localStorage.getItem("orders"))
            };
        }
    }
</script>
