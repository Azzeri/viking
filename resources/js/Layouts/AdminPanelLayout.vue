<template>
    <Head :title="title" />
    <!-- Success info message -->
    <FlashMessage></FlashMessage>

    <!-- Navbar -->
    <div class="navbar shadow-lg bg-neutral text-neutral-content sticky top-0 z-50">

        <!-- Left side -->
        <div class="px-2 mx-2 space-x-2 navbar-start">
            <!-- Logo -->
            <Link :href="route('about')" as=button class="btn btn-square btn-ghost">
                <jet-application-mark class="block h-9 w-auto" />
            </Link>
            <span class="text-lg font-bold">
                {{ $page.props.groupInfo.name }}
            </span>
        </div> 

        <!-- Right side -->
        <div class="navbar-end">
            <!-- User options -->
            <div data-tip="Ciemny motyw" class="tooltip tooltip-bottom lg:tooltip-left tooltip-primary">
                <button class="btn btn-square btn-ghost">
                    <i class="fas fa-moon fa-lg"></i>
                </button>
            </div>
            <div data-tip="Wyloguj" class="hidden lg:flex tooltip tooltip-bottom tooltip-primary">
                <button @click=logout class="btn btn-square btn-ghost">
                    <i class="fas fa-sign-out-alt fa-lg"></i>
                </button>
            </div>
            <div data-tip="Profil" class="hidden lg:flex tooltip tooltip-bottom tooltip-primary items-center">
                <Link :href="route('profile.show') " class="avatar mx-3">
                    <div class="rounded-full w-10 h-10 hover:ring ring-primary ring-offset-base-100 ring-offset-2 cursor-pointer transition">
                        <img src="http://daisyui.com/tailwind-css-component-profile-1@56w.png">
                    </div>
                </Link>
            </div>
            <!-- Hamburger -->
            <div class="flex-none lg:hidden">
                <label for="my-drawer-3" class="btn btn-square btn-ghost">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                </label>
            </div> 
        </div>

    </div>

    <!-- Drawer  -->
    <div class="shadow bg-base-100 text-base-content drawer drawer-mobile">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle"> 
        
        <!-- Drawer content -->
        <div class="flex flex-col drawer-content">
            <!-- Main content -->
            <main class="lg:ml-20 text-base-content">
                <div class="hero min-h-screen -mt-16 pt-16 place-items-start">
                    <div class="hero-content flex-col w-full mx-auto">
                        <slot></slot>
                    </div>
                </div>
            </main>
        </div> 
        
        <!-- Side drawer content -->
        <div class="drawer-side fixed h-full">
            <label for="my-drawer-3" class="drawer-overlay lg:hidden fixed w-full h-full"></label> 
            <ul class="px-4 pb-24 bg-accent text-accent-content pt-8 menu lg:w-20 lg:hover:w-64 group h-full justify-between">
                <div class="flex space-y-4 flex-col">
                    <AdminNavButton icon="fas fa-home fa-lg" :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">Panel</AdminNavButton>
                    <AdminNavButton v-if="$page.props.user != null && $page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" icon="fas fa-users fa-lg" :href="route('admin.users.index')" :active="route().current('admin.users.index')">Użytkownicy</AdminNavButton>
                    <AdminNavButton icon="fas fa-calendar-week fa-lg" :href="route('admin.events.index')" :active="route().current('admin.events.index')">Wydarzenia</AdminNavButton>
                    <AdminNavButton icon="fas fa-shopping-basket fa-lg" :href="route('admin.store.items.index')" :active="route().current('admin.store.items.index')">Sklep</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-ankh fa-lg" :href="route('admin.inventory_items.index')" :active="route().current('admin.inventory_items.index')">Sprzęt</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-clone fa-lg" :href="route('admin.posts.index')" :active="route().current('admin.posts.index')">Posty</AdminNavButton>
                    <AdminNavButton icon="fas fas fa-images fa-lg" :href="route('admin.photo_categories.index')" :active="route().current('admin.photo_categories.index')">Zdjęcia</AdminNavButton>
                </div>

                <!-- User options -->
                <div class="lg:hidden flex space-y-4 flex-col">
                    <div class="flex">
                        <Link :href="route('profile.show')" class="avatar">
                            <div class="rounded-full w-12 h-12 cursor-pointer transition">
                                <img src="http://daisyui.com/tailwind-css-component-profile-1@56w.png">
                            </div>
                        </Link>
                        <Link as="button" class="btn btn-primary rounded-l-none pl-10 justify-start -ml-6" style="width:200px;">
                            Profil
                        </Link>
                    </div>
                    <AdminNavButton icon="fas fa-sign-out-alt fa-lg" :active="route().current('logout')" @click=logout>Wyloguj</AdminNavButton>
                </div>
            </ul>
        </div>
    </div>

</template>

<script>
    import { defineComponent } from 'vue'
    import { Inertia } from '@inertiajs/inertia'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import AdminNavButton from '@/Components/AdminNavButton.vue'
    import FlashMessage from '@/Components/FlashMessage.vue'

    export default defineComponent({
        props: {
            title: String,
        },

        setup() {
            const logout = _ => Inertia.post(route('logout'));
            
            return { logout }
        },

        components: {
            Head,
            Link,
            JetApplicationMark,
            AdminNavButton,
            FlashMessage
        },
    })
</script>