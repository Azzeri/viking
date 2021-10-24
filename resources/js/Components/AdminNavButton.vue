<template>
    <div class="flex flex-col items-center">
        <Link :href=href as="button" class="p-2 glass-admin-nav-button rounded-lg shadow-lg hover:ring ring-gray-700 focus:ring-gray-800">
            <i :class="icon"></i>
        </Link>
        <span class="md:hidden mt-2"><slot></slot></span>
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