<template>
	<admin-panel-layout title="Serwisy">

		<ServicesDisplay :columns=columns :data=services :filters=filters :frontFilters=frontFilters sortRoute="admin.inventory_services.index">

			<template #buttons>
				<Link :href="route('admin.inventory_items.index')" class="w-1/4 md:w-auto">
					<button class="btn btn-sm btn-primary w-full space-x-2">
						<i class="fas fa-arrow-left"></i>
						<span class="hidden md:inline">Powrót</span>
					</button>
				</Link>
				<div class="w-3/4 md:w-auto">
					<button @click="createModalOpened = true" class="btn btn-sm btn-primary w-full space-x-2">
						<i class="fas fa-plus"></i>
						<span>Nowy serwis</span>
					</button>
				</div>
			</template>

			<template #content v-if="services.data.length">
				<div class="overflow-y-auto mt-4">
					<ul class="menu">
						<li v-for="row in services.data" :key="row.id" @click="showDetails(row)" class="hover-bordered">
							<a class="flex flex-col" style="align-items:flex-start;">
								<h1>{{ row.name }}</h1>
								<h2 class="text-sm text-gray-600">{{ row.inventory_item.name }}</h2>
								<h3 v-if="row.date_due" class="text-xs" 
									:class="{'text-error':row.date_due < currentDate(), 'text-info': row.date_due == currentDate(), 
									'text-success': row.date_due > currentDate() }">{{ row.date_due_formatted }}
								</h3>
							</a>
						</li>
					</ul>
				</div>
			</template>
			<template #content v-else>
				<h1 class="ml-2 text-lg font-semibold mt-2">Brak danych</h1>
			</template>
			
		</ServicesDisplay>

		<!-- Modal - details -->
		<Modal :show=detailsModalOpened @close=close :id="'modal-2'">

			<template #side>
				<div class="flex space-x-2">
					<template v-if="isAuthAdmin || ($page.props.user.id == selectedService.created_by.id && (selectedService.assigned_user == null || selectedService.assigned_user.id == $page.props.user.id))">
						<button @click="deleteRow(selectedService.id)" class="btn btn-error btn-xs">
							<i class="fas fa-trash"></i>
							<span class="ml-2">Usuń</span>
						</button>
						<button v-if="!selectedService.is_finished" @click="edit(selectedService)" :class="{'btn-info':!editServiceMode, 'btn-error':editServiceMode}" class="btn btn-xs">
							<i :class="{'fas fa-edit':!editServiceMode, 'fas fa-times':editServiceMode}"></i>
							<span v-html="editServiceMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
						</button>
						<button v-if="editServiceMode" @click="$refs.updateServiceSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zapisz</button>
					</template>
				</div>
			</template>

			<template #content>
				<ul class="mt-3">
					<template v-if="!editServiceMode">
						<li>
							<h1 class="mt-4 font-semibold text-lg">{{ selectedService.name }}</h1>
							<h2 class="text-sm text-gray-600">{{ selectedService.inventory_item.name }}</h2>
						</li>
						<li class="flex flex-col mt-3">
							<span class="font-semibold">Dodano</span>
							<span class="text-sm">{{ `${selectedService.created_at_formatted}, ${formatUserName(selectedService.created_by)}` }}</span>
						</li>
						<li v-if="selectedService.date_due" class="mt-2 flex flex-col">
							<span class="font-semibold">Termin</span>
							<div class="text-sm">
								<span>{{ `${selectedService.date_due_formatted}, przypomnienie ` }}</span>
								<span v-if="selectedService.notification">włączone</span>
								<span v-else>wyłączone</span>
							</div>
						</li>
						<li class="mt-2 flex flex-col">
							<span class="font-semibold">Przydzielono</span>
							<span v-if="selectedService.assigned_user" class="text-sm">{{ formatUserName(selectedService.assigned_user) }}</span>
							<span v-else>Zadanie nie zostało jeszcze przydzielone</span>
						</li>
						<li v-if="selectedService.is_finished" class="mt-2 flex flex-col">
							<span class="font-semibold">Wykonano</span>
							<span class="text-sm">{{ `${selectedService.date_performed_formatted}, ${formatUserName(selectedService.performed_by)}` }}</span>
						</li>
						<li v-if="selectedService.description" class="mt-2 flex flex-col">
							<span class="font-semibold">Opis</span>
							<p class="text-sm text-justify">{{ selectedService.description }}</p>
						</li>
					</template>

					<template v-else>
						<form @submit.prevent="update(selectedService.id)" class="form-control">
							<label class="label"><span class="label-text">Przedmiot<span class="ml-1 text-error">*</span></span></label> 
							<select v-model=form.inventory_item_id class="select select-bordered select-primary select-sm text-sm w-full">
								<option v-for="row in items" :key=row.id :value=row.id>{{ row.name }}</option>
							</select>

							<form-input-field type="text" name="Nazwa" :required="true" model="name" :form="form" max="64" min="3"></form-input-field>
							<form-input-field type="date" name="Termin" :required="false" model="date_due" :form="form" :min="currentDate()"></form-input-field>

							<div v-if="form.date_due" class="flex items-center space-x-2 mt-2">
								<input v-model="form.notification" type="checkbox" class="checkbox checkbox-primary checkbox-sm">
								<span class="label-text">Przypomnienie</span> 
							</div>
							<template v-if="isAuthAdmin">
								<label class="label"><span class="label-text">Przypisz</span></label> 
								<select v-model=form.assigned_user class="select select-bordered select-sm select-primary text-sm w-full">
									<option :value="null">Nie przypisuj</option>
									<option v-for="row in users" :key=row.id :value=row.id>{{ row.name }}</option>
								</select>
							</template>
							<label class="label"><span class="label-text">Opis</span></label> 
							<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." max=255 min=3></textarea>
							<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
							<input type="submit" ref="updateServiceSubmit" class="hidden" />
						</form>
					</template>
				</ul>
			</template>
			<template #footer>
				<template v-if="!selectedService.is_finished && !editServiceMode">
					<button v-if="!selectedService.assigned_user" @click="assignAuth(selectedService)" class="btn btn-xs btn-success" :disabled="form.processing" :class="{ 'loading': form.processing }">Przypisz mnie</button> 
					<button v-if="isAuthAdmin || (selectedService.assigned_user != null && $page.props.user.id == selectedService.assigned_user.id)" @click="finishService(selectedService)" class="btn btn-xs btn-success" :disabled="form.processing" :class="{ 'loading': form.processing }">Zakończ</button>
				</template>
			</template>
		</Modal>

		<!-- Modal - create -->
		<Modal :show=createModalOpened @close=close :id="'modal-1'" :maxWidth="'max-w-sm'">
			<template #side>
				<h1 class="text-lg font-semibold">Nowy serwis</h1>
			</template>

			<template #content>
				<form @submit.prevent="store">
					<div class="form-control mt-4">
						<label class="label"><span class="label-text">Przedmiot<span class="ml-1 text-error">*</span></span></label> 
						<select v-model=form.inventory_item_id class="select select-bordered select-primary w-full">
							<option v-for="row in items" :key=row.id :value=row.id>{{ row.name }}</option>
						</select>

						<form-input-field type="text" name="Nazwa" :required="true" model="name" :form="form" max="64" min="3"></form-input-field>
						<form-input-field type="date" name="Termin" :required="false" model="date_due" :form="form" :min="currentDate()"></form-input-field>

						<div v-if="form.date_due" class="flex mt-4 items-center space-x-2">
							<input v-model="form.notification" type="checkbox" class="checkbox checkbox-primary">
							<span class="label-text">Przypomnienie</span> 
						</div>

						<template v-if="isAuthAdmin">
							<label class="label"><span class="label-text">Przypisz</span></label> 
							<select v-model=form.assigned_user class="select select-bordered select-primary w-full">
								<option :value="null">Nie przypisuj</option>
								<option v-for="row in users" :key=row.id :value=row.id>{{ row.name }}</option>
							</select>
						</template>

						<div v-else class="flex mt-4 items-center space-x-2">
							<input id="auth-assign" type="checkbox" class="checkbox checkbox-primary">
							<span class="label-text">Przypisz sobie</span> 
						</div>

						<label class="label"><span class="label-text">Opis</span></label> 
						<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." max=255 min=3></textarea>
						<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
					</div>
					<input type="submit" ref="createServiceSubmit" class="hidden" />
				</form>
			</template>

			<template #footer>
				<button @click="$refs.createServiceSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-primary w-full">Dodaj</button>
			</template>
		</Modal>

	</admin-panel-layout>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import ServicesDisplay from '@/Components/ServicesDisplay.vue'
import Modal from '@/Components/CrudModal.vue'
import FormInputField from "@/Components/FormInputField.vue";
import { isAuthAdmin} from '@/shared.js'

export default defineComponent({

	props: {
		services: Object,
		items: Object,
		users:Object,
		filters: Object
	},

	setup(props) {
		// Modals visibility
		const createModalOpened = ref(false)
		const detailsModalOpened = ref(false)

		// Service for details modal
		const selectedService = ref({ 'is_finished': false, 'created_at':'', 'created_by':'', 'assigned_user':'', 'inventory_item':'' })

		// Serivice edit mode
		const editServiceMode = ref(false)
		
		// Returns current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

		const formatUserName = (user) => `${user.name} ${user.nickname ? `"${user.nickname}"` : ""} ${user.surname}`

		// Create and edit form
		const form = useForm({
			name: null,
            description: null,
			date_due: null,
			inventory_item_id: props.items.length ? props.items[0].id : 0,
			assigned_user: null,
			notification: false,
		})

		// Shows details modal
		const showDetails = (service) => {
			selectedService.value = service
			detailsModalOpened.value = true
		}

		// Close modals and reset form
		const reset = _ => {
			form.reset()
			form.clearErrors()
		}

		const close = _ => { 
			detailsModalOpened.value = false
			createModalOpened.value = false
			editServiceMode.value = false
			reset()
		}

		// Edit mode
		const edit = (row) => { 
			editServiceMode.value = !editServiceMode.value

			form.name = row.name
            form.description = row.description
			form.date_due = row.date_due
            form.inventory_item_id = row.inventory_item.id
            form.assigned_user = row.assigned_user.id
            form.notification = row.notification
		}

		// Store service
		const store = _ => { 
			if (!isAuthAdmin.value) document.getElementById('auth-assign').checked ? form.assigned_user = usePage().props.value.user.id : form.assigned_user = null
			form.post(route('admin.inventory_services.store'), {
				onSuccess: () => reset()
			}) 
		}
        
		// Update service
		const update = (row) => { 
			form.put(route('admin.inventory_services.update', row), {
				onSuccess: () => close()
			}) 
		}

		// Delete service
        const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.inventory_services.destroy', row), {
				onSuccess: () => close()
			}) 
        }

		// Mark the service as finished
		const finishService = (row) => {
            if (!confirm('Czy potwierdzasz wykonanie serwisu?')) return;
            form.put(route('admin.inventory_services.finish', row), {
				onSuccess: () => close()
			}) 
        }

		// Assign authenticated user to the service
		const assignAuth = (row) => {
            if (!confirm('Na pewno?')) return;
            form.put(route('admin.inventory_services.assign', row), {
				onSuccess: () => close()
			})
        }

		// Sort options
		const columns = [
			{ name:'date_due', label:'Termin malejąco', direction: 'desc' },
			{ name:'date_due', label:'Termin rosnąco', direction: 'asc' },
			{ name:'name', label:'Nazwa rosnąco', direction: 'asc' },
			{ name:'name', label:'Nazwa malejąco', direction: 'desc' },
			{ name:'inventory_item_id', label:'Przedmiot rosnąco', direction: 'asc' },
			{ name:'inventory_item_id', label:'Przedmiot malejąco', direction: 'desc' },
        ]

		// Available filters
		const frontFilters = [
			{ label: 'Nieprzypisane', value: 0 },
			{ label: 'Przypisane', value: 1 },
			{ label: 'Moje', value: 2 },
			{ label: 'Wykonane', value: 3 },
		]
		
		// Returned data
		return { 
			assignAuth, 
			form, 
			isAuthAdmin, 
			columns, 
			frontFilters, 
			createModalOpened, 
			editServiceMode, 
			selectedService, 
			close, 
			store, 
			edit, 
			update, 
			deleteRow, 
			finishService, 
			showDetails, 
			currentDate, 
			formatUserName,
			detailsModalOpened
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		Modal,
		ServicesDisplay,
		FormInputField
	},

});
</script>