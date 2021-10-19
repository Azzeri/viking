<template>
    <Head :title="title" />
    <div class="min-h-screen">
       

        <!-- Page Content -->
        <main>
            <slot></slot>
        </main>
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
    .card-glass-main {
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        background-color: rgba(255, 255,255, 0.2);
        border-radius: 20px;
        border: 1px solid rgba(209, 213, 219, 0.3);
        min-height: 95vh;
        width: 95vw;
    }

    .background {
        /* background-color:#F7B535; */
        /* background-image: url("https://images.pexels.com/photos/693434/pexels-photo-693434.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"); */
        /* background-image: linear-gradient(to right bottom, #f7b535, #f8806d, #bd6c91, #716484, #4b4f54); */
        background-image: url("https://images.pexels.com/photos/1933319/pexels-photo-1933319.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260");
        /* background-image: url("https://images.pexels.com/photos/6985265/pexels-photo-6985265.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"); */
        /* background-image: url("https://images.pexels.com/photos/7130555/pexels-photo-7130555.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"); */
        /* background: linear-gradient(to left, #181818, #BA8B02);  */


    }
</style>
