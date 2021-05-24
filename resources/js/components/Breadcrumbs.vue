<template>
    <div class="page-header page-header-light" v-if="breadcrumbs && breadcrumbs.length">
        <div class="breadcrumb-line breadcrumb-line-light breadcrumb-line-component header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/#sell-device-section" class="breadcrumb-item"><img width="12" height="12" src="../../client/images/home.svg"/> Home</a>
                    <a v-for="(breadcrumb, index) in breadcrumbs" :href="$r('get-category', { slug: breadcrumb.slug })" :key="`breadcrumb-${index}`" class="breadcrumb-item">{{ breadcrumb.name }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            breadcrumbs: {
                type: Array,
                required: false,
            },
        },
    };
</script>