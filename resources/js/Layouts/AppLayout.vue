<template>
    <Head :title="title" />
    <div class="background min-h-screen bg-cover bg-center flex items-center justify-center py-4">
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
                            <h1 class="ml-4 text-2xl font-bold flex items-center text-white">Barbarian</h1>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                Aktualności
                            </jet-nav-link>
                            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                Galeria
                            </jet-nav-link>
                            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                Materiały
                            </jet-nav-link>
                            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                Sklep
                            </jet-nav-link>
                            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                Wydarzenia
                            </jet-nav-link>
                            <jet-nav-link :href="route('about')" :active="route().current('about')">
                                O nas
                            </jet-nav-link>
                        </div>

                        <!-- Right Panel -->
                        <div class="hidden lg:flex lg:items-center lg:ml-6 lg:space-x-3">
                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos && $page.props.user != null" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
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

                        <!-- Hamburger -->
                        <div class="flex items-center lg:hidden">
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
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="lg:hidden">
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
                                <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('register')">
                                    Rejestracja
                                </jet-responsive-nav-link>
                            </template>
                            <template v-else>
                                <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profil
                                </jet-responsive-nav-link>

                                <jet-responsive-nav-link v-if="$page.props.user != null && $page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" :href="route('admin.dashboard')" :active="route().current('profile.show')">
                                    Panel administratora
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
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-600 text-gray-200 p-4">
        <div class="border-b-2 space-y-4 sm:flex sm:pb-4 sm:justify-between sm:px-8 max-w-6xl mx-auto">
            <div class="pt-4">
                <Link :href="route('about')">
                    <jet-application-mark class="block h-9 w-auto" />
                </Link>
            </div>
            <div>
                <h1><strong>Linki</strong></h1>
                <div><Link>Aktualności</Link></div>
                <div><Link>Galeria</Link></div>
                <div><Link>Materiały</Link></div>
                <div><Link>O nas</Link></div>
                <div><Link>Sklep</Link></div>
                <div><Link>Wydarzenia</Link></div>
            </div>
            <div>
                <h1><strong>Kontakt</strong></h1>
                <div>Nysa jakas tm</div>
                <div>48-300 Nysa</div>
                <div>123123123</div>
                <div>email@email.email</div>
            </div>
            <div>
                <h1><strong>Inne</strong></h1>
                <div><Link>Wilki</Link></div>
                <div><Link>Bobry</Link></div>
                <div><Link>Zające</Link></div>
            </div>
            <div class="pb-4">
                <h1><strong>Social Medias</strong></h1>
                <div class="flex gap-3 pt-2">
                    <i class="fab fa-facebook fa-lg"></i>
                    <i class="fab fa-instagram fa-lg"></i>
                    <i class="fab fa-youtube fa-lg"></i>
                    <i class="fab fa-twitter fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="pt-3 text-center">
            <h1 class="sm:hidden">© 2021 Mariusz Waloszczyk</h1> 
            <h1 class="sm:hidden">Wszelkie prawa zastrzeżone</h1>
            <h1 class="hidden sm:inline">© 2021 Mariusz Waloszczyk | Wszelkie prawa zastrzeżone</h1>
        </div>
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
