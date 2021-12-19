<template>
  <admin-panel-layout title="Szczegóły wydarzenia">

        <!-- Name and actions -->
        <div class="flex flex-col space-y-3 w-full items-center sm:mt-8">
            <div class="flex space-x-2 self-start">
                <Link :href="route('admin.events.index', event)" as="button" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Powrót
                </Link>
                <h1 class="text-2xl font-bold">{{ event.name }}</h1>
            </div>
            <div class="flex flex-wrap gap-2 self-start shadow-lg p-3 rounded-2xl border w-full justify-center">
                <Link :href="route('admin.events.task_manager', event)" as="button" class="btn btn-sm btn-primary">Zadania</Link>
                <template v-if="!event.is_finished">
                    <button v-if="!userInParticipants($page.props.user.id)" @click=confirmParticipation() class="btn btn-sm">Biorę udział</button>
                    <button v-else @click=confirmParticipation() class="btn btn-sm">Nie biorę udziału</button>
                    <button @click="openSummary()" class="btn btn-sm">Podsumuj</button>
                    <button @click="openEdit()" class="btn btn-sm btn-info">
                        <i class="fas fa-edit mr-1"></i>
                        Edytuj
                    </button>
                </template>
                <button v-if="isAuthAdmin" @click="deleteEvent()" class="btn btn-sm btn-error">
                    <i class="fas fa-trash mr-1"></i>
                    Usuń
                </button>
            </div>
        </div>

        <!-- Cards -->
        <div class="w-full flex flex-col space-y-5 max-w-4xl">
            <!-- Details card -->
            <div class="card border shadow-lg">
                <div class="pt-8 pl-8">
                    <div class="card-title sticky top-0 mb-0">
                        <h1>Szczegóły</h1>
                    </div>
                </div>
                <div class="card-body overflow-y-auto pt-6 pl-10">
                    <div class="flex space-x-2 items-center">
                        <i class="fas fa-calendar-week"></i>
                        <span>{{ `${event.date_start_parsed} - ${event.date_end_parsed}` }}</span>
                    </div>
                    <div class="flex space-x-2 items-center mt-3">
                        <i class="fas fa-clock"></i>
                        <span>{{ `${event.time_start} ${event.time_end ? ` - ${event.time_end}` : ''}` }}</span>
                    </div>
                    <div class="flex space-x-2 items-center mt-6">
                        <i class="fas fa-map"></i>
                        <span>Adres</span>
                    </div>
                    <div>
                        <span>{{ `ul. ${event.addrStreet} ${event.addrNumber}` }}</span>
                        <br>
                        <span>{{ `${event.addrPostCode} ${event.addrTown}` }}</span>
                    </div>

                    <div class="flex space-x-2 items-center mt-6">
                        <i class="fas fa-align-justify"></i>
                        <span>Opis</span>
                    </div>
                    <p>{{ event.description }}</p>

                    <div v-if="event.is_finished" class="flex space-x-2 items-center mt-6">
                        <i class="fas fa-align-justify"></i>
                        <span>Podsumowanie</span>
                    </div>
                    <p>{{ event.description_summary }}</p>
                </div>
            </div> 

            <!-- Participants card -->
            <div class="card border shadow-lg">
                <div class="pt-8 pl-8">
                    <div class="card-title sticky top-0 mb-0">
                        <h1>Uczestnicy</h1>
                    </div>
                </div>
                <div class="card-body overflow-y-auto pt-6 pl-10">
                    <ul v-if="event.participants && event.participants.length" class="space-y-3">
                        <li v-for="row in event.participants" :key=row.id class="">
                            <div class="flex space-x-2 items-center">
                                <div class="avatar">
                                    <div class="rounded-full w-10 h-10">
                                        <img src="http://daisyui.com/tailwind-css-component-profile-1@40w.png">
                                    </div>
                                </div>
                                <span>{{ `${row.name} ${row.nickname ? `"${row.nickname}"` : ''} ${row.surname}` }}</span>
                            </div>
                        </li>
                    </ul>
                    <h1 v-else>Wydarzenie nie ma uczestników</h1>
                </div>
            </div> 
        </div>

        <!-- Modals -->
        <Modal :show="modalOpened" @close=close id="main-modal">
            <template #side>
                <h1 v-if="modals.summary" class="text-lg font-semibold">Podsumowanie wydarzenia</h1>
                <h1 v-if="modals.edit" class="text-lg font-semibold">Edycja wydarzenia</h1>
            </template>

            <template #content>

                <!-- Summary -->
                <template v-if="modals.summary">
                    <form @submit.prevent=update>
                        <div class="form-control mt-4">
					        <textarea v-model=formSummary.description_summary class="textarea h-44 textarea-bordered textarea-primary resize-none" placeholder="Podsumowanie..."></textarea>
					        <label v-if="formSummary.errors.description_summary" class="label label-text-alt text-error text-sm">{{ formSummary.errors.description_summary }}</label>
                        </div> 
                    </form>
                </template>

                <!-- Edit -->
                <template v-if="modals.edit">
                    <form>
                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=formEdit.name type="text" placeholder="Nazwa wydarzenia" class="input input-primary input-bordered" required>
                            <label v-if="formEdit.errors.name" class="label label-text-alt text-error text-sm">{{ formEdit.errors.name }}</label>

                            <div class="flex space-x-2 mt-4">
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text">Rozpoczęcie<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.date_start type="date" :min=currentDate() class="input input-primary input-bordered w-full" required>
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
                                    <input v-model=formEdit.time_start type="time" class="input input-primary input-bordered w-full" required>
                                </div>
                            </div>
                            <label v-if="formEdit.errors.date_start" class="label label-text-alt text-error text-sm">{{ formEdit.errors.date_start }}</label>
                            <label v-if="formEdit.errors.time_start" class="label label-text-alt text-error text-sm">{{ formEdit.errors.time_start }}</label>

                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text">Zakończenie<span class="ml-1 text-red-500">*</span> (czas opcjonalny)</span></label> 
                                    <input v-model=formEdit.date_end type="date" :min=currentDate() class="input input-primary input-bordered w-full" required>
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Zakończenie (czas opcjonalny)</span></label> 
                                    <input v-model=formEdit.time_end type="time" class="input input-primary input-bordered w-full">
                                </div>
                            </div>
                            <label v-if="formEdit.errors.date_end" class="label label-text-alt text-error text-sm">{{ formEdit.errors.date_end }}</label>
                            <label v-if="formEdit.errors.time_end" class="label label-text-alt text-error text-sm">{{ formEdit.errors.time_end }}</label>

                            <div class="flex mt-4 space-x-2">
                                <div class="w-full">
                                    <label class="label"><span class="label-text">Ulica<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrStreet type="text" placeholder="Ulica" class="input input-primary input-bordered w-full" required>
                                </div>
                                <div class="w-24">
                                    <label class="label"><span class="label-text">Nr<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrNumber type="text" placeholder="Nr" class="input input-primary input-bordered w-full" required>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-48">
                                    <label class="label"><span class="label-text">Kod<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrPostCode type="text" placeholder="Kod pocztowy" class="input input-primary input-bordered w-full" required>
                                </div>
                                <div class="w-full">
                                    <label class="label"><span class="label-text">Miejscowość<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrTown type="text" placeholder="Miejscowość" class="input input-primary input-bordered w-full" required>
                                </div>
                            </div>
                            <label v-if="formEdit.errors.addrStreet" class="label label-text-alt text-error text-sm">{{ formEdit.errors.addrStreet }}</label>
                            <label v-if="formEdit.errors.addrNumber" class="label label-text-alt text-error text-sm">{{ formEdit.errors.addrNumber }}</label>
                            <label v-if="formEdit.errors.addrPostCode" class="label label-text-alt text-error text-sm">{{ formEdit.errors.addrPostCode }}</label>
                            <label v-if="formEdit.errors.addrTown" class="label label-text-alt text-error text-sm">{{ formEdit.errors.addrTown }}</label>

                            <label class="label mt-4">
                                <span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
                            </label> 
                            <textarea v-model=formEdit.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." required></textarea>
                            <label v-if="formEdit.errors.description" class="label label-text-alt text-error text-sm">{{ formEdit.errors.description }}</label>
                            
                        </div>
                    </form>
                </template>

            </template>

            <template #footer>
                <button v-if="modals.summary" @click=finish() :disabled="formSummary.processing" :class="{ 'loading': formSummary.processing }" class="btn btn-primary w-full">Zapisz</button>
                <button v-else @click=update() :disabled="formEdit.processing" :class="{ 'loading': formEdit.processing }" class="btn btn-primary w-full">Zapisz</button>
            </template>
        </Modal> 

  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		event: Object,
	},

	setup(props) {
        // Modal visibility
        const modalOpened = ref(false)
        const modals = ref({ edit:false, summary:false })

        // Edit form
		const formEdit = useForm({
			name:props.event.name,

			date_start:props.event.date_start,
			time_start:props.event.time_start,
			date_end:props.event.date_end,
			time_end:props.event.time_end,
			
			addrStreet:props.event.addrStreet,
			addrNumber:props.event.addrNumber,
			addrPostCode:props.event.addrPostCode,
			addrTown:props.event.addrTown,

			description:props.event.description
		})

        // Summary form
        const formSummary = useForm({
			description_summary: null,
            is_finished: true
		})

        // Modal opening functions
        const openSummary = _ => {
            modals.value.summary = true
            modalOpened.value = true
        }

        const openEdit = _ => {
            modals.value.edit = true
            modalOpened.value = true
        }

        // Close modal and reset data
		const close = _ => { 
			modals.value.edit = false
			modals.value.summary = false
            modalOpened.value = false

			formEdit.reset() 
            formEdit.clearErrors()
            formSummary.reset()
            formSummary.clearErrors()
		}

        // Mark event as finished and store summary description in DB
		const finish = _ => { 
			formSummary.put(route('admin.events.finish', props.event.id), {
				onSuccess: () => close()
			}) 
		}

        // Confirm authenticated user participation in the event
        const confirmParticipation = _ => Inertia.put(route('admin.events.participation', props.event.id))

        // Update event data
        const update = _ => { 
			formEdit.put(route('admin.events.update', props.event.id), {
				onSuccess: () => close()
			}) 
		}
        
        // Delete event
        const deleteEvent = _ => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.events.destroy', props.event.id))
        }

        // Return current date
		const currentDate = _ => new Date().toISOString().split('T')[0]

        // Check if authenticated user participates in the event
        const userInParticipants = (id) => {
            if(props.event.participants)
                return props.event.participants.some((ele) => ele.id == id)
        }

        // Checks if authenticated user has an administrator privileges
		const isAuthAdmin = computed(() => usePage().props.value.user.privilege_id == usePage().props.value.privileges.IS_ADMIN)

        // Returned data
		return { 
            formEdit, 
            isAuthAdmin, 
            formSummary, 
            modals,
            modalOpened,
            close, 
            update, 
            finish, 
            deleteEvent, 
            confirmParticipation, 
            userInParticipants, 
            currentDate, 
            openSummary, 
            openEdit, 
        }
	},

	components: {
		AdminPanelLayout,
		Link,
        Modal
	},

});
</script>

