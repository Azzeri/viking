<template>
	<admin-panel-layout title="Panel administratora">
		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 justify-items-center my-16">
			<div v-for="row in links" :key=row class="rounded-lg bg-primary shadow-lg text-primary-content p-4 w-full" :class="{ 'bg-gray-400': row.admin && !isAuthAdmin }">
				<div class="flex flex-col items-center space-y-2">
					<i :class=row.icon></i>
					<Link :href=route(row.link) as="button" :disabled="row.admin && !isAuthAdmin" class="btn w-full btn-sm btn-ghost">{{ row.label }}</Link>
				</div>
			</div>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 justify-items-center">
			<div v-for="row in stats" :key=row class="p-4 border rounded-lg shadow w-full xl:px-2">
				<h1 class="font-semibold text-gray-400">{{ row.label }}</h1>
				<h2 class="text-3xl font-extrabold ml-4 mt-2">{{ row.value }}</h2>
			</div>
		</div>

		<div class="flex flex-wrap gap-2 mt-5">
			<button @click="infoModalOpened = true" class="btn btn-secondary">Informacje o grupie</button>
			<button @click="addressModalOpened = true" class="btn btn-secondary">Dane adresowe</button>
			<button @click="linksModalOpened = true" class="btn btn-secondary">Odnośniki</button>
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

		<Modal :show=infoModalOpened @close=close :id="'modal-info'" :maxWidth="'md:max-w-lg'">
			<template #side>
				<h1 class="text-lg font-semibold">Informacje o grupie</h1>
			</template>

			<template #content>
				<form @submit.prevent="update">
					<div class="form-control mt-4">
						<form-input-field type="text" name="Skrócona nazwa" :required="true" model="short_name" :form="form" min="3" max="15" ></form-input-field>
						<form-input-field type="text" name="Pełna nazwa" :required="true" model="full_name" :form="form" min="3" max="32" ></form-input-field>
						<form-input-field type="text" name="Motto" :required="true" model="motto" :form="form" min="3" max="32" ></form-input-field>

						<label class="label"><span class="label-text">Opis</span></label> 
						<textarea v-model=form.description class="textarea h-48 textarea-bordered textarea-primary resize-none" placeholder="Opis..." maxlength=500 minlength=3></textarea>
						<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
					</div> 
					<input type="submit" ref="updateSubmit" class="hidden" />
				</form>
			</template>

			<template #footer>
				<button @click="$refs.updateSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full ">Zapisz</button>
			</template>
		</Modal>

		<Modal :show=addressModalOpened @close=close :id="'modal-address'" :maxWidth="'md:max-w-lg'">
			<template #side>
				<h1 class="text-lg font-semibold">Dane adresowe grupy</h1>
			</template>

			<template #content>
				<form @submit.prevent="update">
					<div class="form-control mt-4">
						<form-input-field type="text" name="Ulica" :required="true" model="addr_street" :form="form" min="3" max="64" ></form-input-field>
						<form-input-field type="text" name="Nr" :required="true" model="addr_number" :form="form" min="3" max="10" ></form-input-field>
						<form-input-field type="text" name="Kod" :required="true" model="addr_postCode" :form="form" min="3" max="10" ></form-input-field>
						<form-input-field type="text" name="Miejscowość" :required="true" model="addr_town" :form="form" min="3" max="64" ></form-input-field>

						<form-input-field type="text" name="Nr telefonu" :required="true" model="phone" :form="form" min="3" max="15" ></form-input-field>
						<form-input-field type="text" name="Adres email" :required="true" model="email" :form="form" min="3" max="32" ></form-input-field>
					</div> 
					<input type="submit" ref="updateSubmit" class="hidden" />
				</form>
			</template>

			<template #footer>
				<button @click="$refs.updateSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full ">Zapisz</button>
			</template>
		</Modal>

		<Modal :show=linksModalOpened @close=close :id="'modal-links'" :maxWidth="'md:max-w-xl'">
			<template #side>
				<h1 class="text-lg font-semibold">Odnośniki</h1>
			</template>

			<template #content>
				<form @submit.prevent="update">
					<div class="form-control mt-4">
						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 1" :required="false" model="link1_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 1 - link" :required="false" model="link1_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 2" :required="false" model="link2_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 2 - link" :required="false" model="link2_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 3" :required="false" model="link3_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 3 - link" :required="false" model="link3_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 4" :required="false" model="link4_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 4 - link" :required="false" model="link4_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 5" :required="false" model="link5_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 5 - link" :required="false" model="link5_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<div class="flex flex-col md:flex-row sm:items-end gap-2">
							<div class="sm:w-1/3">
								<form-input-field type="text" name="Link 6" :required="false" model="link6_label" :form="form" min="3" max="32" extraClass="w-full"></form-input-field>
							</div>
							<div class="sm:w-2/3">
								<form-input-field type="text" name="Link 6 - link" :required="false" model="link6_url" :form="form" min="3" max="32" extraClass="w-full" :label=false></form-input-field>
							</div>
						</div>

						<form-input-field type="text" name="Facebook" :required="false" model="facebook" :form="form" min="3" max="128"></form-input-field>
						<form-input-field type="text" name="Instagram" :required="false" model="instagram" :form="form" min="3" max="128"></form-input-field>
						<form-input-field type="text" name="Twitter" :required="false" model="twitter" :form="form" min="3" max="128"></form-input-field>
						<form-input-field type="text" name="Tiktok" :required="false" model="tiktok" :form="form" min="3" max="128"></form-input-field>
						<form-input-field type="text" name="Youtube" :required="false" model="youtube" :form="form" min="3" max="128"></form-input-field>

					</div> 
					<input type="submit" ref="updateSubmit" class="hidden" />
				</form>
			</template>

			<template #footer>
				<button @click="$refs.updateSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full ">Zapisz</button>
			</template>
		</Modal>

	</admin-panel-layout>
</template>

<script>
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { defineComponent, ref } from 'vue'
import { isAuthAdmin} from '@/shared.js'
import FormInputField from "@/Components/FormInputField.vue";
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({
	props: {
		stats: Object,
		eventTasks: Object,
		inventoryTasks: Object,
		groupInfo: Object
	}, 
	setup(props) {
		const infoModalOpened = ref(false)
		const addressModalOpened = ref(false)
		const linksModalOpened = ref(false)

		const form = useForm({
			short_name: props.groupInfo.short_name,
			full_name: props.groupInfo.full_name,
			motto: props.groupInfo.motto,
			description: props.groupInfo.description,

			addr_street: props.groupInfo.addr_street,
			addr_number: props.groupInfo.addr_number,
			addr_postCode: props.groupInfo.addr_postCode,
			addr_town: props.groupInfo.addr_town,

			phone: props.groupInfo.phone,
			email: props.groupInfo.email,

			facebook: props.groupInfo.facebook,
			youtube: props.groupInfo.youtube,
			twitter: props.groupInfo.twitter,
			instagram: props.groupInfo.instagram,
			tiktok: props.groupInfo.tiktok,

			link1_label: props.groupInfo.link1_label,
			link1_url: props.groupInfo.link1_url,
			link2_label: props.groupInfo.link2_label,
			link2_url: props.groupInfo.link2_url,
			link3_label: props.groupInfo.link3_label,
			link3_url: props.groupInfo.link3_url,
			link4_label: props.groupInfo.link4_label,
			link4_url: props.groupInfo.link4_url,
			link5_label: props.groupInfo.link5_label,
			link5_url: props.groupInfo.link5_url,
			link6_label: props.groupInfo.link6_label,
			link6_url: props.groupInfo.link6_url,
		})

		const close = _ => {
			form.reset()
			form.clearErrors()
			infoModalOpened.value = false
			addressModalOpened.value = false
			linksModalOpened.value = false
		}

		const update = _ => form.put(route('admin.groupInfo.update'))

		const links = [
			{ 'label':'użytkownicy', 'link': 'admin.users.index', 'icon': 'fas fa-users fa-3x', 'admin': true },
			{ 'label':'wydarzenia', 'link': 'admin.events.index', 'icon': 'fas fa-calendar-week fa-3x' },
			{ 'label':'sklep', 'link': 'admin.store_items.index', 'icon': 'fas fa-shopping-basket fa-3x' },
			{ 'label':'przedmioty', 'link': 'admin.inventory_items.index', 'icon': 'fas fa-ankh fa-3x' },
			{ 'label':'posty', 'link': 'admin.posts.index', 'icon': 'fas fa-clone fa-3x' },
			{ 'label':'zdjęcia', 'link': 'admin.photos.index', 'icon': 'fas fa-images fa-3x' },
		]

		return { links, isAuthAdmin, close, form, update, infoModalOpened, addressModalOpened, linksModalOpened }
	},

	components: {
		AdminPanelLayout,
		Link,
		FormInputField,
		Modal
	},
});
</script>