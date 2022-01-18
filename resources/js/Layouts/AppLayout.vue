<template>
    <Head :title="title" />

    <!-- Flash message -->
    <FlashMessage></FlashMessage>

    <!-- Drawer -->
    <div :data-theme="darkTheme ? 'luxury' : 'fantasy'" class="shadow bg-base-200 drawer">
        <!-- Toggle side drawer hidden checkbox -->
        <input id="drawer-main" type="checkbox" class="drawer-toggle"> 

        <!-- Drawer content -->
        <div class="drawer-content">

            <!-- Navbar -->
            <Navbar :centerLinks=true :adminPanelLink=true @changeTheme=changeTheme></Navbar>
            <div v-if="displayNavbar" class="w-full self-start opacity-90 bg-no-repeat bg-cover shadow-md" :class="navbarHeight"
                 style='background-position:0 62%;background-image:url("https://images.unsplash.com/photo-1609894851180-7be27983da7d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80");'></div>
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
                        <Link :href="route('profile.show')" class="avatar z-50">
                            <div class="rounded-full w-12 h-12 cursor-pointer transition">
                                <img :src="profilePhotoSource($page.props.user)" :alt="$page.props.user.name" class="rounded-full">
                            </div>
                        </Link>
                        <Link as="button" class="btn btn-primary rounded-l-none pl-10 justify-start -ml-6" style="width:200px;">
                            Profil
                        </Link>
                    </div>
                    <AdminNavButton :href="route('admin.dashboard')" icon="fas fa-user-shield fa-lg">Panel</AdminNavButton>
                    <AdminNavButton icon="fas fa-sign-out-alt fa-lg" @click="$inertia.post(route('logout'))">Wyloguj</AdminNavButton>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
    import { defineComponent, ref } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import Footer from '@/Components/Footer.vue'
    import Navbar from '@/Components/Navbar.vue'
    import FlashMessage from '@/Components/FlashMessage.vue'
    import AdminNavButton from '@/Components/AdminNavButton.vue'
    import { darkTheme, changeTheme, profilePhotoSource} from '@/shared.js'

    export default defineComponent({
        props: {
            title: String,
            navbarHeight: {
                type:String,
                default:'h-40'
            },
            displayNavbar: {
                type:Boolean,
                default:true
            }
        },

        setup() {
            return { changeTheme, darkTheme, profilePhotoSource }
        },

        components: {
            Head, Link, Footer, FlashMessage, AdminNavButton, Navbar
        },
    })
</script>
<style>

</style>
