<template>
  <admin-panel-layout title="Wydarzenia">

	<!-- Data not present -->
    <template v-if="!events.data.length && filters.search == null && filters.filter == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego wydarzenia</h1>
		<button @click="createModalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj wydarzenie
		</button>
	</template>

	<!-- Data present -->
	<template v-else>
		<DataTable :columns=columns :data=events :filters=filters :frontFilters=frontFilters sortRoute="admin.events.index">

			<template #buttons>
				<button @click="createModalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus fa-lg mr-2"></i>
					Dodaj wydarzenie
				</button>
			</template>

			<template #content>
				<tr v-for="row in events.data" :key="row" class="hover">
					<td class="font-bold">{{ row.id }}</td>
					<td>{{ row.name }}</td>
					<td>{{ row.addrTown }}</td>
					<td>{{ `${row.date_start} - ${row.date_end}` }}</td>
					<td><Link :href="route('admin.events.show', row.id)" class="btn btn-primary btn-xs">Szczegóły</Link></td>
				</tr>
			</template>
			
		</DataTable>
	</template>

	<!-- Create event modal -->
	<Modal :show=createModalOpened @close=close id="create-event-modal">
		<template #side>
			<h1 class="text-lg font-semibold">Nowe wydarzenie</h1>
		</template>

		<template #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Nazwa wydarzenia" class="input input-primary input-bordered" required>
					<label v-if="form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>

					<div class="flex space-x-2 mt-4">
						<div class="w-1/2">
							<label class="label"><span class="label-text">Rozpoczęcie<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.date_start type="date" :min=currentDate() class="input input-primary input-bordered w-full" required>
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
							<input v-model=form.time_start type="time" class="input input-primary input-bordered w-full" required>
						</div>
					</div>
					<label v-if="form.errors.date_start" class="label label-text-alt text-error text-sm">{{ form.errors.date_start }}</label>
					<label v-if="form.errors.time_start" class="label label-text-alt text-error text-sm">{{ form.errors.time_start }}</label>

					<div class="flex space-x-2">
						<div class="w-1/2">
							<label class="label"><span class="label-text">Zakończenie<span class="ml-1 text-red-500">*</span> (czas opcjonalny)</span></label> 
							<input v-model=form.date_end type="date" :min=currentDate() class="input input-primary input-bordered w-full" required>
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Zakończenie (czas opcjonalny)</span></label> 
							<input v-model=form.time_end type="time" class="input input-primary input-bordered w-full">
						</div>
					</div>
					<label v-if="form.errors.date_end" class="label label-text-alt text-error text-sm">{{ form.errors.date_end }}</label>
					<label v-if="form.errors.time_end" class="label label-text-alt text-error text-sm">{{ form.errors.time_end }}</label>

					<div class="flex mt-4 space-x-2">
						<div class="w-full">
							<label class="label"><span class="label-text">Ulica<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrStreet type="text" placeholder="Ulica" class="input input-primary input-bordered w-full" required>
						</div>
						<div class="w-24">
							<label class="label"><span class="label-text">Nr<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrNumber type="text" placeholder="Nr" class="input input-primary input-bordered w-full" required>
						</div>
					</div>

					<div class="flex space-x-2">
						<div class="w-48">
							<label class="label"><span class="label-text">Kod<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrPostCode type="text" placeholder="Kod pocztowy" class="input input-primary input-bordered w-full" required>
						</div>
						<div class="w-full">
							<label class="label"><span class="label-text">Miejscowość<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrTown type="text" placeholder="Miejscowość" class="input input-primary input-bordered w-full" required>
						</div>
					</div>
					<label v-if="form.errors.addrStreet" class="label label-text-alt text-error text-sm">{{ form.errors.addrStreet }}</label>
					<label v-if="form.errors.addrNumber" class="label label-text-alt text-error text-sm">{{ form.errors.addrNumber }}</label>
					<label v-if="form.errors.addrPostCode" class="label label-text-alt text-error text-sm">{{ form.errors.addrPostCode }}</label>
					<label v-if="form.errors.addrTown" class="label label-text-alt text-error text-sm">{{ form.errors.addrTown }}</label>

					<label class="label mt-4">
						<span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." required></textarea>
					<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
					
				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full ">Dodaj</button>
		</template>
	</Modal> 
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		events: Object,
		filters: Object
	},

	setup() {
		// Modal visibility
		const createModalOpened = ref(false)
		
		// Data form
		const form = useForm({
			name:null,

			date_start:null,
			time_start:null,
			date_end:null,
			time_end:null,
			
			addrStreet:null,
			addrNumber:null,
			addrPostCode:null,
			addrTown:null,

			description:null
		})

		// Close modal and reset form
		const close = _ => { 
			createModalOpened.value = false
			form.reset() 
			form.clearErrors()
		}

		// Store event in database
		const store = _ => { 
			form.post(route('admin.events.store'), {
				onSuccess: () => close()
			}) 
		}

		// Return current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

		// Columns for data table
		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Nazwa', sortable: true},
			{name:'addrTown', label:'Miejscowość', sortable: true},
			{name:'date_start', label:'Termin', sortable: true},
			{name:'actions', label:'Działania'},
        ]

		// Filters for data table
		const frontFilters = [
			{ label: 'Niezakończone', value: 0, color:'btn-secondary' },
			{ label: 'Zakończone', value: 1, color:'btn-accent' }
		]

		// Returned data
		return { form, columns, createModalOpened, frontFilters, close, currentDate, store }
	},

	components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal,
	},

});
</script>