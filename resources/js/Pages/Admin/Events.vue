<template>
  <admin-panel-layout title="Wydarzenia">

	<!-- Data not present -->
    <template v-if="!events.data.length && filters.search == null && filters.filter != null && filters.filter != 1">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego wydarzenia</h1>
		<button @click="createEvent" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj wydarzenie
		</button>
	</template>

	<!-- Data present -->
	<template v-else>
		<DataTable :columns=columns :data=events :filters=filters :frontFilters=frontFilters sortRoute="admin.events.index">

			<template #buttons>
				<button @click="createEvent" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus fa-lg mr-2"></i>
					Dodaj wydarzenie
				</button>
			</template>

			<template #content>
				<tr v-for="row in events.data" :key="row" class="hover">
					<th class="font-bold">{{ row.id }}</th>
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
			<form @submit.prevent="store">
				<div class="form-control mt-4">
					<form-input-field id="focus-create" type="text" name="Nazwa" :required="true" model="name" :form="form" max="128" min="3"></form-input-field>

					<div class="flex space-x-2 mt-4">
						<div class="w-1/2">
							<form-input-field type="date" name="Rozpoczęcie" :required="true" model="date_start" :form="form" :min="currentDate()" extraClass="w-full"></form-input-field>
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
							<form-input-field type="time" name="Rozpoczęcie" :required="true" model="time_start" :form="form" :label=false extraClass="w-full"></form-input-field>
						</div>
					</div>

					<div class="flex space-x-2">
						<div class="w-1/2">
							<form-input-field type="date" name="Zakończenie" :required="true" model="date_end" :form="form" :min="currentDate()" extraClass="w-full"></form-input-field>
						</div>
						<div class="w-1/2">
							<label class="label"><span class="label-text text-white">Zakończenie</span></label> 
							<form-input-field type="time" name="Zakończenie" :required="false" model="time_end" :form="form" :label=false extraClass="w-full"></form-input-field>
						</div>
					</div>

					<div class="flex mt-4 space-x-2">
						<div class="w-full">
							<form-input-field type="text" name="Ulica" :required="true" model="addrStreet" :form="form" extraClass="w-full" min="3" max="64"></form-input-field>
						</div>
						<div class="w-24">
							<form-input-field type="text" name="Nr" :required="true" model="addrNumber" :form="form" extraClass="w-full" min="1" max="10"></form-input-field>
						</div>
					</div>

					<div class="flex space-x-2">
						<div class="w-48">
							<form-input-field type="text" name="Kod" :required="true" model="addrPostCode" :form="form" extraClass="w-full" min="3" max="10"></form-input-field>
						</div>
						<div class="w-full">
							<form-input-field type="text" name="Miejscowość" :required="true" model="addrTown" :form="form" extraClass="w-full" min="3" max="64"></form-input-field>
						</div>
					</div>

					<label class="label mt-4">
						<span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." required min="3" max="512"></textarea>
					<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>

					<input type="file" id="upload-file-store" @change="previewImage" ref="photo" accept="image/*" @input="form.image = $event.target.files[0]" class="hidden" />
					<div v-if="url && form.image" class="mx-auto indicator mt-2">
						<div class="indicator-item">
							<button v-if="url && form.image" @click="form.image=null" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></button>
						</div> 
						<img :src="url" class="block h-24 w-24 object-cover mask mask-squircle" />
					</div>
					<label for="upload-file-store" refs="upload-file" class="btn btn-primary mt-2">Wybierz zdjęcie</label>
					<label v-if="form.hasErrors && form.errors.image" class="label label-text-alt text-error text-sm">{{ form.errors.image }}</label>
					
				</div>
				<input type="submit" ref="createEventSubmit" class="hidden" />
			</form>
		</template>

		<template #footer>
			<button @click="$refs.createEventSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full ">Dodaj</button>
		</template>
	</Modal> 
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'
import FormInputField from "@/Components/FormInputField.vue";

export default defineComponent({

	props: {
		events: Object,
		filters: Object
	},

	setup() {
		// Modal visibility
		const createModalOpened = ref(false)

		const url = ref(null)

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

			description:null,
			image:null
		})

		// Close modal and reset form
		const close = _ => { 
			createModalOpened.value = false
			form.reset() 
			form.clearErrors()
		}

		const createEvent = (_) => {
			createModalOpened.value = true
			nextTick(() => document.getElementById("focus-create").focus());
		}

		// Store event in database
		const store = _ => { 
			form.post(route('admin.events.store'), {
				onSuccess: () => close()
			}) 
		}

		// Return current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

		const previewImage = (e) => url.value = URL.createObjectURL(e.target.files[0])

		const removeImage = _ => {
			form.image = null
			document.getElementById('category-image').src = "/images/default.png"
		}

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
		return { form, columns, createModalOpened, frontFilters, close, currentDate, createEvent, store, previewImage, removeImage, url }
	},

	components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal,
		FormInputField
	},

});
</script>