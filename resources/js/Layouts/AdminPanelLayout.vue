<template>
    <Head :title="title" />
    <body  class="background bg-cover bg-center">
    <div class="relative min-h-screen md:flex">

        <!-- mobile menu bar -->
        <div class="glass-admin-nav p-3 text-gray-100 flex justify-between md:hidden">
            <!-- logo -->
            <div class="flex"> 
                <div class="flex-shrink-0 flex items-center"> 
                    <Link :href="route('about')"> 
                        <jet-application-mark class="block h-9 w-auto" /> 
                    </Link> 
                </div> 
            </div>
            <!-- mobile menu button -->
            <div class="flex items-center lg:hidden pr-1">
                <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- sidebar -->
        <div :class="{'-translate-x-full': ! showingNavigationDropdown, 'translate-x-0':  showingNavigationDropdown }" 
              class="z-10 absolute md:relative glass-admin-aside text-blue-100 w-64 md:w-24 inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:translate-x-0 flex flex-col justify-between py-6">
            
            <nav class="space-y-5 flex flex-col items-center">
                <div class="flex justify-center"> 
                    <div class="flex"> 
                        <div class="flex-shrink-0 flex items-center"> 
                            <Link :href="route('about')"> 
                                <jet-application-mark class="block h-9 w-auto" /> 
                            </Link> 
                        </div> 
                    </div> 
                </div>

                <AdminNavButton class="pt-4" icon="fas fa-home fa-lg" :href="route('admin.dashboard')">Panel</AdminNavButton>
                <AdminNavButton icon="fas fa-users fa-lg" :href="route('admin.users.index')">Użytkownicy</AdminNavButton>
                
            </nav>
        </div>

        <!-- content -->
        <div class="flex-1">
            <div class="w-full p-3 md:p-4 lg:px-16 xl:px-64 glass-admin-heading text-gray-100 flex justify-between items-center z-0">
                <div class="text-2xl font-bold"><slot name="page-title"></slot></div>
                <div class="relative">
                    <jet-dropdown align="right" width="48">
                        <template #trigger>
                            <button v-if="$page.props.jetstream.managesProfilePhotos && $page.props.user != null" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </button>
                            <button v-else class="flex text-white text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <div class="rounded-full p-1" ><i class="fas fa-user fa-2x"></i></div>
                            </button>
                        </template>

                        <template #content v-if="$page.props.user != null">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Zarządzaj kontem
                            </div>

                            <jet-dropdown-link :href="route('profile.show')">
                                Profil
                            </jet-dropdown-link>

                            <jet-dropdown-link v-if="$page.props.user != null && $page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" :href="route('admin.dashboard')">
                                Panel administratora
                            </jet-dropdown-link>

                            <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                Tokeny API
                            </jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form @submit.prevent="logout">
                                <jet-dropdown-link as="button">
                                    Wyloguj
                                </jet-dropdown-link>
                            </form>
                        </template>
                        <template #content v-else>
                            <jet-dropdown-link :href="route('profile.show')">
                                Logowanie
                            </jet-dropdown-link>

                            <jet-dropdown-link :href="route('register')">
                                Rejestracja
                            </jet-dropdown-link>
                        </template>
                    </jet-dropdown>
                </div>
            </div>
            <slot></slot>
        </div>

    </div>
    </body>
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
    import AdminNavButton from '@/Components/AdminNavButton.vue'

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
            AdminNavButton,
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