<template>
    <div class="navbar shadow-lg bg-neutral text-neutral-content sticky top-0 z-50 h-16">

        <!-- Left side -->
        <div class="px-2 mx-2 space-x-2 navbar-start">
            <!-- Logo -->
            <Link :href="route('home')" as=button class="btn btn-square btn-ghost">
                <jet-application-mark class="block h-9 w-auto" />
            </Link>
            <span class="text-lg font-bold">
                {{ $page.props.groupInfo.name }}
            </span>
        </div> 

        <!-- Center links -->
        <div v-if="centerLinks" class="hidden px-2 mx-2 navbar-center lg:flex">
            <div class="flex items-stretch">
                <NavLink v-for="row in $page.props.navigation" :key=row :href="route(row.link)" :active="route().current(row.link)">
                    <i :class=row.icon></i>
                    <div class="ml-2">{{ row.label }}</div>
                </NavLink>
            </div>
        </div> 

        <!-- Right side -->
        <div class="navbar-end">
            <!-- <div data-tip="Ciemny motyw" class="tooltip tooltip-bottom lg:tooltip-left tooltip-primary">
                <button class="btn btn-square btn-ghost">
                    <i class="fas fa-moon fa-lg"></i>
                </button>
            </div> -->
            <!-- Authenticated user options -->
            <div v-if="$page.props.user != null" class=" items-center hidden lg:flex">
                <div v-if="adminPanelLink" data-tip="Panel administracyjny" class="tooltip tooltip-bottom tooltip-primary">
                    <Link :href="route('admin.dashboard')" class="btn btn-square btn-ghost">
                        <i class="fas fa-user-shield fa-lg"></i>
                    </Link>
                </div>
                <div data-tip="Wyloguj" class="tooltip tooltip-bottom tooltip-primary">
                    <button @click=logout class="btn btn-square btn-ghost">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </button>
                </div>
                <div data-tip="Profil" class="tooltip tooltip-bottom tooltip-primary flex items-center">
                    <Link :href="route('profile.show') " class="avatar mx-3">
                        <div class="rounded-full w-10 h-10 hover:ring ring-primary ring-offset-base-100 ring-offset-2 cursor-pointer transition">
                            <img :src="$page.props.user.profile_photo_path || `https://ui-avatars.com/api/?name=${$page.props.user.name} ${$page.props.user.surname}&color=7F9CF5&background=EBF4FF`" :alt="$page.props.user.name" class="rounded-full">
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex-none lg:hidden">
                <label for="drawer-main" class="btn btn-square btn-ghost">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                </label>
            </div> 
        </div>

    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import NavLink from '@/Components/NavLink.vue'
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

export default defineComponent({
    props: {
        centerLinks: Boolean,
        adminPanelLink: Boolean
    },

    setup() {
        const logout = _ => Inertia.post(route('logout'));
        
        return { logout }
    },

    components: { JetApplicationMark, Link, NavLink },
})

</script>
