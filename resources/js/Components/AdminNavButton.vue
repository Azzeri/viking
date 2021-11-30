<template>
    <div class="flex justify-start">
        <Link :href=href as="button" class="btn h-12 w-12 rounded-r-none lg:rounded-r-lg group-hover:rounded-r-none">
            <i :class="icon"></i>
            <slot name="avatar"></slot>
        </Link>
        <Link :href=href as="button" class="btn btn-primary lg:hidden lg:group-hover:flex rounded-l-none w-44 justify-start">
            <slot></slot>
        </Link>
    </div>
    
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    props: {
        href: String,
        icon: String
    },
    components: {
        Link
    }
}
</script>

<style>

</style>