<template>
  <admin-panel-layout title="Użytkownicy">

	<!-- Data table -->
	<DataTable :columns=columns :data=users :filters=filters sortRoute="admin.users.index">

		<template #buttons>
			<button @click="createModalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
				<i class="fas fa-plus fa-lg mr-2"></i>
				Dodaj użytkownika
			</button>
		</template>

		<template #content>
			<tr v-for="row in users.data" :key="row" class="hover">
				<td class="font-bold">{{ row.id }}</td>
				<td>{{ `${row.name} ${row.nickname ? `"${row.nickname}"` : ''} ${row.surname}` }}</td>
				<td>{{ row.email }}</td>
				<td>{{ row.role ?? "Nie określono" }}</td>
				<td class="space-x-2 text-center">
					<button @click=showDetails(row) class="btn btn-xs btn-accent">Szczegóły</button>
				</td>
			</tr>
		</template>
		
	</DataTable>

	<!-- Modal - details -->
	<Modal :show=detailsModalOpened @close=close :id="'modal-2'">
		<template #side>
			<div class="flex space-x-2">
				<button @click=deleteRow(selectedUser) :disabled="selectedUser.id == adminPrivilege" class="btn btn-error btn-xs">
					<i class="fas fa-trash"></i>
					<span class="ml-2">Usuń</span>
				</button>
				<button @click="edit(selectedUser)" :disabled="selectedUser.id == adminPrivilege" :class="{'btn-info':!editMode, 'btn-error':editMode}" class="btn btn-xs">
					<i :class="{'fas fa-edit':!editMode, 'fas fa-times':editMode}"></i>
					<span v-html="editMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
				</button>
				<button v-if="editMode" @click="update(selectedUser.id)" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zapisz</button>
			</div>
		</template>
		<template v-if="!editMode" #content>
			<div class="mt-4 flex space-x-2">
				<img src="http://daisyui.com/tailwind-css-component-profile-1@56w.png" :alt="selectedUser.surname" class="block h-24 w-24 object-cover mask mask-squircle">
				<div class="mt-2">
					<h1><strong>{{ `${selectedUser.id}. ` }}</strong>{{ `${selectedUser.name} ${selectedUser.nickname ? `"${selectedUser.nickname}"` : ''} ${selectedUser.surname}` }}</h1>
					<h2 class="text-gray-500 text-sm">{{ selectedUser.role ?? "Nie przypisano roli" }}</h2>					
				</div>							
			</div>
			<div class="flex space-x-2 items-center mt-2 text-sm ml-2">
				<i class="fas fa-at"></i>
				<span>{{ `${selectedUser.email}` }}</span>
			</div>
			<div class="flex space-x-2 items-center mt-2 text-sm ml-2">
				<i class="fas fa-birthday-cake"></i>
				<span>{{ `${selectedUser.date_birth_formatted}, wiek: ${selectedUser.age}` }}</span>
			</div>
			<p class="ml-2 mt-2">{{ selectedUser.description }}</p>
		</template>
		<template v-else #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Imię<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Imię" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>

					<label class="label"><span class="label-text">Nazwisko<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.surname type="text" placeholder="Nazwisko" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.surname" class="label label-text-alt text-error text-sm">{{ form.errors.surname }}</label>

					<label class="label"><span class="label-text">Nick<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.nickname type="text" placeholder="Nick" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.nickname" class="label label-text-alt text-error text-sm">{{ form.errors.nickname }}</label>

					<label class="label"><span class="label-text">Data urodzenia<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.date_birth type="date" :max="currentDate()" min="1900-01-01" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.date_birth" class="label label-text-alt text-error text-sm">{{ form.errors.date_birth }}</label>

					<label class="label"><span class="label-text">Email<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.email type="email" placeholder="Email" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.email" class="label label-text-alt text-error text-sm">{{ form.errors.email }}</label>

					<label class="label"><span class="label-text">Rola<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.role type="text" placeholder="Rola użytkownika" class="input input-primary input-bordered input-sm">
					<label v-if="form.errors.role" class="label label-text-alt text-error text-sm">{{ form.errors.role }}</label>

					<label class="label"><span class="label-text">Opis</span></label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." max=255 min=3></textarea>
					<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
				</div>
			</form>
		</template>
	</Modal>

	<!-- Create user modal -->
	<Modal :show=createModalOpened @close=close :id="'modal-1'" :maxWidth="'max-w-sm'">
		<template #side>
			<h1 class="text-lg font-semibold">Nowy użytkownik</h1>
		</template>

		<template #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Email<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.email type="email" placeholder="Email" class="input input-primary input-bordered">
					<label v-if="form.errors.email" class="label label-text-alt text-error text-sm">{{ form.errors.email }}</label>

					<label class="label"><span class="label-text">Rola<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.role type="text" placeholder="Rola użytkownika" class="input input-primary input-bordered">
					<label v-if="form.errors.role" class="label label-text-alt text-error text-sm">{{ form.errors.role }}</label>
				</div>
			
			</form>
		</template>

		<template #footer>
			<button @click=generateLink() :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full">Dodaj</button>
		</template>
	</Modal>

  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		users: Object,
		filters: Object
	},

	setup(props) {
		// Show details of this user
		const selectedUser =  ref(props.users.data[0])

		// Modals visibility
		const createModalOpened = ref(false)
		const detailsModalOpened = ref(false)

		// Details modal edit mode
		const editMode = ref(false)

		// Returns admin privilege id
		const adminPrivilege = computed(() => usePage().props.value.privileges.IS_ADMIN)

		// Returns current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

		// Data form
		const form = useForm({
			name: null,
			surname: null,
			nickname: null,
			role: null,
			date_birth: null,
			email: null,
			description: null
		})

		// Reset form
		const reset = _ => {
			form.reset()
			form.clearErrors()
			editMode.value = null
		}

		// Close modals and reset data 
		const close = _ => { 
			createModalOpened.value = false
			detailsModalOpened.value = false
			reset()
		}

		// Show user details
		const showDetails = (item) => {
			selectedUser.value = item
			detailsModalOpened.value = true
		}

		// Edit user
		const edit = (row) => { 
			editMode.value = !editMode.value

			form.reset()
			form.clearErrors()
			
			form.name = row.name
            form.surname = row.surname
			form.nickname = row.nickname
			form.role = row.role
            form.date_birth = row.date_birth
            form.email = row.email
            form.description = row.description
		}

		// Update user
		const update = (id) => { 
			form.put(route('admin.users.update', id), {
				onSuccess: () => {
					reset()
					selectedUser.value = props.users.data.find(element => element.id == selectedUser.value.id)
				}
			}) 
		}

		// Delete user
        const deleteRow = (id) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.users.destroy', id), { 
				onSuccess: () => close()
			})
		}

		// Generate registration link
		const generateLink = _ => { 
			form.post(route('admin.users.generate_link'), {
				onSuccess: () => close()
			}) 
		}

		// Columns for data table
		const columns = [
			{ name:'id', label:'ID', sortable: true },
			{ name:'surname', label:'Użytkownik', sortable: true },
			{ name:'email', label:'Email', sortable: true },
			{ name:'role', label:'Rola', sortable: true },
			{ name:'actions', label:'' }
		]

		// Returned data
		return { 
			form, 
			columns, 
			createModalOpened, 
			detailsModalOpened, 
			editMode, 
			close, 
			adminPrivilege, 
			generateLink, 
			selectedUser, 
			showDetails, 
			edit, 
			update, 
			currentDate, 
			deleteRow 
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal
	},

});
</script>