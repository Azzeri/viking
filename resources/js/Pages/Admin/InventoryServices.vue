<template>
	<admin-panel-layout title="Serwisy">
	
		<template #page-title>Serwisy</template>
		
		<div v-if="!services.data.length && filters.search == null && filters.filter == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
			<h1>Brak danych</h1>
			<div class="flex space-x-2">
				<Link as=button :href="route('admin.inventory.items.index')" class="sm:flex bg-white items-center bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
					<i class="fas fa-arrow-left fa-lg"></i>
				</Link>
				<button @click="modalOpened = true" class="lg:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
					<i class="fas fa-plus fa-lg"></i>
				</button>
				<button @click="modalOpened = true" class="hidden lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
					<i class="fas fa-plus fa-lg"></i>
				</button>
			</div>
		</div>

		<div v-else>
			<ServicesDisplay :columns=columns :links=services.links :filters=filters sortRoute="admin.inventory.services.index">

				<template #buttons>
					<Link as=button :href="route('admin.inventory.items.index')" class="lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-arrow-left fa-lg"></i>
					</Link>

					<button @click="modalOpened = true" class="lg:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
						<i class="fas fa-plus fa-lg"></i>
					</button>
					<button @click="modalOpened = true" class="hidden lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-plus fa-lg"></i>
					</button>
				</template>

				<template #content>
					<div v-for="row in services.data" :key="row" class="w-full rounded-lg bg-white p-3 mt-3">
						<div @click=showDetails(row.id) class="flex justify-between cursor-pointer">
							<div class="font-semibold"> {{ row.name }} </div>
							<div :id="'arrow-' + row.id">
								<i class="fas fa-arrow-down"></i>
							</div>
						</div>
						
						<div class="text-sm text-gray-600"> {{ row.inventory_item_name }} </div>
						<div class="hidden my-3" :id="'details-' + row.id">
							<ul>
								<li><span class="font-semibold">Utworzono: </span>{{ row.created_at.split('T')[0] }}</li>
								<li v-if="row.date_due"><span class="font-semibold">Termin: </span><span :class="checkDateDue(row.date_due)">{{ row.date_due }}</span></li>
								<li v-if="!row.is_finished && row.date_due"><span class="font-semibold">Przypomnienie: </span><span v-html="booleanIcon(row.notification)"></span></li>
								<li v-if=row.assigned_user><span class="font-semibold">Przydzielone użytkownikowi: {{ row.assigned_user }}</span></li>
								<li v-if=row.is_finished><span class="font-semibold">Wykonane przez: {{ row.performed_by }}</span></li>
								<li v-if=row.is_finished><span class="font-semibold">Wykonano: {{ row.date_performed }}</span></li>

								<li v-if="!row.is_finished" class="space-x-3">
									<i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
									<i v-if="$page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i>
								</li>
							</ul>
						</div>
						<div v-if=row.description>{{ row.description }}</div>
						<button v-if="row.is_finished == false" @click="finish(row)" class="font-semibold mt-2 px-2 py-1 bg-green-500 rounded-full text-white" >Zrobione!</button>
					</div>
				</template>
				
			</ServicesDisplay>
		</div>

	</admin-panel-layout>

	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowy serwis</template>

		<template #content>
			<jet-validation-errors class="my-6" />
			<form @submit.prevent="store, update">

				<div class="mt-6">
					<label for=item>Przedmiot</label>
					<select id=item class="w-full rounded-lg" v-model=form.inventory_item_id>
						<template v-for="row in items" :key=row>
							<option :value="row.id"> {{ row.name }} </option>
						</template>
					</select>
				</div>

				<div class="mt-6">
					<label for=name>Nazwa</label>
					<jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus placeholder="Nazwa" autocomplete="name" />
				</div>
				<div class="mt-6">
					<label for=description>Opis</label>
					<jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" placeholder="Opis" autocomplete="description" />
				</div>
				<div class="mt-6">
					<label for=date_due>Termin</label>
					<jet-input id="date_due" type="date" class="mt-1 block w-full" v-model="form.date_due" placeholder="Termin" autocomplete="date_due" :min=currentDate() />
				</div>								

				<div v-if="$page.props.user.privilege_id == $page.props.privileges.IS_ADMIN" class="mt-6">
					<label for=assigned_user>Przydziel użytkownikowi</label>
					<select id=assigned_user class="w-full rounded-lg" v-model=form.assigned_user>
						<template v-for="row in users" :key=row>
							<option :value="row.id"> {{ row.name }} </option>
						</template>
					</select>
				</div>

				<div class="mt-6" v-if="form.date_due">
					<checkbox id=notification v-model="form.notification" :checked="form.notification">asd</checkbox>
					<span class="ml-2">Przypomnienie</span>
				</div>

			</form>
		</template>

		<template #footer>
			<jet-button type="submit" v-if=!modalEditMode @click=store class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Dodaj
			</jet-button>

			<jet-button type="submit" v-else @click=update class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Edytuj
			</jet-button>
		</template>
	</CrudModal>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetButton from '@/Jetstream/Button.vue'
import Checkbox from '@/Jetstream/Checkbox.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import ServicesDisplay from '@/Components/ServicesDisplay.vue'
import CrudModal from '@/Components/CrudModal.vue'
import { Inertia } from '@inertiajs/inertia'
import Label from '@/Jetstream/Label.vue'

export default defineComponent({

	props: {
		services: Object,
		items: Object,
		users:Object,
		filters: Object
	},

	setup(props) {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const finish = (row) => {
            if (!confirm('Czy potwierdzasz wykonanie serwisu?')) return;
            Inertia.post('inventoryservicesfinish/', row)
        }

		const showDetails = (id) => {
			let element = document.getElementById('details-'+id)
			let arrow = document.getElementById('arrow-'+id)

			element.classList.contains('hidden') ? element.classList.remove('hidden') : element.classList.add('hidden')
			arrow.innerHTML.indexOf('down') != -1 ? arrow.innerHTML = '<i class="fas fa-arrow-up"></i>' : arrow.innerHTML = '<i class="fas fa-arrow-down"></i>'
		}

        const booleanIcon = (notification) => notification == true ? '<i class="fas fa-check text-green-500">' : '<i class="fas fa-times text-red-500">'

		const checkDateDue = (date_due) => {
			if(date_due < currentDate()) return 'text-red-500';
			else if (date_due == currentDate()) return 'text-yellow-500';
			else return 'text-green-500'
		}

		const form = useForm({
            id: null,
			name: null,
            description: null,
			date_due: null,
			inventory_item_id: props.items.length ? props.items[0].id : 0,
			assigned_user: props.users.length ? props.users[0].id : 0,
			notification: false
		})

		const reset = _ => { 
			form.reset()
			modalEditMode.value = false 
		}

		const close = _ => { 
			modalOpened.value = false
			reset() 
		}

		const edit = (row) => { 
			modalEditMode.value = true

			form.id = row.id
			form.name = row.name
            form.description = row.description
			form.date_due = row.date_due
            form.inventory_item_id = row.inventory_item_id
            form.assigned_user = row.assigned_user_id
            form.notification = row.notification

			modalOpened.value = true 
		}

        const store = _ => { 
			form.post('inventoryservices/', {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put('inventoryservices/' + form.id, {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete('inventoryservices/' + row.id)
        }

		const columns = [
			{name:'name', label:'Nazwa', sortable: true},
			{name:'description', label:'Opis'},
			{name:'created_at', label:'Data utworzenia', sortable: true},
			{name:'date_due', label:'Termin', sortable: true},
			{name:'notification', label:'Przypomnienie', sortable: true},
			{name:'is_finished', label:'Zakończony'},
			{name:'inventory_item_name', label:'Przedmiot'},

			{name:'inventory_item_id', label:'Przedmiot', sortable: true},
			{name:'assigned_user', label:'Przypisany użytkownik', sortable: true}

        ]

		return { form, columns, modalOpened, modalEditMode, close, store, edit, update, deleteRow, finish, showDetails, booleanIcon, currentDate, checkDateDue }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetButton,
		JetInput,
		JetLabel,
		JetValidationErrors,
		CrudModal,
		ServicesDisplay,
		Checkbox,
		Label
	},

});
</script>