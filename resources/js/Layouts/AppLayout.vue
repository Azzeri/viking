<template>
    <Head :title="title" />

    <div class="flex flex-col bg-base-200 min-h-screen">

        <!-- Navbar -->
        <Navbar></Navbar>

        <!-- Main Content -->
        <div class="flex-grow">
            <slot></slot>
        </div>

        <!-- Footer -->
        <Footer></Footer>

    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
    import Navbar from '@/Components/Navbar.vue'
    import Footer from '@/Components/Footer.vue'

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Head, Navbar, Footer
        },
    })
</script>
<style>

</style>
