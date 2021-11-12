<template>

  <admin-panel-layout v-if="!items.data.length" title="Sprzęt">
	<template #page-title>Sprzęt</template>
	<div class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
        <h1>Brak danych</h1>
        	<button @click="modalOpened = true" class="p-3 rounded-full border-2">Dodaj przedmiot</button>
            <Link as=button :href="route('admin.inventory.category.index')">Zobacz kategorie</Link>
    </div>
  </admin-panel-layout>

  <admin-panel-layout v-else title="Kategorie sprzętu">
    <template #page-title>Sprzęt</template>
    <div class="mx-auto py-4 px-32">
		<div class="p-4 glass-admin-content rounded-3xl mx-auto">
			<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.inventory.items.index" @edit=edit>
				<template #buttons>
            		<button @click="modalOpened = true" class="p-3 rounded-full border-2">Dodaj przedmiot</button>
                    <Link as=button :href="route('admin.inventory.category.index')">Zobacz kategorie</Link>
				</template>
                <template #content>
                    <tr v-for="row in items.data" :key="row">

                        <td class="px-6 py-2"><img class="w-14 h-14" :src=row.photo_path :alt=row.name></td>
                        <td class="px-6 py-2">{{ row.category_name }}</td>
                        <td class="px-6 py-2">{{ row.name }}</td>
                        <td class="px-6 py-2">{{ row.quantity }}</td>

                        <td class="flex justify-evenly px-6 py-2">
                            <i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
                            <i @click="deleteRow(row)" class="fas fa-trash cursor-pointer"></i>
                        </td>
                    </tr>
                </template>
			</DataTable>
		</div>
    </div>
</admin-panel-layout>

<CrudModal :show=modalOpened @close=close>
	<template #title>Nowy kategoria sprzętu</template>

	<template #content>
		<jet-validation-errors class="my-6" />
		<form @submit.prevent="store, update">

			<div class="mt-6">
				<jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus placeholder="Nazwa" autocomplete="name" />
			</div>
            <div class="mt-6">
				<jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" required autofocus placeholder="Opis" autocomplete="description" />
			</div>
            <div class="mt-6" v-if="modalEditMode">
				<jet-input id="description" type="number" class="mt-1 block w-full" v-model="form.quantity" required autofocus placeholder="Ilość" autocomplete="quantity" />
			</div>
			<div class="mt-6">
				<select v-model=form.inventory_category_id>
					<template v-for="row in categories" :key=row>
						<option :value="row.id"> {{ row.name }} </option>
					</template>
				</select>
					<span>Selected: {{ form.inventory_category_id }}</span>
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
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({

	props: {
		categories: Object,
        items: Object,
		filters: Object
	},

	setup(props) {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
            id: null,
			name: null,
			photo: null,
            description: null,
            quantity: null,
            inventory_category_id: props.categories[0].id
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
			form.inventory_category_id = row.inventory_category_id
            form.quantity = row.quantity

			modalOpened.value = true 
		}

        const store = _ => { 
			form.post('inventoryitems/', {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put('inventoryitems/' + form.id, {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie konserwacje związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete('inventoryitems/' + row.id)
        }

		const columns = [
			{name:'photo_path', label:'Zdjęcie'},
            {name:'inventory_category_id', label:'Kategoria', sortable: true},
			{name:'name', label:'Nazwa', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true}
        ]

		return { form, columns, modalOpened, modalEditMode, close, store, edit, update, deleteRow }
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