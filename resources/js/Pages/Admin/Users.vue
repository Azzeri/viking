<template>
  <admin-panel-layout title="Użytkownicy">

    <div class="mx-auto max-w-6xl text-center">
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
		<DataTable :columns=columns :data=users :filters=filters />
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
			{name:'id', label:'Id', searchable: true, filterable: true},
			{name:'name', label:'Imię', searchable: true, filterable: true},
			{name:'surname', label:'Nazwisko', searchable: true, filterable: true},
			{name:'nickname', label:'Nick', searchable: true, filterable: true},
			{name:'email', label:'Email', searchable: true, filterable: true},
			{name:'date_birth', label:'Data urodzenia', searchable: true, filterable: true},
			{name:'role', label:'Rola', searchable: true, filterable: true},
			{name:'privilege_id', label:'Uprawnienie', filterable: true},
			{name:'description', label:'Opis', filterable: true},
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