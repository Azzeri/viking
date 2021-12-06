<template>
  <admin-panel-layout title="Menedżer zadań">
    <template #page-title>Menedżer zadań</template>

	<div class="w-full flex space-x-2">

		<div v-for="state in task_states" :key=state.id @drop="onDrop($event, state)" @dragenter.prevent @dragover.prevent class="flex-shrink-0 border w-64 p-2 bg-neutral rounded-lg">
			<div class="flex justify-between items-center text-base-200">
				<h1 class="font-bold capitalize">{{ state.name }}</h1>
				<button @click="createTask(state.id)" for="create-task-modal" class="btn btn-ghost btn-sm modal-button"><i class="fas fa-plus"></i></button>
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
	<input type="checkbox" id="task-details" class="modal-toggle"> 
	<div class="modal">
		<div class="modal-box">

			<!-- Task name and close button -->
			<div class="flex justify-between items-center">
				<div class="flex items-center space-x-2">
					<i class="fas fa-thumbtack"></i>
					<h1 class="font-bold text-lg capitalize">{{ currentTask.name }}</h1>
					<button @click="deleteTask(currentTask)" class="btn btn-error btn-xs">
						<i class="fas fa-times"></i>
						<span class="ml-2">Usuń</span>
					</button>
				</div>
				<label @click="reset" for="task-details" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></label>
			</div>

			<!-- Date due -->
			<div class="flex items-center space-x-2 mt-3">
				<i class="fas fa-calendar-week"></i>
				<h1 class="font-bold">Termin</h1>
			</div>
			<h2 class="ml-6 mt-2">{{ currentTask.date_due ?? 'Nie określono' }}</h2>

			<!-- Description -->
			<div class="flex items-center space-x-2 mt-6">
				<i class="fas fa-align-justify"></i>
				<h1 class="font-bold">Opis</h1>
			</div>
			<p class="ml-6 mt-2">{{ currentTask.description }}</p>

			<!-- Subtask list -->
			<div class="flex items-center space-x-2 mt-6">
				<i class="fas fa-tasks"></i>
				<h1 class="font-bold">Zadania</h1>
				<button @click="createSubTask(currentTask.id)" class="btn btn-ghost btn-xs">
					<i class="fas fa-plus fa-sm cursor-pointer"></i>
				</button>			
			</div>
			<div class="flex mt-2 ml-5">
				<form id="create-subtask-form" class="hidden space-x-3" @submit.prevent=storeSubTask>
					<input v-model="createSubTaskForm.name" type="text" class=" input input-primary input-xs" />
					<input v-model="createSubTaskForm.date_due" type="date" :min=currentDate() class=" input input-primary input-xs" />
					<input type="submit" class=" btn btn-primary btn-xs" value="Dodaj">
				</form>
			</div>
			<div class="mt-2">
				<ul class="menu">
					<li v-for="task in currentTask.subtasks" :key=task.id >
						<a class="flex justify-between space-x-2">
							<div class="flex items-center space-x-2">
								<input @click=finishSubtask(task) :checked=task.is_finished type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
								<span>{{ task.name }}</span>
							</div>
							<span>{{ task.date_due }}</span>
						</a>
					</li>
				</ul>
			</div>

		</div>
	</div>

	<!-- Modal - create -->
	<input type="checkbox" id="create-task-modal" class="modal-toggle"> 
	<div class="modal">
		<div class="modal-box">
			<div class="flex justify-between items-center">
				<div class="flex items-center space-x-2">
					<i class="fas fa-thumbtack"></i>
					<h1 class="font-bold text-lg capitalize">Nowe zadanie</h1>
				</div>
				<label @click="reset" for="create-task-modal" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></label>
			</div>
			<jet-validation-errors v-if="createTaskForm.hasErrors" class="my-6" />
			<form @submit.prevent=storeTask>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=createTaskForm.name type="text" placeholder="Nazwa zadania" class="input input-primary input-bordered">
					
					<label class="label"><span class="label-text">Termin</span></label> 
					<input v-model=createTaskForm.date_due type="date" :min=currentDate() class="input input-primary input-bordered">

					<label class="label">
						<span class="label-text">Opis</span>
					</label> 
					<textarea v-model=createTaskForm.description class="textarea h-24 textarea-bordered textarea-primary" placeholder="Opis..."></textarea>
					
				</div> 
			</form>

			<div class="modal-action">
				<button @click="storeTask()" class="btn">Dodaj</button>
			</div>
		</div>
	</div>

</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({

	props: {
		event: Object,
		tasks: Object,
		task_states: Object
	},

	setup(props) {
		const currentTask = ref(props.tasks[0]) || ref({})

		const createTaskForm = useForm({
			name:null,
			description:null,
			date_due:null,
			event_id:props.event.id,
			event_task_state_id:null,
		})

		const createSubTaskForm = useForm({
			name:null,
			date_due:null,
			event_task_id:null,
		})

		const reset = _ => {
			createTaskForm.reset()
			createTaskForm.clearErrors()
			createSubTaskForm.reset()
			createSubTaskForm.clearErrors()
			document.getElementById('create-subtask-form').classList.add('hidden')
		}

		const showDetails = (row) => {
			currentTask.value = row
			document.getElementById('task-details').checked = true
		}

		const createTask = (id) => {
			createTaskForm.event_task_state_id = id
			document.getElementById('create-task-modal').checked = true
		}

		const storeTask = _ => {
			createTaskForm.post(route('admin.event_tasks.store'), {
				onSuccess: () => {
					reset()
					document.getElementById('create-task-modal').checked = false
				} 

			})
		}

		const deleteTask = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.event_tasks.destroy', row.id), {
				onSuccess: () => {
					reset()
					document.getElementById('task-details').checked = false
				} 
			})
        }

		const createSubTask = (id) => {
			createSubTaskForm.event_task_id = id
			document.getElementById('create-subtask-form').classList.contains('hidden') ? document.getElementById('create-subtask-form').classList.remove('hidden')
			: document.getElementById('create-subtask-form').classList.add('hidden')

			document.getElementById('create-subtask-form').firstChild.focus()
		}

		const storeSubTask = _ => {
			createSubTaskForm.post(route('admin.event_sub_tasks.store'), {
				onSuccess: () => {
					// const id = createSubTaskForm.event_task_id
					reset()
					document.getElementById('task-details').checked = false

					// showDetails(currentTask.value)
					// document.getElementById('create-subtask-form').classList.add('hidden')
				}
			})
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

		return { currentTask, showDetails, createTask, storeTask, deleteTask, createTaskForm, 
				 finishSubtask, createSubTask, createSubTaskForm, storeSubTask,
				 startDrag, onDrop, reset, currentDate,}
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
	},

});
</script>

