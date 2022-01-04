<template>
	<admin-panel-layout title="Menedżer zadań" contentMaxWidth="max-w-7xl">

		<!-- Name and return button -->
		<div class="flex space-x-2 self-start items-center my-4">
			<Link :href="route('admin.events.show', event)" as="button" class="btn btn-sm btn-primary">
				<i class="fas fa-arrow-left mr-1"></i>
				Powrót
			</Link>
			<h1 class="text-2xl font-bold">{{ event.name }}</h1>
		</div>

		<!-- Task states -->
		<div class="flex space-x-2">
			<div v-for="state in task_states" :key=state.id @drop="onDrop($event, state)" @dragenter.prevent @dragover.prevent 
				class="w-64 p-2 bg-neutral rounded-lg overflow-y-auto flex-shrink-0" style="max-height: 80vh;">
				<div class="flex justify-between items-center text-base-200">
					<h1 class="font-bold capitalize">{{ state.name }}</h1>
					<button v-if="!event.is_finished" @click="createTask(state.id)" class="btn btn-ghost btn-sm"><i class="fas fa-plus"></i></button>
				</div>
				<div class="mt-4 space-y-3">
					<template v-for="task in tasks" :key="task.id">
						<div v-if="task.event_task_state_id == state.id" @click=showTask(task) draggable=true @dragstart="startDrag($event, task)"
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
			<template v-if="!event.is_finished">
				<button @click="deleteTask(selectedTask.id)" class="btn btn-error btn-xs">
					<i class="fas fa-times"></i>
					<span class="ml-2">Usuń</span>
				</button>
				<button v-if="!taskEditMode" @click="editTask(true)" class="btn btn-info btn-xs ml-2">
					<i class="fas fa-edit"></i>
					<span class="ml-2">Edytuj</span>
				</button>
				<button v-else @click="reset()" class="btn btn-error btn-xs ml-2">
					<i class="fas fa-times"></i>
					<span class="ml-2">Anuluj</span>
				</button>
				<button @click="$refs.updateTaskSubmit.click()" v-if=taskEditMode :disabled="taskForm.processing" :class="{ 'loading': taskForm.processing }" class="btn btn-success btn-xs ml-2">Zapisz</button>
			</template>
		</template>

		<template #content>
			<!-- Update Task Form and info-->
			<form @submit.prevent="updateTask(selectedTask.id)">
				<!-- Task name -->
				<div class="flex justify-between items-center mt-6">
					<div class="flex items-center space-x-2 w-full">
						<i class="fas fa-thumbtack"></i>
						<h1 v-if="!taskEditMode" class="font-bold text-lg capitalize">{{ selectedTask.name }}</h1>
						<form-input-field v-else id="focus-update-task" type="text" name="Nazwa" :required="true" model="name" :form="taskForm" max="128" min="3" extraClass="input-sm w-full max-w-sm" :label=false></form-input-field>
					</div>
				</div>

				<!-- Date due -->
				<div class="flex items-center space-x-2 mt-3">
					<i class="fas fa-calendar-week"></i>
					<h1 class="font-bold">Termin</h1>
				</div>
				<h2 v-if="!taskEditMode" class="ml-6 mt-2">{{ selectedTask.date_due ? selectedTask.date_due_formatted : 'Nie określono' }}</h2>
				<form-input-field v-else type="date" name="Termin" :required="false" model="date_due" :form="taskForm" :min="currentDate()" extraClass="input-sm ml-5 w-full max-w-sm" :label=false></form-input-field>

				<!-- Description -->
				<div class="flex items-center space-x-2 mt-6">
					<i class="fas fa-align-justify"></i>
					<h1 class="font-bold">Opis</h1>
				</div>
				<p v-if="!taskEditMode" class="ml-6 mt-2">{{ selectedTask.description ?? 'Brak opisu' }}</p>
				<textarea v-else class="ml-6 textarea w-full h-32 textarea-primary resize-none max-w-sm" v-model="taskForm.description"></textarea>
				<label v-if="taskForm.errors.description" class="label label-text-alt text-error text-sm">{{ taskForm.errors.description }}</label>
				<input type="submit" ref="updateTaskSubmit" class="hidden" />
			</form>

			<!-- Subtasks -->
			<div class="flex items-center space-x-2 mt-6">
				<i class="fas fa-tasks"></i>
				<h1 class="font-bold">Zadania</h1>
				<template v-if="!event.is_finished">
					<button v-if="!subTaskCreateMode" @click="createSubTask(true)" class="btn btn-ghost btn-xs">
						<i class="fas fa-plus fa-sm cursor-pointer"></i>
					</button>	
					<button v-else @click="createSubTask(false)" class="btn btn-ghost btn-xs">
						<i class="fas fa-times fa-sm cursor-pointer"></i>
					</button>	
				</template>
				<label v-if="subTaskForm.errors.name" class="label label-text-alt text-error text-sm">{{ subTaskForm.errors.name }}</label>
			</div>

			<!-- Create subtask form -->
			<div class="mt-2 mx-5">
				<form v-show="subTaskCreateMode && !event.is_finished" id="create-subtask-form" class="flex flex-col sm:flex-row gap-2 w-full" @submit.prevent=storeSubTask>
					<input id="focus-create-subtask" v-model="subTaskForm.name" type="text" class="input input-primary input-xs" required minlength="1" maxlength="128"/>
					<input v-model="subTaskForm.date_due" type="date" :min=currentDate() class="input input-primary input-xs" />
					<input type="submit" class="btn btn-primary btn-xs" value="Dodaj">
				</form>
			</div>

			<!-- List -->
			<div class="mt-2">
				<ul class="menu max-h-64 overflow-y-auto">
					<li v-for="task in selectedTask.subtasks" :key=task.id class="hover-bordered" @click="editSubTask(task)">
						<a class="flex flex-col space-y-2">
							<template v-if="!subTaskEditMode">
								<div class="flex items-center space-x-2 w-full">
									<input @click.stop @click=finishSubtask(task) :checked=task.is_finished :disabled="event.is_finished" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
									<span :class="{ 'text-gray-600 line-through':task.is_finished }">{{ task.name }}</span>
								</div>
								<div v-if="task.date_due" class="self-start"><i class="fas fa-calendar-week ml-7 mr-1" ></i>{{ task.date_due_formatted }}</div>
							</template>
							<div v-if="subTaskEditMode && subTaskIndex == task.id" class="w-full flex flex-col space-y-2" @click.stop>
								<form @submit.prevent="updateSubTask(task.id)" class="flex flex-col gap-2 w-full">
									<input id="focus-edit-subtask" v-model="subTaskForm.name" type="text" class="input input-primary input-xs">
									<input v-model="subTaskForm.date_due" type="date" :min="currentDate()" class="input input-primary input-xs w-full">
									<input type="submit" ref="updateSubtaskSubmit" class="hidden"/>
								</form>
								<div class="flex space-x-2">
									<button @click="$refs.updateSubtaskSubmit.click()" class="btn btn-success btn-xs" :disabled="subTaskForm.processing" :class="{ 'loading': subTaskForm.processing }">Zapisz</button>
									<button @click=deleteSubTask(task) class="btn btn-error btn-xs">Usuń</button>
									<button @click="reset()" class="btn btn-error btn-xs">Anuluj</button>
								</div>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</template>
	</Modal>

	<!-- Modal - create -->
	<Modal :show="createModalOpened && !event.is_finished" @close=close id="modal-create" maxWidth="md:max-w-sm">
		<template #side>
			<div class="flex items-center space-x-2">
				<i class="fas fa-thumbtack"></i>
				<h1 class="font-bold text-lg capitalize">Nowe zadanie</h1>
			</div>
		</template>

		<template #content>
			<form @submit.prevent="storeTask">
				<div class="form-control mt-4">
					<form-input-field id="focus-create-task" type="text" name="Nazwa" :required="true" model="name" :form="taskForm" max="128" min="3"></form-input-field>
					<form-input-field type="date" name="Termin" :required="false" model="date_due" :form="taskForm" :min="currentDate()" extraClass="w-full"></form-input-field>

					<label class="label"><span class="label-text">Opis</span></label> 
					<textarea v-model=taskForm.description class="textarea h-44 textarea-bordered textarea-primary resize-none" placeholder="Opis..." minlength="3" maxlength="255"></textarea>
					<label v-if="taskForm.errors.description" class="label label-text-alt text-error text-sm">{{ taskForm.errors.description }}</label>
				</div> 
				<input type="submit" ref="storeTaskSubmit" class="hidden" />
			</form>
		</template>

		<template #footer>
			<button @click="$refs.storeTaskSubmit.click()" :disabled="taskForm.processing" :class="{ 'loading': taskForm.processing }" class="btn btn-primary w-full">Dodaj</button>
		</template>
	</Modal>

</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from "@inertiajs/inertia";
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from '@/Components/CrudModal.vue'
import FormInputField from "@/Components/FormInputField.vue";

export default defineComponent({

	props: {
		event: Object,
		tasks: Object,
		task_states: Object
	},

	setup(props) {
		// Modals visibility
		const detailsModalOpened = ref(false)
		const createModalOpened = ref(false)

		// Task for details modal and subTask to edit
		const selectedTask = ref({'name':''})
		const subTaskIndex = ref(0)

		// Modal modes
		const taskEditMode = ref(false)
		const subTaskEditMode = ref(false)
		const subTaskCreateMode = ref(false)

		// Forms
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

		// Close and reset functions
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
			subTaskEditMode.value = false
			subTaskCreateMode.value = false

			subTaskIndex.value = 0
		}

		// EventTask CRUD
		const showTask = (row) => {
			selectedTask.value = row
			detailsModalOpened.value = true
		}

		const createTask = (id) => {
			taskForm.event_task_state_id = id
			createModalOpened.value = true
			nextTick(() => document.getElementById('focus-create-task').focus())
		}

		const editTask = _ => {
			reset()
			taskEditMode.value = true

			taskForm.name = selectedTask.value.name
			taskForm.description = selectedTask.value.description
			taskForm.date_due = selectedTask.value.date_due

			nextTick(() => document.getElementById('focus-update-task').focus())
		}

		const storeTask = _ => {
			taskForm.post(route('admin.event_tasks.store'), {
				onSuccess: () => close()
			})
		}

		const updateTask = (id) => {
			taskForm.put(route('admin.event_tasks.update', id), {
				onSuccess: () => {
					reset()
					selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
				} 
			}) 
		}

		const deleteTask = (id) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.event_tasks.destroy', id), {
				onSuccess: () => close()
			})
        }

		// EventSubTask CRUD
		const createSubTask = (mode) => {
			reset()
			subTaskCreateMode.value = mode

			if (mode) {
				subTaskForm.event_task_id = selectedTask.value.id
				nextTick(() => document.getElementById('focus-create-subtask').focus())
			}
		}

	    const editSubTask = (subTask) => {
			reset()
			subTaskIndex.value = subTask.id
			subTaskEditMode.value = true

			subTaskForm.name = subTask.name
			subTaskForm.date_due = subTask.date_due

			nextTick(() => document.getElementById('focus-edit-subtask').focus())
		}

		const storeSubTask = _ => {
			subTaskForm.post(route('admin.event_sub_tasks.store'), {
				onSuccess: () => {
					subTaskForm.name = null
					subTaskForm.date_due = null
					selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
					nextTick(() => document.getElementById('focus-create-subtask').focus())
				}
			})
		}

		const updateSubTask = (id) => { 
			subTaskForm.put(route('admin.event_sub_tasks.update', id), {
				onSuccess: () => {
					reset()
					selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
				}
			}) 
		}

		const deleteSubTask = (id) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.event_sub_tasks.destroy', id), {
				onSuccess: () => {
					reset()
					selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
				} 
			})
        }

		const finishSubtask = (id) => {
			subTaskForm.put(route('admin.event_sub_tasks.finish', id), {
				onSuccess: () => selectedTask.value = props.tasks.find(element => element.id == selectedTask.value.id)
			})
		}

		// Tasks drag functions
		const startDrag = (event, item) => {
			event.dataTransfer.dropEffect = 'move'
			event.dataTransfer.effectAllowed = 'move'
			event.dataTransfer.setData('ItemID', item.id)
		}

		const onDrop = (event, state) => {
			const itemID = event.dataTransfer.getData('itemID')
			Inertia.put(route('admin.event_tasks.change_state', [itemID, state]))
		}

		// Return current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

		// Returned date
		return { 
			detailsModalOpened,
			createModalOpened,
			selectedTask, 
			subTaskIndex,
			taskForm,
			subTaskForm,
			taskEditMode,
			subTaskEditMode,
			subTaskCreateMode,
			showTask, 
			createTask, 
			storeTask, 
			deleteTask,   
			editTask, 
			updateTask,
			finishSubtask, 
			createSubTask,  
			storeSubTask, 
			deleteSubTask, 
			updateSubTask,
			editSubTask,
			startDrag, 
			onDrop, 
			reset, 
			currentDate,   
			close,  
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		Modal,
		FormInputField
	},

});
</script>

