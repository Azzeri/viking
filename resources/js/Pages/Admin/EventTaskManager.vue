<template>
  <admin-panel-layout title="Menedżer zadań">
    <template #page-title>Menedżer zadań</template>

	<div class="w-full flex space-x-2">

		<div v-for="state in task_states" :key=state.id class="flex-shrink-0 border w-64 p-2 bg-neutral rounded-lg">
			<h1 class="text-base-200 font-bold capitalize">{{ state.name }}</h1>
			<div class="mt-4 space-y-3">
				<template v-for="task in tasks" :key="task.id">
					<div v-if="task.event_task_state_id == state.id" class="p-2 border bg-base-200 rounded-sm hover:bg-base-300 hover:cursor-pointer">
						{{task.name}}
					</div>
				</template>
			</div>
		</div>

	</div>
  </admin-panel-layout>
      <!-- // <div class="hero-content gap-8 flex-col overflow-hidden place-items-start mx-auto">
//         <div class="flex items-center space-x-16">
//             <h1 class="text-2xl font-bold">{{ event.name }}</h1>
//             <button class="btn btn-sm">Zadania</button>
//         </div>
//         <div class="w-full flex overflow-x-scroll no-scrollbar space-x-7">
//             <div class="flex-shrink-0 w-96"> -->
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

	setup() {

		return {  }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
	},

});
</script>

