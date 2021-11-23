<template>
	<admin-panel-layout title="Serwisy">
	
		<template #page-title>Serwisy</template>
		
		<div v-if="!requests.data.length && filters.search == null && filters.filter == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
			<h1>Brak danych</h1>
			<div class="flex space-x-2">
				<Link as=button :href="route('admin.store.items.index')" class="sm:flex bg-white items-center bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
					<i class="fas fa-arrow-left fa-lg"></i>
				</Link>
			</div>
		</div>

		<div v-else>
			<ServicesDisplay :columns=columns :links=requests.links :filters=filters :frontFilters=frontFilters sortRoute="admin.store.requests.index">

				<template #buttons>
					<Link as=button :href="route('admin.store.items.index')" class="lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-arrow-left fa-lg"></i>
					</Link>
				</template>

				<template #content>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2 my-4">
                        <div v-for="row in requests.data" :key="row" class="rounded-lg bg-white p-3 flex flex-col justify-between text-justify overflow-hidden">
                            <div>
                                <div class="flex justify-between">
                                    <div class="font-bold text-lg">{{row.store_item_name}}</div>
                                    <!-- <div><i @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i></div> -->
                                </div>
                                <div class="my-2 text-gray-500 italic">
                                    <div class="flex space-x-2 items-center">
                                        <i class="fas fa-user cursor-pointer"></i>
                                        <div>{{ row.client_name }}</div>
                                    </div>
                                    <div class="flex space-x-2 items-center">
                                        <i class="fas fa-phone cursor-pointer"></i>
                                        <div v-if="row.client_phone">{{ row.client_phone }}</div>
                                        <div v-else>Nie podano</div>
                                    </div>
                                    <div class="flex space-x-2 items-center">
                                        <i class="fas fa-envelope cursor-pointer"></i>
                                        <div>{{ row.client_email }}</div>
                                    </div>
                                </div>
                                <div>{{ row.description }}</div>
                            </div>
                            
                            <div v-if="!row.is_finished" class="mt-4">
                                <div v-if="!row.is_accepted" class="flex justify-between space-x-2">
                                    <button @click=acceptRow(row) class="w-1/2 px-3 py-1 bg-green-500 text-white font-bold rounded-full">Zaakceptuj</button>
                                    <button @click=deleteRow(row) class="w-1/2 px-3 py-1 bg-red-500 text-white font-bold rounded-full">Odrzuć</button>
                                </div>
                                <div v-else>
                                    <button @click=finishRow(row) class="w-1/2 px-3 py-1 bg-blue-500 text-white font-bold rounded-full">Zakończ</button>
                                </div>
                            </div>

					    </div>
                    </div>
					
				</template>
				
			</ServicesDisplay>
		</div>

	</admin-panel-layout>

	<!-- <CrudModal :show=modalOpened @close=close>
		<template #title>Nowy serwis</template>

		<template #content>
			<jet-validation-errors class="my-6" />
			<form @submit.prevent="store, update">

				<div class="mt-6">
					<label for=item>Przedmiot</label>
					<select id=item class="w-full rounded-lg" v-model=form.store_item_id>
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
	</CrudModal> -->
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
		requests: Object,
		filters: Object
	},

	setup(props) {
		// const modalOpened = ref(false)
		// const modalEditMode = ref(false)

		// const currentDate = _ => new Date().toISOString().split('T')[0]

		// const finish = (row) => {
        //     if (!confirm('Czy potwierdzasz wykonanie serwisu?')) return;
        //     Inertia.post('storerequestsfinish/', row)
        // }

		// const showDetails = (id) => {
		// 	let element = document.getElementById('details-'+id)
		// 	let arrow = document.getElementById('arrow-'+id)

		// 	element.classList.contains('hidden') ? element.classList.remove('hidden') : element.classList.add('hidden')
		// 	arrow.innerHTML.indexOf('down') != -1 ? arrow.innerHTML = '<i class="fas fa-arrow-up"></i>' : arrow.innerHTML = '<i class="fas fa-arrow-down"></i>'
		// }

        // const booleanIcon = (notification) => notification == true ? '<i class="fas fa-check text-green-500">' : '<i class="fas fa-times text-red-500">'

		// const checkDateDue = (date_due) => {
		// 	if(date_due < currentDate()) return 'text-red-500';
		// 	else if (date_due == currentDate()) return 'text-yellow-500';
		// 	else return 'text-green-500'
		// }

		// const form = useForm({
        //     id: null,
		// 	name: null,
        //     description: null,
		// 	date_due: null,
		// 	store_item_id: props.items.length ? props.items[0].id : 0,
		// 	assigned_user: props.users.length ? props.users[0].id : 0,
		// 	notification: false
		// })

		// const reset = _ => { 
		// 	form.reset()
		// 	modalEditMode.value = false 
		// }

		// const close = _ => { 
		// 	modalOpened.value = false
		// 	reset() 
		// }

		// const edit = (row) => { 
		// 	modalEditMode.value = true

		// 	form.id = row.id
		// 	form.name = row.name
        //     form.description = row.description
		// 	form.date_due = row.date_due
        //     form.store_item_id = row.store_item_id
        //     form.assigned_user = row.assigned_user_id
        //     form.notification = row.notification

		// 	modalOpened.value = true 
		// }

        // const store = _ => { 
		// 	form.post('storerequests/', {
		// 		onSuccess: () => close()
		// 	}) 
		// }

		// const update = _ => { 
		// 	form.put('storerequests/' + form.id, {
		// 		onSuccess: () => close()
		// 	}) 
		// }

        const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete('storerequests/' + row.id)
        }

        const acceptRow = (row) => {
            Inertia.post('storeRequests/accept/' + row.id)
        }

        const finishRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.post('storeRequests/finish/' + row.id)
        }

		const columns = [
			{ name:'store_item_id', label:'Przedmiot', sortable: true },
			{ name:'description', label:'Opis'},
			{ name:'created_at', label:'Data utworzenia', sortable: true },
			{ name:'client_name', label:'Klient', sortable: true },
			{ name:'client_phone', label:'Telefon klienta', sortable: true },
			{ name:'client_email', label:'Email klienta', sortable: true },
			{ name:'is_finished', label:'Zakończone'},
			{ name:'is_accepted', label:'Zaakceptowane'},
        ]

        const frontFilters = [
			{ label: 'Oczekujące', value: 0 },
			{ label: 'Zaakceptowane', value: 1 },
			{ label: 'Wykonane', value: 2 },
		]

		return { columns, frontFilters, deleteRow, acceptRow, finishRow }

		// return { form, columns, modalOpened, modalEditMode, close, store, edit, update, deleteRow, finish, showDetails, booleanIcon, currentDate, checkDateDue }
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