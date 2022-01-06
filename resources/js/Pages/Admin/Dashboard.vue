<template>
	<admin-panel-layout title="Panel administratora">
		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 justify-items-center my-16">
			<div v-for="row in links" :key=row class="rounded-lg shadow-lg bg-primary text-primary-content p-4 w-full">
				<div class="flex flex-col items-center space-y-2">
					<i :class=row.icon></i>
					<Link :href=route(row.link) as="button" class="btn w-full btn-sm btn-ghost">{{ row.label }}</Link>
				</div>
			</div>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 justify-items-center">
			<div v-for="row in stats" :key=row class="p-4 border rounded-lg shadow w-full xl:px-2">
				<h1 class="font-semibold text-gray-400">{{ row.label }}</h1>
				<h2 class="text-3xl font-extrabold ml-4 mt-2">{{ row.value }}</h2>
			</div>
		</div>

		<template v-if="eventTasks.length || inventoryTasks.length">
			<h1 class="text-xl font-bold mt-16">Moje zadania</h1>
			<!-- Event Tasks -->
			<div class="flex flex-col gap-3">
				<h2 class="text-lg font-bold mt-2">Wydarzenia</h2>
				<div v-for="task in eventTasks" :key="task" class="shadow border w-full rounded-lg p-4 flex flex-col sm:flex-row gap-4">
					<div class="sm:w-1/2">
						<h3 class="font-semibold text-gray-700 text-justify">{{ task.name }}</h3>
						<h4 class="font-gray-500 text-sm">{{ task.event }}</h4>
						<div class="flex items-center space-x-2 mt-1">
							<i class="fas fa-calendar-week"></i>
							<h1 class="text-sm font-gray-500">{{ task.date_due }}</h1>
						</div>
					</div>
					<div class="sm:w-1/2">
						<p class="text-justify">{{ task.description }}</p>
					</div>
				</div>
			</div>

			<!-- Inventory services Tasks -->
			<div class="flex flex-col gap-3 mb-16 mt-2">
				<h2 class="text-lg font-bold mt-2">Serwisy</h2>
				<div v-for="task in inventoryTasks" :key="task" class="shadow border w-full rounded-lg p-4 flex flex-col sm:flex-row gap-4">
					<div class="sm:w-1/2">
						<h3 class="font-semibold text-gray-700 text-justify">{{ task.name }}</h3>
						<h4 class="font-gray-500 text-sm">{{ task.item }}</h4>
						<div class="flex items-center space-x-2 mt-1">
							<i class="fas fa-calendar-week"></i>
							<h1 class="text-sm font-gray-500">{{ task.date_due }}</h1>
						</div>
					</div>
					<div class="sm:w-1/2">
						<p class="text-justify">{{ task.description }}</p>
					</div>
				</div>
			</div>
		</template>
		<h1 v-else class="text-xl font-bold mt-16">Nie masz przydzielonych zadań</h1>

	</admin-panel-layout>
</template>

<script>
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import { defineComponent } from 'vue'

export default defineComponent({
	props: {
		stats: Object,
		eventTasks: Object,
		inventoryTasks: Object
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
});
</script>