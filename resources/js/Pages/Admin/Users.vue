<template>
  <admin-panel-layout title="Użytkownicy">
    <template #page-title>Użytkownicy</template>

    <div class="mx-auto py-4 px-32">
		<div class="p-4 glass-admin-content rounded-3xl mx-auto">
			<DataTable :columns=columns :data=users :filters=filters @edit=edit>
				<template #buttons>
            		<button @click="modalOpened = true" class="p-3 rounded-full border-2">Nowy</button>
				</template>
			</DataTable>
		</div>
    </div>

	<CrudModal :show=modalOpened @close=close>
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
	</CrudModal>
	
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

export default defineComponent({

	props: {
		users: Object,
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
			email: null,
			role: null
		})

		const formEdit = useForm({
			id: null,
			name: null,
			surname: null,
			nickname: null,
			role: null,
			date_birth: null,
			email: null
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

		const generateLink = _ => { 
			form.post('generateRegistrationLink', {
				onSuccess: () => close()
			}) 
		}

		const update = () => { 
			formEdit.put('users/'+formEdit.id, {
				onSuccess: () => close()
			}) 
		}

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const columns = [
			// {name:'id', label:'Id', searchable: true, sortable: true},
			{name:'name', label:'Imię', searchable: true, sortable: true},
			{name:'surname', label:'Nazwisko', searchable: true, sortable: true},
			{name:'nickname', label:'Nick', searchable: true, sortable: true},
			{name:'email', label:'Email', searchable: true, sortable: true},
			{name:'date_birth', label:'Data urodzenia', searchable: true, sortable: true},
			{name:'role', label:'Rola', searchable: true, sortable: true},
			{name:'privilege_id', label:'Uprawnienie', sortable: true},
			// {name:'description', label:'Opis', sortable: true},
		]

		return { form, formEdit, columns, modalOpened, modalEditMode, close, generateLink, edit, update, currentDate }
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