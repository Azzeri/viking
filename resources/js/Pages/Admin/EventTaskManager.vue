<template>
  <admin-panel-layout title="Menedżer zadań">

	<!-- Name and return -->
	<div class="flex space-x-2 self-start items-center">
		<Link :href="route('admin.events.show', event)" as="button" class="btn btn-sm btn-primary">
			<i class="fas fa-arrow-left mr-1"></i>
			Powrót
		</Link>
		<h1 class="text-2xl font-bold">{{ event.name }}</h1>
	</div>

	<!-- Task states -->
	<div class="flex space-x-2">
		<div v-for="state in task_states" :key=state.id @drop="onDrop($event, state)" @dragenter.prevent @dragover.prevent 
		     class="w-60 p-2 bg-neutral rounded-lg overflow-y-auto" style="max-height: 80vh;">
			<div class="flex justify-between items-center text-base-200">
				<h1 class="font-bold capitalize">{{ state.name }}</h1>
				<button @click="createTask(state.id)" class="btn btn-ghost btn-sm"><i class="fas fa-plus"></i></button>
			</div>
			<div class="mt-4 space-y-3">
				<template v-for="task in tasks" :key="task.id">
					<div @click=showDetails(task) v-if="task.event_task_state_id == state.id" draggable=true @dragstart="startDrag($event, task)"
					class="p-2 border bg-base-200 rounded-sm hover:bg-base-300 hover:cursor-pointer">
						<h1>{{ task.name }}</h1>					
					</div>
				</template>
			</div>
		</div>
	</div>
  </admin-panel-layout>

	<!-- Modal - details -->
	<Modal :show="detailsModalOpened" @close=close id="modal-details">
		<template #side>
			<!-- Buttons -->
			<button @click="deleteTask(selectedTask)" class="btn btn-error btn-xs">
				<i class="fas fa-times"></i>
				<span class="ml-2">Usuń</span>
			</button>
			<button @click="editTask(selectedTask)" :class="{'btn-info':!taskEditMode, 'btn-error':taskEditMode}" class="btn btn-xs ml-2">
				<i :class="{'fas fa-edit':!taskEditMode, 'fas fa-times':taskEditMode}"></i>
				<span v-html="taskEditMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
			</button>
		</template>

		<template #content>
			<form @submit.prevent=updateTask(selectedTask.id)>
				<!-- Task name -->
				<div class="flex justify-between items-center mt-6">
					<div class="flex items-center space-x-2">
						<i class="fas fa-thumbtack"></i>
						<h1 v-if="!taskEditMode" class="font-bold text-lg capitalize">{{ selectedTask.name }}</h1>
						<input v-else v-model="taskForm.name" type="text" class="input input-primary input-sm" />
					</div>
				</div>

				<!-- Date due -->
				<div class="flex items-center space-x-2 mt-3">
					<i class="fas fa-calendar-week"></i>
					<h1 class="font-bold">Termin</h1>
				</div>
				<h2 v-if="!taskEditMode" class="ml-6 mt-2">{{ selectedTask.date_due ?? 'Nie określono' }}</h2>
				<input v-else v-model="taskForm.date_due" type="date" class="input input-primary input-sm ml-6" />

				<!-- Description -->
				<div class="flex items-center space-x-2 mt-6">
					<i class="fas fa-align-justify"></i>
					<h1 class="font-bold">Opis</h1>
				</div>
				<p v-if="!taskEditMode" class="ml-6 mt-2">{{ selectedTask.description }}</p>
				<textarea v-else class="ml-6 textarea w-full h-32 textarea-primary" v-model="taskForm.description"></textarea>
				<button v-if=taskEditMode class="ml-6 btn btn-primary btn-sm">Zapisz</button>
			</form>

			<!-- Subtask list -->
			<div class="flex items-center space-x-2 mt-6">
				<i class="fas fa-tasks"></i>
				<h1 class="font-bold">Zadania</h1>
				<button @click="createSubTask(selectedTask.id)" class="btn btn-ghost btn-xs">
					<i class="fas fa-plus fa-sm cursor-pointer"></i>
				</button>			
			</div>
			<div class="flex mt-2 ml-5">
				<form id="create-subtask-form" class="hidden space-x-3" @submit.prevent=storeSubTask>
					<input v-model="subTaskForm.name" type="text" class=" input input-primary input-xs" />
					<input v-model="subTaskForm.date_due" type="date" :min=currentDate() class=" input input-primary input-xs" />
					<input type="submit" class=" btn btn-primary btn-xs" value="Dodaj">
				</form>
			</div>
			<div class="mt-2">
				<ul class="menu">
					<li v-for="task in selectedTask.subtasks" :key=task.id >
						<a>
							<div :id="'subtask-name-'+task.id" class="flex w-full justify-between">
								<div class="flex items-center space-x-2">
									<input @click=finishSubtask(task) :checked=task.is_finished type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
									<span @click="toggleSubTaskEditMode(task)" :class="{ 'text-gray-600 line-through':task.is_finished }">{{ task.name }}</span>
								</div>
								<span>{{ task.date_due }}</span>
							</div>

							<div :id="'subtask-form-'+task.id" class="hidden w-full flex flex-col space-y-2">
								<div>
									<form @submit.prevent="updateSubTask(task.id)" class="flex justify-between w-full space-x-2">
										<input v-model="subTaskForm.name" :id="'subtask-input-'+task.id" type="text" class="input input-primary input-xs w-3/4">
										<input v-model="subTaskForm.date_due" type="date" class="input input-primary input-xs">
									</form>
								</div>
								<div class="flex space-x-3">
									<button @click="updateSubTask(task.id)" class="btn btn-primary btn-xs">Zapisz</button>
									<button @click=deleteSubTask(task) class="btn btn-error btn-xs">Usuń</button>
									<button @click="toggleSubTaskEditMode(task, true)" class="btn btn-info btn-xs">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</template>
	</Modal>

	<!-- Modal - create -->
	<Modal :show="createModalOpened" @close=close id="modal-create">
		<template #side>
			<div class="flex items-center space-x-2">
				<i class="fas fa-thumbtack"></i>
				<h1 class="font-bold text-lg capitalize">Nowe zadanie</h1>
			</div>
		</template>

		<template #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=taskForm.name type="text" placeholder="Nazwa zadania" class="input input-primary input-bordered">
					
					<label class="label"><span class="label-text">Termin</span></label> 
					<input v-model=taskForm.date_due type="date" :min=currentDate() class="input input-primary input-bordered">

					<label class="label">
						<span class="label-text">Opis</span>
					</label> 
					<textarea v-model=taskForm.description class="textarea h-24 textarea-bordered textarea-primary" placeholder="Opis..."></textarea>
				</div> 
			</form>
		</template>

		<template #footer>
			<button @click="storeTask()" class="btn w-full">Dodaj</button>
		</template>
	</Modal>

</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from "@inertiajs/inertia";
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		event: Object,
		tasks: Object,
		task_states: Object
	},

	setup(props) {
		const detailsModalOpened = ref(false)
		const createModalOpened = ref(false)

		const selectedTask = ref({'name':''})
		const taskEditMode = ref(false)

		const taskForm = useForm({
			name:null,
			description:null,
			date_due:null,
			event_id:props.event.id,
			event_task_state_id:null,
		})

		const subTaskForm = useForm({
			name:null,
			date_due:null,
			event_task_id:null,
		})

		const close = _ => {
			detailsModalOpened.value = false
			createModalOpened.value = false

			reset()
		}

		const reset = _ => {
			taskForm.reset()
			taskForm.clearErrors()
			subTaskForm.reset()
			subTaskForm.clearErrors()

			taskEditMode.value = false
		}

		const showDetails = (row) => {
			selectedTask.value = row
			detailsModalOpened.value = true
		}

		const createTask = (id) => {
			taskForm.event_task_state_id = id
			createModalOpened.value = true
		}


		const editTask = (row) => {
			if (taskEditMode.value == false) {
				taskForm.name = row.name
				taskForm.description = row.description
				taskForm.date_due = row.date_due

				taskEditMode.value = true
			}
			else reset()
		}



		const storeTask = _ => {
			taskForm.post(route('admin.event_tasks.store'), {
				onSuccess: () => {
					close()
				} 

			})
		}

		const deleteTask = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.event_tasks.destroy', row.id), {
				onSuccess: () => {
					close()
				} 
			})
        }

		const updateTask = (id) => {
			taskForm.put(route('admin.event_tasks.update', id), {
				onSuccess: () =>{
					reset()
					selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
				} 
			}) 
		}





		const createSubTask = (id) => {
			toggleSubTaskEditMode({}, true)
			subTaskForm.name = null,
			subTaskForm.date_due = null,
			subTaskForm.vent_task_id = null,
			subTaskForm.event_task_id = id
			document.getElementById('create-subtask-form').classList.contains('hidden') ? document.getElementById('create-subtask-form').classList.remove('hidden')
			: document.getElementById('create-subtask-form').classList.add('hidden')

			document.getElementById('create-subtask-form').firstChild.focus()
		}

		const storeSubTask = _ => {
			subTaskForm.post(route('admin.event_sub_tasks.store'), {
				onSuccess: () => {
					// const id = subTaskForm.event_task_id
					reset()
					document.getElementById('task-details').checked = false

					// showDetails(selectedTask.value)
					// document.getElementById('create-subtask-form').classList.add('hidden')
				}
			})
		}

		const updateSubTask = (id) => { 
			subTaskForm.put(route('admin.event_sub_tasks.update', id), {
				onSuccess: () => {toggleSubTaskEditMode({},true)}
			}) 
		}

		const deleteSubTask = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.event_sub_tasks.destroy', row.id), {
				onSuccess: () => {

				} 
			})
        }

		const toggleSubTaskEditMode = (task, close_all) => {
			let elements = document.querySelectorAll('*[id^="subtask-form-"]')
			elements.forEach((element) => element.classList.add('hidden'))

			elements = document.querySelectorAll('*[id^="subtask-name-"]')
			elements.forEach((element) => element.classList.remove('hidden'))

			document.getElementById('create-subtask-form').classList.add('hidden')

			if(!close_all) {
				document.getElementById('subtask-name-'+task.id).classList.contains('hidden') ? 
				document.getElementById('subtask-name-'+task.id).classList.remove('hidden') : 
				document.getElementById('subtask-name-'+task.id).classList.add('hidden')

				document.getElementById('subtask-form-'+task.id).classList.contains('hidden') ? 
				document.getElementById('subtask-form-'+task.id).classList.remove('hidden') : 
				document.getElementById('subtask-form-'+task.id).classList.add('hidden')

				subTaskForm.name = task.name
				subTaskForm.date_due = task.date_due
				subTaskForm.event_task_id = task.event_task_id

				document.getElementById('subtask-input-'+task.id).focus()
			}
		}

		const finishSubtask = (row) => {
			Inertia.put(route('admin.event_sub_tasks.finish', row.id))
		}

		const startDrag = (event, item) => {
			event.dataTransfer.dropEffect = 'move'
			event.dataTransfer.effectAllowed = 'move'
			event.dataTransfer.setData('ItemID', item.id)
		}

		const onDrop = (event, state) => {
			const itemID = event.dataTransfer.getData('itemID')
			Inertia.put(route('admin.event_tasks.change_state', [itemID, state]))
		}

		const currentDate = _ => new Date().toISOString().split('T')[0]

		return { selectedTask, showDetails, createTask, storeTask, deleteTask, taskForm, taskEditMode, editTask, updateTask,
				 finishSubtask, createSubTask, subTaskForm, storeSubTask, deleteSubTask, toggleSubTaskEditMode, updateSubTask,
				 startDrag, onDrop, reset, currentDate, detailsModalOpened, createModalOpened, close}
	},

	components: {
		AdminPanelLayout,
		Link,
		Modal
	},

});
</script>

