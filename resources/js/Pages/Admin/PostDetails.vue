
<template>
  <admin-panel-layout title="Szczegóły postu">

    <div class="card lg:card-side bordered w-full">
        <figure>
            <img :src=post.photo_path :alt="post.title" class="block h-64 w-full object-fill">
        </figure> 
        <div class="card-body pt-2">
            <h2 class="card-title">{{ post.title }}</h2> 
            <p>{{ post.body }}</p>
        </div>
    </div> 

<!-- 
	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowe wydarzenie</template>

		<template #content>
			<jet-validation-errors v-if="form.hasErrors" class="my-6" />
			<form @submit.prevent=store>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Tytuł<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.title type="text" placeholder="Tytuł posta" class="input input-primary input-bordered">

					<label class="label mt-4">
						<span class="label-text">Treść<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.body class="textarea h-24 textarea-bordered textarea-primary" placeholder="Treść......"></textarea>
                    
				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn btn-info">Dodaj</button>
		</template>
	</CrudModal>  -->
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import CrudModal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		post: Object,
	},

	setup() {
		const modalOpened = ref(false)

		const form = useForm({
			title:null,
			body:null,
			resource_link:null,
		})

		const close = _ => { 
			modalOpened.value = false
			form.reset() 
			form.clearErrors()
		}

		return { form, modalOpened, close }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		CrudModal,
	},

});
</script>