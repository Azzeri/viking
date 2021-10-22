<template>
    <Head :title="title" />
    <div class="min-h-screen">
        <nav class="w-full bg-gray-700 text-white p-4">
            <div class="flex justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <Link :href="route('about')">
                            <jet-application-mark class="block h-9 w-auto" />
                        </Link>
                    </div>
                    <h1 class="ml-4 text-2xl font-bold flex items-center text-white">Barbarian</h1>
                </div>
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
            </div>
        </nav>
        <div class="flex">
            <aside class="px-8 py-2 bg-gray-500 h-screen">
                <div>
                    <Link :href="route('admin.dashboard')">Panel</Link>
                </div>
                <div>
                    <Link :href="route('users.index')">Użytkownicy</Link>
                </div>
            </aside>
        

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
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
