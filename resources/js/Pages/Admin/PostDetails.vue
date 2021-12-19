<template>
  <admin-panel-layout title="Szczegóły postu">
	
	<div class="flex space-x-2 place-self-start">
		<Link :href="route('admin.posts.index')" class="btn btn-primary btn-sm ">
			<i class="fas fa-arrow-left mr-2"></i>
			Powrót
		</Link>
		<button @click="modalOpened = true" class="btn btn-info btn-sm">
			<i class="fas fa-edit mr-2"></i>
			Edytuj
		</button>
		<button @click=deletePost class="btn btn-error btn-sm">
			<i class="fas fa-trash mr-2"></i>
			Usuń
		</button>
	</div>
	
	<div class="card bordered shadow-lg max-w-3xl">
		<figure>
			<img :src=post.photo_path class="">
		</figure> 
		<div class="card-body">
			<div class="card-title">
				<span>{{ post.title }}</span>
				<h2 class="text-gray-500 text-base">
					<div>{{ `${post.user.name} ${post.user.nickname ? `"${post.user.nickname}"` : ''} ${post.user.surname}` }}</div> 
					<div class="text-sm">{{ `${post.date_created} ${post.time_created}` }}</div>
				</h2>
			</div>
			<p>{{ post.body }}</p>
			<div class="card-actions">
				<button v-if="post.resource_link" class="btn btn-sm btn-secondary">Szczegóły</button>
			</div>
		</div>
	</div> 

	<Modal :show=modalOpened @close=close :id="'modal'">
		<template #side><h1 class="text-lg font-semibold">Edycja postu</h1></template>

		<template #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Tytuł<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.title type="text" placeholder="Tytuł posta" class="input input-primary input-bordered">

					<label class="label mt-4">
						<span class="label-text">Treść<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.body class="textarea h-64 textarea-bordered textarea-primary" placeholder="Treść......"></textarea>
				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=update :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn btn-info">Zapisz</button>
		</template>
	</Modal> 
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from '@/Components/CrudModal.vue'

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

        const update = _ => form.put(route('admin.posts.update', props.post.id), { onSuccess: () => close() }) 

		const deletePost = _ => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.posts.destroy', props.post.id))
        }

		return { form, modalOpened, close, update, deletePost }
	},

	components: {
		AdminPanelLayout,
		Link,
		Modal,
	},

});
</script>