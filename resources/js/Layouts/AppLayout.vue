<template>
    <Head :title="title" />

    <div class="flex flex-col min-h-screen">
        <Navbar></Navbar>

        <!-- Main Content -->
        <div class="hero background bg-base-200 flex-grow">
            <div class="text-center hero-content">
                <div class="max-w-md">
                    <!-- <slot></slot> -->
                </div>
            </div>
        </div>
    
        <Footer></Footer>
    </div>


</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import Navbar from '@/Components/Navbar.vue'
    import Footer from '@/Components/Footer.vue'

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Head,
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            Link,
            Navbar,
            Footer
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    })
</script>
<style>

</style>
