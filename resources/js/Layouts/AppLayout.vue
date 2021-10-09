<template>
    <Head :title="title" />
    <div class="background min-h-screen bg-cover bg-center flex items-center justify-center">
        <div class="card-glass-main">
            <nav>
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 sm:pt-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <Link :href="route('about')">
                                    <jet-application-mark class="block h-9 w-auto" />
                                </Link>
                            </div>
                            <h1 class="ml-4 text-2xl font-bold flex items-center">Barbarian</h1>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('about')" :active="route().current('about')">
                                    O nas
                                </jet-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                <!-- {{ $page.props.user.name }} -->
                                                USERNAME
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            Profile
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                            API Tokens
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                Log Out
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Aktualności
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Galeria
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Materiały
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Sklep
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Wydarzenia
                        </jet-responsive-nav-link>
                        <jet-responsive-nav-link :href="route('about')" :active="route().current('about')">
                            O nas
                        </jet-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos && $page.props.user != null" class="flex-shrink-0 mr-3" >
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </div>

                            <div v-if="$page.props.user != null">
                                <div class="font-medium text-base text-gray-800">{{$page.props.user.name}}</div>
                                <div class="font-medium text-sm text-gray-500">{{$page.props.user.email}}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <template v-if="$page.props.user == null">
                                <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                    Logowanie
                                </jet-responsive-nav-link>
                                <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                    Rejestracja
                                </jet-responsive-nav-link>
                            </template>
                            <template v-else>
                                <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profil
                                </jet-responsive-nav-link>

                                <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                    Tokeny API
                                </jet-responsive-nav-link>

                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <jet-responsive-nav-link as="button">
                                        Wyloguj
                                    </jet-responsive-nav-link>
                                </form>
                            </template>
                        </div>

                        <div class="mt-4 space-x-4 ml-4">
                            <i class="fas fa-adjust fa-3x"></i>
                            <i class="fas fa-language fa-3x"></i>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
    <footer class="bg-white">
        Stoopki 
    </footer>
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
                showingNavigationDropdown: true,
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
        background-color: rgba(255, 255, 255, 0);
        border-radius: 40px;
        border: 1px solid rgba(209, 213, 219, 0.3);
        height: 95vh;
        width: 95vw;
    }

    .background {
        background-color:teal;
        /* background-image: url("https://images.pexels.com/photos/1933319/pexels-photo-1933319.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"); */
    }
</style>
