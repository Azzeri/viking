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
						<div @click="eventDetails(row)" class="btn btn-primary btn-xs">Szczegóły</div> 
					</td>
				</tr>
			</template>
			
		</DataTable>
	</template>

	<!-- <CrudModal :show=modalOpened @close=close>
		<template #title>Nowy użytkownik</template>
		<template #content v-if="!modalEditMode">

			<jet-validation-errors class="my-6" />
			<form id="generateLinkForm">
				<div class="mt-4">
					<jet-label for="email" value="Email" />
					<jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required placeholder="Wprowadź email" />
				</div>

				<div class="mt-4">
					<jet-label for="role" value="Rola w grupie" />
					<jet-input id="role" type="text" class="mt-1 block w-full" v-model="form.role" required placeholder="Wprowadź rolę" />
				</div>
			</form>

		</template>

		<template #content v-else>
			<jet-validation-errors class="my-6" />
			<form id="editForm" @submit.prevent=update>
				<div class="mt-6">
					<jet-input id="name" type="text" class="mt-1 block w-full" v-model="formEdit.name" required autofocus placeholder="Imię" autocomplete="name" />
				</div>

				<div class="mt-6">
					<jet-input id="surname" type="text" class="mt-1 block w-full" v-model="formEdit.surname" required placeholder="Nazwisko" autocomplete="surname" />
				</div>

				<div class="mt-6">
					<jet-input id="nickname" type="text" class="mt-1 block w-full" v-model="formEdit.nickname" placeholder="Nick" autocomplete="nickname" />
				</div>

				<div class="mt-6">
					<jet-input id="date_birth" type="date" class="mt-1 block w-full" v-model="formEdit.date_birth" :max="currentDate()" min="1900-01-01" autocomplete="date_birth" />
				</div>

				<div class="mt-6">
					<jet-input id="email" type="email" class="mt-1 block w-full" v-model="formEdit.email" required placeholder="Adres email" />
				</div>

				<div class="mt-6">
					<jet-input id="role" type="text" class="mt-1 block w-full" v-model="formEdit.role" required placeholder="Rola w grupie" />
				</div>

			</form>
		</template>

		<template #footer>
			<jet-button type="submit" form="generateLinkForm" v-if=!modalEditMode @click=generateLink class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Wygeneruj link do rejestracji
			</jet-button>

			<jet-button type="submit" form="editForm" v-else @click=update class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Edytuj
			</jet-button>

		</template>
	</CrudModal> -->
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({

	props: {
		events: Object,
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
			email: null,
			role: null
		})

		const reset = _ => { 
			form.reset()
			formEdit.reset()
			modalEditMode.value = false 
		}

		const close = _ => { 
			modalOpened.value = false
			reset() 
		}

		const edit = (row) => { 
			modalEditMode.value = true
			formEdit.id = row.id
			formEdit.name = row.name
			formEdit.surname = row.surname
			formEdit.nickname = row.nickname
			formEdit.date_birth = row.date_birth
			formEdit.email = row.email
			formEdit.role = row.role

			modalOpened.value = true 
		}

		const update = () => { 
			formEdit.put('events/'+formEdit.id, {
				onSuccess: () => close()
			}) 
		}

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const eventDetails = (row) => Inertia.get('events/' + row.id)

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

		return { form, columns, modalOpened, modalEditMode, frontFilters, close, edit, update, currentDate, eventDetails }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetButton,
		JetInput,
		JetLabel,
		JetValidationErrors,
		DataTable,
		CrudModal,
	},

});
</script>