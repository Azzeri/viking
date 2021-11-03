<template>
  <admin-panel-layout title="Użytkownicy">
    <template #page-title>Użytkownicy</template>
    <div class="mx-auto py-4 px-32">
		<jet-validation-errors class="my-6" />

		<!-- <form @submit.prevent="form.post('generateRegistrationLink')">
			<div class="mt-4">
				<jet-label for="email" value="Email" />
				<jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required placeholder="Wprowadź email" />
			</div>

			<div class="mt-4">
				<jet-label for="role" value="Rola w grupie" />
				<jet-input id="role" type="text" class="mt-1 block w-full" v-model="form.role" required placeholder="Wprowadź rolę" />
			</div>

			<div class="flex items-center justify-end mt-4">
				<jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Wygeneruj link do rejestracji
				</jet-button>
			</div>
		</form> -->
		<div class="p-4 glass-admin-content rounded-3xl mx-auto">
			<DataTable :columns=columns :data=users :filters=filters>
				<template #buttons>
            		<button class="p-3 rounded-full border-2">Nowy</button>
				</template>
			</DataTable>
		</div>

    </div>
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'

export default defineComponent({

	props: {
		users: Object,
		filters: Object
	},

	setup() {
		const form = useForm({
			email: null,
			role: null
		})

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

		return { form, columns }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetButton,
		JetInput,
		JetLabel,
		JetValidationErrors,
		DataTable
	},

});
</script>