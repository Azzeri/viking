<template>
    <div class="navbar shadow-lg bg-neutral text-neutral-content sticky top-0 z-50">

        <!-- Left side -->
        <div class="px-2 mx-2 space-x-2 navbar-start">
            <!-- Logo -->
            <Link :href="route('about')" as=button class="btn btn-square btn-ghost">
                <jet-application-mark class="block h-9 w-auto" />
            </Link>
            <span class="text-lg font-bold">
                Barbarian
            </span>
        </div> 

        <!-- Center links -->
        <div class="hidden px-2 mx-2 navbar-center lg:flex">
            <div class="flex items-stretch">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    <i class="fas fa-newspaper"></i>
                    <div class="ml-2">Aktualności</div>
                </NavLink>
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    <i class="far fa-images"></i>
                    <div class="ml-2">Galeria</div>
                </NavLink>
                <NavLink :href="route('store')" :active="route().current('store')">
                    <i class="fas fa-shopping-basket"></i>
                    <div class="ml-2">Sklep</div>
                </NavLink>
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    <i class="far fa-calendar-alt"></i>
                    <div class="ml-2">Wydarzenia</div>
                </NavLink>
                <NavLink :href="route('about')" :active="route().current('about')">
                    <i class="far fa-address-card"></i>
                    <div class="ml-2">O nas</div>
                </NavLink>
            </div>
        </div> 

        <!-- Right side -->
        <div class="navbar-end">
            <!-- Authenticated user options -->
            <div data-tip="Ciemny motyw" class="tooltip tooltip-bottom tooltip-primary">
                <button class="btn btn-square btn-ghost">
                    <i class="fas fa-moon fa-lg"></i>
                </button>
            </div>
            <div v-if="$page.props.user != null" class=" items-center hidden lg:flex">
                <div data-tip="Panel administracyjny" class="tooltip tooltip-bottom tooltip-primary">
                    <Link :href="route('admin.dashboard')" class="btn btn-square btn-ghost">
                        <i class="fas fa-user-shield fa-lg"></i>
                    </Link>
                </div>
                <div data-tip="Wyloguj" class="tooltip tooltip-bottom tooltip-primary">
                    <button @click=logout class="btn btn-square btn-ghost">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </button>
                </div>
                <div data-tip="Profil" class="tooltip tooltip-bottom tooltip-primary">
                    <Link :href="route('profile.show') " class="avatar mx-3">
                        <div class="rounded-full w-10 h-10 hover:ring ring-primary ring-offset-base-100 ring-offset-2 cursor-pointer transition">
                            <img src="http://daisyui.com/tailwind-css-component-profile-1@56w.png">
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex-none lg:hidden">
                <label @click="showingNavigationDropdown = ! showingNavigationDropdown" class="btn btn-square btn-ghost">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </label>
            </div> 
        </div>

    </div>

    <!-- Responsive navigation menu -->
    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="lg:hidden">
        <ul class="menu py-4 bg-base-100">
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                <i class="fas fa-newspaper"></i>
                <div class="ml-2">Aktualności</div>
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                <i class="far fa-images"></i>
                <div class="ml-2">Galeria</div>
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('store')" :active="route().current('store')">
                <i class="fas fa-shopping-basket"></i>
                <div class="ml-2">Sklep</div>
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                <i class="far fa-calendar-alt"></i>
                <div class="ml-2">Wydarzenia</div>
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('about')" :active="route().current('about')">
                <i class="far fa-address-card"></i>
                <div class="ml-2">O nas</div>
            </ResponsiveNavLink>
        </ul>

        <!-- Authenticated user options -->
        <ul v-if="$page.props.user != null" class="menu py-4 bg-base-100 border-t-2">
            <div class="flex items-center space-x-3">
                <Link :href="route('profile.show') " class="avatar ml-4">
                    <div class="rounded-full w-14 h-14 hover:ring ring-primary ring-offset-base-100 ring-offset-2 cursor-pointer transition">
                        <img src="http://daisyui.com/tailwind-css-component-profile-1@56w.png">
                    </div>
                </Link>
                <div>
                    <div>{{ $page.props.user.name + ' ' + $page.props.user.surname }}</div>
                    <div>{{ $page.props.user.email }}</div>
                </div>
            </div>
            
            <ResponsiveNavLink  :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
                <i class="fas fa-user-shield"></i>
                <div class="ml-2">Panel administracyjny</div>
            </ResponsiveNavLink>
            <ResponsiveNavLink  @click=logout>
                <i class="fas fa-sign-out-alt"></i>
                <div class="ml-2">Wyloguj</div>
            </ResponsiveNavLink>
        </ul>
    </div>

</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { defineComponent, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

export default defineComponent({
    setup() {
        const showingNavigationDropdown = ref(false)
        const logout = _ => Inertia.post(route('logout'));

        return { showingNavigationDropdown, logout }
    },

    components: { JetApplicationMark, Link, NavLink, ResponsiveNavLink },
})

</script>
