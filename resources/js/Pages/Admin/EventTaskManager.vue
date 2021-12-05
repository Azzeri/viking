<template>
  <admin-panel-layout title="Menedżer zadań">
    <template #page-title>Menedżer zadań</template>

	<div class="w-full flex space-x-2">

		<div v-for="state in task_states" :key=state.id class="flex-shrink-0 border w-64 p-2 bg-neutral rounded-lg">
			<h1 class="text-base-200 font-bold capitalize">{{ state.name }}</h1>
			<div class="mt-4 space-y-3">
				<template v-for="task in tasks" :key="task.id">
					<div @click=showDetails(task) v-if="task.event_task_state_id == state.id" class="p-2 border bg-base-200 rounded-sm hover:bg-base-300 hover:cursor-pointer">
						{{task.name}}
					</div>
				</template>
			</div>
		</div>

	</div>
  </admin-panel-layout>

	<!-- Modal -->
	<input type="checkbox" id="task-details" class="modal-toggle"> 
	<div id="task-details" class="modal ">
		<div class="modal-box">

			<!-- Task name and close button -->
			<div class="flex justify-between items-center">
				<div class="flex items-center space-x-2">
					<i class="fas fa-thumbtack"></i>
					<h1 class="font-bold text-lg capitalize">{{ currentTask.name }}</h1>
				</div>
				<label for="task-details" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></label>
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
			</div>
			<div class="mt-2">
				<ul class="menu">
					<li v-for="task in currentTask.subtasks" :key=task.id >
						<a class="flex items-center space-x-2">
							<input :checked=task.is_finished type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
							<span>{{ task.name }}</span>
						</a>
					</li>
				</ul>
			</div>

		</div>
	</div>


</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

export default defineComponent({

	props: {
		event: Object,
		tasks: Object,
		task_states: Object
	},

	setup(props) {
		const currentTask = props.tasks.length ? ref(props.tasks[0]) : ref({})

		const showDetails = (row) => {
			currentTask.value = row
			document.getElementById('task-details').checked = true
		}

		return { showDetails, currentTask }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
	},

});
</script>

