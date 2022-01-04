<template>
    <Head :title="title" />

    <!-- Success info message -->
    <FlashMessage></FlashMessage>

    <!-- Navbar -->
    <Navbar></Navbar>

    <!-- Drawer  -->
    <div class="shadow bg-base-100 text-base-content drawer drawer-mobile">
        <input id="drawer-main" type="checkbox" class="drawer-toggle"> 
        
        <!-- Drawer content -->
        <div class="flex flex-col drawer-content -mt-16 pt-16">
            <!-- Main content -->
            <main class="lg:ml-20 text-base-content">
                <div class="container mx-auto min-h-screen -mt-16 pt-16 px-2 md:px-5" :class="contentMaxWidth">
                    <slot></slot>
                </div>
            </main>
        </div> 
        
        <!-- Side drawer content -->
        <div class="drawer-side w-64 lg:w-auto lg:fixed h-full">
            <label for="drawer-main" class="drawer-overlay lg:hidden fixed w-full h-full"></label> 
            <ul class="px-4 pb-24 bg-accent text-accent-content menu lg:w-20 lg:hover:w-64 group h-full justify-between pt-8">
                <div class="flex space-y-4 flex-col">
                    <AdminNavButton icon="fas fa-home fa-lg" :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">Panel</AdminNavButton>
                    <AdminNavButton v-if="$page.props.user != null && $page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" icon="fas fa-users fa-lg" :href="route('admin.users.index')" :active="route().current('admin.users.index')">Użytkownicy</AdminNavButton>
                    <AdminNavButton icon="fas fa-calendar-week fa-lg" :href="route('admin.events.index')" :active="route().current('admin.events.index') || route().current('admin.events.show') || route().current('admin.events.task_manager')">Wydarzenia</AdminNavButton>
                    <AdminNavButton icon="fas fa-shopping-basket fa-lg" :href="route('admin.store_items.index')" :active="route().current('admin.store_items.index') || route().current('admin.store_categories.index') || route().current('admin.store_requests.index')">Sklep</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-ankh fa-lg" :href="route('admin.inventory_items.index')" :active="route().current('admin.inventory_items.index') || route().current('admin.inventory_categories.index') || route().current('admin.inventory_services.index')">Sprzęt</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-clone fa-lg" :href="route('admin.posts.index')" :active="route().current('admin.posts.index') || route().current('admin.posts.show')">Posty</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-images fa-lg" :href="route('admin.photos.index')" :active="route().current('admin.photo_categories.index') || route().current('admin.photos.index')">Zdjęcia</AdminNavButton>
                </div>

                <div class="lg:hidden flex space-y-4 flex-col">
                    <div class="flex">
                        <Link :href="route('profile.show')" class="avatar z-50">
                            <div class="rounded-full w-12 h-12 cursor-pointer transition">
                                <img :src="$page.props.user.profile_photo_path || `https://ui-avatars.com/api/?name=${$page.props.user.name}&color=7F9CF5&background=EBF4FF`" :alt="$page.props.user.name" class="rounded-full">
                            </div>
                        </Link>
                        <Link as="button" class="btn btn-primary rounded-l-none pl-10 justify-start -ml-6" style="width:200px;">
                            Profil
                        </Link>
                    </div>
                    <AdminNavButton icon="fas fa-sign-out-alt fa-lg" :active="route().current('logout')" @click="$inertia.post(route('logout'))">Wyloguj</AdminNavButton>
                </div>
            </ul>
        </div>
    </div>

</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import AdminNavButton from '@/Components/AdminNavButton.vue'
    import FlashMessage from '@/Components/FlashMessage.vue'
    import Navbar from '@/Components/Navbar.vue'

    export default defineComponent({
        props: {
            title: String,
            contentMaxWidth: {
                type:String,
                default:'max-w-4xl'
            }
        },

        components: {
            Head,
            Link,
            JetApplicationMark,
            AdminNavButton,
            FlashMessage,
            Navbar
        },
    })
</script>