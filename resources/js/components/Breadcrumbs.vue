<template>
    <div class="page-header page-header-light" v-if="breadcrumbs && breadcrumbs.length">
        <div class="breadcrumb-line breadcrumb-line-light breadcrumb-line-component header-elements-md-inline">
            <div class="d-flex">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/#sell-device-section"><img width="12" height="12" src="../../client/images/home.svg"/> Home</a>
                    </li>
                    <li class="breadcrumb-item" v-for="(breadcrumb, index) in breadcrumbs" :key="`breadcrumb-${index}`">
                        <a :href="$r('get-category', { slug: breadcrumb.slug })">{{ breadcrumb.name }}</a>
                    </li>
                </ul>
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