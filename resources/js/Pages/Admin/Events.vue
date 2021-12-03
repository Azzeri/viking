<template>
  <admin-panel-layout title="Wydarzenia">
    <template #page-title>Wydarzenia</template>

    <template v-if="!events.data.length && filters.search == null && filters.filter == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego wydarzenia</h1>
		<button @click="modalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj wydarzenie
		</button>
	</template>

	<template v-else>
		<DataTable :columns=columns :data=events :filters=filters :frontFilters=frontFilters sortRoute="admin.events.index">

			<template #buttons>
				<button @click="modalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus fa-lg mr-2"></i>
					Dodaj wydarzenie
				</button>
			</template>

			<template #content>
				<tr v-for="row in events.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1">{{ row.id }}</td>
					<td class="px-3 py-1">{{ row.name }}</td>
					<td class="px-3 py-1">{{ row.addrTown }}</td>
					<td class="px-3 py-1">{{ row.date_start + ' - ' + row.date_end }}</td>
					<td class="px-3 py-1">
						<div @click="show(row)" class="btn btn-primary btn-xs">Szczegóły</div> 
					</td>
				</tr>
			</template>
			
		</DataTable>
	</template>

	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowe wydarzenie</template>

		<template #content>
			<jet-validation-errors class="my-6" />
			<form @submit.prevent=store>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Nazwa wydarzenia" class="input input-primary input-bordered">

					<div class="flex space-x-2 mt-4">
						<div class="w-1/2">
							<label class="label"><span class="label-text">Rozpoczęcie<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.date_start type="date"  class="input input-primary input-bordered w-full">
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
							<input v-model=form.time_start type="time" class="input input-primary input-bordered w-full">
						</div>
					</div>

					<div class="flex space-x-2">
						<div class="w-1/2">
							<label class="label"><span class="label-text">Zakończenie<span class="ml-1 text-red-500">*</span> (czas opcjonalny)</span></label> 
							<input v-model=form.date_end type="date"  class="input input-primary input-bordered w-full">
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Zakończenie (czas opcjonalny)</span></label> 
							<input v-model=form.time_end type="time" class="input input-primary input-bordered w-full">
						</div>
					</div>

					<div class="flex mt-4 space-x-2">
						<div class="w-full">
							<label class="label"><span class="label-text">Ulica<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrStreet type="text" placeholder="Ulica" class="input input-primary input-bordered w-full">
						</div>
						<div class="w-24">
							<label class="label"><span class="label-text">Nr<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrNumber type="text" placeholder="Nr" class="input input-primary input-bordered w-full">
						</div>
					</div>

					<div class="flex space-x-2">
						<div class="w-48">
							<label class="label"><span class="label-text">Kod pocztowy<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrPostCode type="text" placeholder="Kod pocztowy" class="input input-primary input-bordered w-full">
						</div>
						<div class="w-full">
							<label class="label"><span class="label-text">Miejscowość<span class="ml-1 text-red-500">*</span></span></label> 
							<input v-model=form.addrTown type="text" placeholder="Miejscowość" class="input input-primary input-bordered w-full">
						</div>
					</div>

					<label class="label mt-4">
						<span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary" placeholder="Opis..."></textarea>
					
				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store class="btn btn-info">Dodaj</button>
		</template>
	</CrudModal> 
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		events: Object,
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)

		const form = useForm({
			// name:null,

			// date_start:null,
			// time_start:null,
			// date_end:null,
			// time_end:null,
			
			// addrStreet:null,
			// addrNumber:null,
			// addrPostCode:null,
			// addrTown:null,

			// description:null,

			name:'maniutek',

			date_start:'2022-10-10',
			time_start:'08:00',
			date_end:'2022-10-11',
			time_end:'09:00',
			
			addrStreet:'Sezamkowa',
			addrNumber:'54',
			addrPostCode:'48-330',
			addrTown:'Nysa',

			description:'Długi opis',
		})

		const close = _ => { 
			modalOpened.value = false
			form.reset() 
		}

		const store = _ => { 
			form.post(route('admin.events.store'), {
				onSuccess: () => close()
			}) 
		}

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const show = (row) => Inertia.get(route("admin.events.show", row.id))

		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Nazwa', sortable: true},
			{name:'addrTown', label:'Miejscowość', sortable: true},
			{name:'date_start', label:'Termin', sortable: true},
			{name:'actions', label:'Działania'},
        ]

		const frontFilters = [
			{ label: 'Niezakończone', value: 0, color:'btn-secondary' },
			{ label: 'Zakończone', value: 1, color:'btn-accent' }
		]

		return { form, columns, modalOpened, frontFilters, close, currentDate, show, store }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		DataTable,
		CrudModal,
	},

});
</script>