<template>
    <div v-if="isLoading" class="d-flex justify-content-center">
        <b-spinner
            style="width: 3rem; height: 3rem;"
            variant="info"
        ></b-spinner>
    </div>
</template>

<script>
    export default {
        props: {
            isLoading: {
                type: Boolean,
                required: true,
            },
        },
    }
</script>
