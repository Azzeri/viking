<template>
  <admin-panel-layout title="Menedżer zadań">
    <template #page-title>Menedżer zadań</template>
{{event}}{{tasks}}
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

export default defineComponent({

	props: {
		event: Object,
		tasks: Object
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