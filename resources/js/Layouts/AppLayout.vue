<template>
    <Head :title="title" />

    <!-- Flash message -->
    <FlashMessage></FlashMessage>

    <!-- Drawer -->
    <div class="shadow bg-base-200 drawer">
        <!-- Toggle side drawer hidden checkbox -->
        <input id="drawer-main" type="checkbox" class="drawer-toggle"> 

        <!-- Drawer content -->
        <div class="drawer-content">

            <!-- Navbar -->
            <Navbar :centerLinks=true :adminPanelLink=true></Navbar>

            <!-- Site content -->
            <main class="text-base-content">
                <div class="hero min-h-screen -mt-16 pt-16">
                    <slot></slot>
                </div>
            </main>

            <!-- Footer -->
            <Footer></Footer>
        </div> 

        <!-- Side drawer content -->
        <div class="drawer-side">
            <!-- Gray overlay -->
            <label for="drawer-main" class="drawer-overlay"></label> 
            <ul class="w-64 overflow-y-auto px-4 py-8 bg-accent text-accent-content menu justify-between">
                <div class="flex space-y-4 flex-col">
                    <AdminNavButton v-for="row in $page.props.navigation" :key=row :href="route(row.link)" :active="route().current(row.link)" :icon="row.icon">
                        {{ row.label }}
                    </AdminNavButton>
                </div>

                <div v-if="$page.props.user" class="lg:hidden flex space-y-4 flex-col">
                    <div class="flex">
                        <Link :href="route('profile.show')" class="avatar">
                            <div class="rounded-full w-12 h-12 cursor-pointer transition">
                                <img :src="$page.props.user.profile_photo_path || `https://ui-avatars.com/api/?name=${$page.props.user.name}&color=7F9CF5&background=EBF4FF`" :alt="$page.props.user.name" class="rounded-full">
                            </div>
                        </Link>
                        <Link as="button" class="btn btn-primary rounded-l-none pl-10 justify-start -ml-6" style="width:200px;">
                            Profil
                        </Link>
                    </div>
                    <AdminNavButton :href="route('admin.dashboard')" icon="fas fa-user-shield fa-lg">Panel</AdminNavButton>
                    <AdminNavButton icon="fas fa-sign-out-alt fa-lg" @click=logout>Wyloguj</AdminNavButton>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia'
    import Footer from '@/Components/Footer.vue'
    import Navbar from '@/Components/Navbar.vue'
    import FlashMessage from '@/Components/FlashMessage.vue'
    import AdminNavButton from '@/Components/AdminNavButton.vue'

    export default defineComponent({
        props: {
            title: String,
        },

        setup() {
            const logout = _ => Inertia.post(route('logout'));

            return { logout }
        },

        components: {
            Head, Link, Footer, FlashMessage, AdminNavButton, Navbar
        },
    })
</script>
<style>

</style>
