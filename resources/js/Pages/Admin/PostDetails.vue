
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

    <div class="flex space-x-3 w-full">
        <button @click="edit" class="btn btn-info">
            <i class="fas fa-edit fa-lg"></i>
            <span class="ml-2">Edytuj post</span>
        </button>
        <button @click="deletePost" class="btn btn-error">
            <i class="fas fa-trash fa-lg"></i>
            <span class="ml-2">Usuń post</span>
        </button>
    </div>

	<CrudModal :show=modalOpened @close=close>
		<template #title>Edycja posta</template>

		<template #content>
			<jet-validation-errors v-if="form.hasErrors" class="my-6" />
			<form @submit.prevent=update>
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
			<button @click=update :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn btn-info">Zapisz</button>
		</template>
	</CrudModal> 
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import CrudModal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		post: Object,
	},

	setup(props) {
		const modalOpened = ref(false)

		const form = useForm({
			title:props.post.title,
			body:props.post.body,
			resource_link:props.post.resource_link,
		})

		const close = _ => { 
			modalOpened.value = false
			form.reset() 
			form.clearErrors()
		}

        const edit = _ => {
            form.title = props.post.title
			form.body = props.post.body
			form.resource_link = props.post.resource_link

            modalOpened.value = true
        }

        const update = _ => form.put(route('admin.posts.update', props.post.id), { onSuccess: () => close() }) 

		const deletePost = _ => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.posts.destroy', props.post.id))
        }

		return { form, modalOpened, close, update, edit, deletePost }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		CrudModal,
	},

});
</script>