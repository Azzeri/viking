<template>
	<admin-panel-layout title="Zamówienia">

		<RequestsDisplay :columns=columns :data=requests :filters=filters :frontFilters=frontFilters sortRoute="admin.store_requests.index">

			<template #buttons>
				<Link :href="route('admin.store_items.index')">
					<button class="btn btn-sm btn-primary w-full space-x-2">
						<i class="fas fa-arrow-left"></i>
						<span>Powrót</span>
					</button>
				</Link>
			</template>

			<template #content v-if="requests.data.length">
				<div class="overflow-y-auto mt-4">
					<ul class="menu">
						<li v-for="row in requests.data" :key="row.id" @click="showDetails(row)" class="hover-bordered">
							<a class="flex flex-col" style="align-items:flex-start;">
								<h1>{{ `Zamówienie nr ${row.id} - ${row.store_item_name}` }}</h1>
								<h3 class="text-xs text-gray-500">{{ row.created_at }}</h3>
							</a>
						</li>
					</ul>
				</div>
			</template>
			<template #content v-else>
				<h1 class="ml-2 text-lg font-semibold mt-2">Brak danych</h1>
			</template>
			
		</RequestsDisplay>

		<!-- Modal - details -->
		<Modal :show=detailsModalOpened @close=close :id="'modal-1'">

			<template #side>
				<div class="flex space-x-2">
					<template v-if="!selectedRequest.is_accepted && !selectedRequest.is_finished">
						<button @click="enableAcceptMode" :class="{'btn-success':!acceptRequestMode, 'btn-error':acceptRequestMode}" class="btn btn-xs">
							<i :class="{'fas fa-check':!acceptRequestMode, 'fas fa-times':acceptRequestMode}"></i>
							<span v-html="acceptRequestMode ? 'Anuluj' : 'Zaakceptuj'" class="ml-2"></span>
						</button>
						<button v-if="acceptRequestMode" @click="$refs.acceptRequestSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-info">Zapisz</button>
					</template>
					<button @click="deleteRow(selectedRequest.id)" class="btn btn-error btn-xs">
						<i class="fas fa-trash"></i>
						<span class="ml-2">{{ selectedRequest.is_accepted || selectedRequest.is_finished ? 'Usuń' : "Odrzuć" }}</span>
					</button>
					<button v-if="selectedRequest.is_accepted && !selectedRequest.is_finished" @click="finishRequest(selectedRequest.id)" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zakończ</button>
				</div>
			</template>

			<template #content>
				<ul class="mt-3">
					<li v-if="acceptRequestMode">
						<form @submit.prevent="acceptRequest(selectedRequest.id)">
							<textarea v-model=form.note id="note-textarea" class="textarea h-24 textarea-bordered textarea-primary resize-none w-full" placeholder="Notatka..." maxlength=255 minlength=3></textarea>
							<label v-if="form.errors.note" class="label label-text-alt text-error text-sm">{{ form.errors.note }}</label>
							<input type="submit" class="hidden" ref="acceptRequestSubmit" />
						</form>
					</li>
					<li>
						<h1 class="mt-4 font-semibold text-lg">{{ `Zamówienie nr ${selectedRequest.id}`}}</h1>
						<h2 class="text-sm text-gray-600">{{ selectedRequest.store_item_name }}</h2>
					</li>
					<li class="flex flex-col mt-3">
						<span class="font-semibold">Dodano</span>
						<span class="text-sm">{{ `${selectedRequest.created_at}`}}</span>
					</li>
					<li v-if="selectedRequest.date_due" class="mt-2 flex flex-col">
						<span class="font-semibold">Termin</span>
						<div class="text-sm">
							<span>{{ `${selectedRequest.date_due}, przypomnienie ` }}</span>
							<span v-if="selectedRequest.notification">włączone</span>
							<span v-else>wyłączone</span>
						</div>
					</li>
					<li v-if="selectedRequest.is_finished" class="mt-2 flex flex-col">
						<span class="font-semibold">Zakończono</span>
						<span class="text-sm">{{ `${selectedRequest.date_finished}` }}</span>
					</li>
					<li v-if="selectedRequest.description" class="mt-2 flex flex-col">
						<span class="font-semibold">Opis</span>
						<p class="text-sm text-justify">{{ selectedRequest.description }}</p>
					</li>
					<li v-if="selectedRequest.note" class="mt-2 flex flex-col">
						<span class="font-semibold">Notatka</span>
						<p class="text-sm">{{ selectedRequest.note }}</p>
					</li>
					<li class="mt-2 flex flex-col space-y-2">
						<span class="font-semibold">Dane klienta</span>
						<div class="ml-2 flex items-center space-x-2">
							<i class="fas fa-user"></i>
							<span class="text-sm">{{ selectedRequest.client_name }}</span>
						</div>
						<div class="ml-2 flex items-center space-x-2">
							<i class="fas fa-at"></i>
							<span class="text-sm">{{ selectedRequest.client_email }}</span>
						</div>
						<div class="ml-2 flex items-center space-x-2">
							<i class="fas fa-phone"></i>
							<span class="text-sm">{{ selectedRequest.client_phone ?? 'Nie podano' }}</span>
						</div>
					</li>
				</ul>
			</template>
		</Modal>

	</admin-panel-layout>
</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import RequestsDisplay from '@/Components/ServicesDisplay.vue'
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		requests: Object,
		filters: Object
	},

	setup() {
		// Modal visibility
		const detailsModalOpened = ref(false)

		// Request for details modal
		const selectedRequest = ref({'is_accepted':''})

		// Serivice edit mode
		const acceptRequestMode = ref(false)

		// Edit form
		const form = useForm({
			note: null
		})

		// Shows details modal
		const showDetails = (request) => {
			selectedRequest.value = request
			detailsModalOpened.value = true
		}

		// Close modals and reset form
		const close = _ => { 
			detailsModalOpened.value = false
			acceptRequestMode.value = false
			form.reset()
			form.clearErrors()
		}

		const enableAcceptMode = _ => {
			acceptRequestMode.value = !acceptRequestMode.value
			nextTick(() => {
				if (document.getElementById('note-textarea'))
					document.getElementById('note-textarea').focus()
			})
		}

		// Delete request
        const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.store_requests.destroy', row), {
				onSuccess: () => close()
			}) 
        }

		// Mark the request as finished
		const finishRequest = (row) => {
            if (!confirm('Czy potwierdzasz wykonanie serwisu?')) return;
            form.put(route('admin.store_requests.finish', row), {
				onSuccess: () => close()
			}) 
        }

		// Mark the request as accepted
		const acceptRequest = (row) => {
            form.put(route('admin.store_requests.accept', row), {
				onSuccess: () => close()
			}) 
        }

		// Sort options
		const columns = [
			{ name:'created_at', label:'Data malejąco', direction: 'desc' },
			{ name:'created_at', label:'Data rosnąco', direction: 'asc' },
			{ name:'store_item_id', label:'Przedmiot malejąco', direction: 'desc' },
			{ name:'store_item_id', label:'Przedmiot rosnąco', direction: 'asc' },
			{ name:'client_name', label:'Klient malejąco', direction: 'desc' },
			{ name:'client_name', label:'Klient rosnąco', direction: 'asc' },
			{ name:'id', label:'ID zamówienia malejąco', direction: 'desc' },
			{ name:'id', label:'ID zamówienia rosnąco', direction: 'asc' },
        ]

		// Available filters
		const frontFilters = [
			{ label: 'Zaakceptowane', value: 0 },
			{ label: 'Oczekujące', value: 1 },
			{ label: 'Wykonane', value: 2 },
		]
		
		// Returned data
		return { 
			form, 
			columns, 
			frontFilters, 
			acceptRequestMode, 
			selectedRequest, 
			detailsModalOpened ,
			close, 
			deleteRow, 
			finishRequest, 
			showDetails, 
			acceptRequest,
			enableAcceptMode
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		Modal,
		RequestsDisplay,
	},

});
</script>