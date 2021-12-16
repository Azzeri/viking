<template>
	<admin-panel-layout title="Panel administratora">
		<h1 class="text-2xl font-black my-4">Panel Administratora</h1>
		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
			<div v-for="row in links" :key=row class="rounded-lg shadow-lg bg-primary text-primary-content p-4 w-36">
				<div class="flex flex-col items-center space-y-2">
					<i :class=row.icon></i>
					<Link :href=route(row.link) as="button" class="btn w-full btn-sm btn-ghost">{{ row.label }}</Link>
				</div>
			</div>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 my-16">
			<div v-for="row in stats" :key=row class="p-4 border rounded-lg shadow w-36">
				<h1 class="font-semibold text-gray-400">{{ row.label }}</h1>
				<h2 class="text-3xl font-extrabold ml-4 mt-2">{{ row.value }}</h2>
			</div>
		</div>

	</admin-panel-layout>
</template>

<script>
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import { Link } from '@inertiajs/inertia-vue3';

export default {
	props: {
		stats:Object
	}, 
	setup() {
		const links = [
			{ 'label':'użytkownicy', 'link': 'admin.users.index', 'icon': 'fas fa-users fa-3x' },
			{ 'label':'wydarzenia', 'link': 'admin.events.index', 'icon': 'fas fa-calendar-week fa-3x' },
			{ 'label':'sklep', 'link': 'admin.store_items.index', 'icon': 'fas fa-shopping-basket fa-3x' },
			{ 'label':'przedmioty', 'link': 'admin.inventory_items.index', 'icon': 'fas fa-ankh fa-3x' },
			{ 'label':'posty', 'link': 'admin.posts.index', 'icon': 'fas fa-clone fa-3x' },
			{ 'label':'zdjęcia', 'link': 'admin.photos.index', 'icon': 'fas fa-images fa-3x' },
		]
		
		return { links }
	},

	components: {
		AdminPanelLayout,
		Link
	},
};
</script>