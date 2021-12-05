<template>
  <admin-panel-layout title="Wydarzenie">
    <template #page-title>Szczegóły wydarzenia</template>

        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between w-full items-center sm:my-8">
            <h1 class="text-2xl font-bold">{{ event.name }}</h1>
            <div class="space-x-1 sm:space-x-3 flex justify-center">
                <Link :href="route('admin.events.task_manager', event)" as="button" class="btn btn-sm btn-primary">Zadania</Link>
                <template v-if="event.is_finished == false">
                    <button v-if="!userInParticipants($page.props.user.id)" @click=confirmParticipation() class="btn btn-sm btn-secondary">Biorę udział</button>
                    <button v-else @click=confirmParticipation() class="btn btn-sm btn-secondary">Nie biorę udziału</button>
                    <button  @click="openSummary()" class="btn btn-sm btn-accent">Podsumuj</button>
                </template>
            </div>
        </div>
        <div class="w-full flex flex-col lg:flex-row space-y-5 lg:space-x-7 lg:space-y-0">
            <div class="lg:w-1/3">
                <div class="card">
                    <div class="card-title px-1 flex space-x-2 items-center">
                        <h1>Szczegóły</h1>
                        <template v-if="$page.props.user.privilege_id == $page.props.privileges.IS_ADMIN">
                            <i v-if="event.is_finished == false" @click="openEdit()" class="pl-4 fas fa-edit cursor-pointer"></i>
						    <i @click="deleteEvent()" class="fas fa-trash cursor-pointer text-red-700"></i>
                        </template>
                    </div>
                    <div class="card-body py-0 px-2 overflow-y-auto">
                        <div class="flex space-x-2 items-center mt-3">
                            <i class="fas fa-calendar-week"></i>
                            <span>{{ event.date_start + ' - ' + event.date_end }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-3">
                            <i class="fas fa-clock"></i>
                            <span>{{ event.time_start }}</span>
                            <span v-if="event.time_end">{{ ' - ' + event.time_end }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-6">
                            <i class="fas fa-map"></i>
                            <span>Adres</span>
                        </div>
                        <div>
                            <span>{{ 'ul. ' + event.addrStreet + ' ' + event.addrNumber }}</span>
                            <br>
                            <span>{{ event.addrPostCode + ' ' + event.addrTown }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-6">
                            <i class="fas fa-align-justify"></i>
                            <span>Opis</span>
                        </div>
                        <p>{{ event.description }}</p>
                        <template v-if="event.description_summary">
                            <div class="flex space-x-2 items-center mt-6">
                                <i class="fas fa-align-justify"></i>
                                <span>Podsumowanie</span>
                            </div>
                            <p>{{ event.description_summary }}</p>
                        </template>

                    </div>
                </div> 
            </div>
            <div class=" lg:w-1/3">
                <div class="card  ">
                    <h1 class="card-title px-1">
                        Uczestnicy
                    </h1>
                    <div v-if="event.participants" class="card-body py-0 px-2">
                        <ul class="space-y-3">
                            <li v-for="row in event.participants" :key=row.id class="cursor-pointer px-3 py-1 rounded-lg hover:text-white hover:bg-primary transition duration-300 ease-in-out">
                                <div class="flex space-x-2 items-center">
                                    <div class="avatar">
                                        <div class="rounded-full w-10 h-10">
                                            <img src="http://daisyui.com/tailwind-css-component-profile-1@40w.png">
                                        </div>
                                    </div>
                                    <span>{{ row.name + ' \"' + row.nickname + '\" ' + row.surname }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="font-semibold px-1">Wydarzenie nie ma jeszcze uczestników</div>
                </div> 
            </div>
            <div class="lg:w-1/3">
                <div class="card">
                    <div class="card-title px-1 flex space-x-2 items-center">
                        <h1>Sprzęt</h1>
                        <button @click=openItems() class="btn btn-shadow btn-circle btn-sm lg:btn-xs">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div v-if="event.items"  class="card-body py-0 px-2 ">
                        <ul class="space-y-2">
                            <li v-for="row in event.items" :key=row.id class="px-3 py-1 rounded-lg hover:text-white hover:bg-primary transition duration-300 ease-in-out">
                                <div class="flex justify-between items-center">
                                    <span>{{ row.name }}</span>
                                    <button class="btn btn-ghost btn-sm">
                                        <i class="cursor-pointer fas fa-trash text-red-600"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="font-semibold px-1">Wydarzenie nie ma jeszcze sprzętu</div>
                </div> 
            </div>
        </div>
        <CrudModal :show="modalOpened" @close=close>
            <template #title>
                <template v-if="modals.summary">
                    Podsumowanie wydarzenia
                </template>
                <template v-else-if="modals.edit">
                    Edycja wydarzenia
                </template>
                <template v-else-if="modals.items">
                    Edycja przedmiotów
                </template>
            </template>

            <template #content>
                <jet-validation-errors v-if="formEdit.hasErrors || formSummary.hasErrors || formItems.hasErrors" class="my-6" />

                <!-- Podsumowanie -->
                <template v-if="modals.summary">
                    <form @submit.prevent=update>
                        <div class="form-control mt-4">
                            <label class="label mt-4">
						        <span class="label-text">Podsumowanie wydarzenia<span class="ml-1 text-red-500">*</span></span>
					        </label> 
					        <textarea v-model=formSummary.description_summary class="textarea h-24 textarea-bordered textarea-primary" placeholder="Podsumowanie..."></textarea>
                        </div> 
                    </form>
                </template>

                <!-- Edycja -->
                <template v-if="modals.edit">
                    <form @submit.prevent=store>
                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=formEdit.name type="text" placeholder="Nazwa wydarzenia" class="input input-primary input-bordered">

                            <div class="flex space-x-2 mt-4">
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text">Rozpoczęcie<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.date_start type="date"  class="input input-primary input-bordered w-full">
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
                                    <input v-model=formEdit.time_start type="time" class="input input-primary input-bordered w-full">
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text">Zakończenie<span class="ml-1 text-red-500">*</span> (czas opcjonalny)</span></label> 
                                    <input v-model=formEdit.date_end type="date"  class="input input-primary input-bordered w-full">
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Zakończenie (czas opcjonalny)</span></label> 
                                    <input v-model=formEdit.time_end type="time" class="input input-primary input-bordered w-full">
                                </div>
                            </div>

                            <div class="flex mt-4 space-x-2">
                                <div class="w-full">
                                    <label class="label"><span class="label-text">Ulica<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrStreet type="text" placeholder="Ulica" class="input input-primary input-bordered w-full">
                                </div>
                                <div class="w-24">
                                    <label class="label"><span class="label-text">Nr<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrNumber type="text" placeholder="Nr" class="input input-primary input-bordered w-full">
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-48">
                                    <label class="label"><span class="label-text">Kod pocztowy<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrPostCode type="text" placeholder="Kod pocztowy" class="input input-primary input-bordered w-full">
                                </div>
                                <div class="w-full">
                                    <label class="label"><span class="label-text">Miejscowość<span class="ml-1 text-red-500">*</span></span></label> 
                                    <input v-model=formEdit.addrTown type="text" placeholder="Miejscowość" class="input input-primary input-bordered w-full">
                                </div>
                            </div>

                            <label class="label mt-4">
                                <span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
                            </label> 
                            <textarea v-model=formEdit.description class="textarea h-24 textarea-bordered textarea-primary" placeholder="Opis..."></textarea>

                            <!-- Przedmioty -->
                            <template v-if="modals.items">

                            </template>
                        </div> 
                    </form>
                </template>

            </template>

            <template #footer>
                <template v-if="modals.summary">
                    <button @click=finish() :disabled="formSummary.processing" :class="{ 'opacity-25': formSummary.processing }" class="btn btn-info">Zapisz</button>
                </template>
                 <template v-if="modals.edit">
                    <button @click=update() :disabled="formEdit.processing" :class="{ 'opacity-25': formEdit.processing }" class="btn btn-info">Zapisz</button>
                </template>
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
		event: Object,
	},

	setup(props) {

        const modalOpened = ref(false)
        const modals = ref({ edit:false, items:false, summary:false })

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

        const formSummary = useForm({
			description_summary: null,
            is_finished: true
		})

        const formItems = useForm({
			description_summary: null,
            is_finished: true
		})


		const close = _ => { 
			modals.value.edit = false
			modals.value.items = false
			modals.value.summary = false
            modalOpened.value = false

			formEdit.reset() 
            formEdit.clearErrors()
            formSummary.reset()
            formSummary.clearErrors()
		}

		const finish = _ => { 
			formSummary.put(route('admin.events.finish', props.event.id), {
				onSuccess: () => close()
			}) 
		}

        const confirmParticipation = _ => Inertia.put(route('admin.events.participation', props.event.id))

        const update = _ => { 
			formEdit.put(route('admin.events.update', props.event.id), {
				onSuccess: () => close()
			}) 
		}

        const deleteEvent = _ => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.events.destroy', props.event.id))
        }

		const currentDate = _ => new Date().toISOString().split('T')[0]

        const userInParticipants = (id) => {
            if(props.event.participants)
                return props.event.participants.some((ele) => ele.id == id)
        }

        const openSummary = _ => {
            modals.value.summary = true
            modalOpened.value = true
        }

        const openEdit = _ => {
            formEdit.name = props.event.name,

			formEdit.date_start=props.event.date_start,
			formEdit.time_start=props.event.time_start,
			formEdit.date_end=props.event.date_end,
			formEdit.time_end=props.event.time_end,
			
			formEdit.addrStreet=props.event.addrStreet,
			formEdit.addrNumber=props.event.addrNumber,
			formEdit.addrPostCode=props.event.addrPostCode,
			formEdit.addrTown=props.event.addrTown,

			formEdit.description=props.event.description

            modals.value.edit = true
            modalOpened.value = true
        }

        const openItems = _ => {
            modals.value.items = true
            modalOpened.value = true
        }

		return { formEdit, formSummary, formItems, close, update, finish, deleteEvent, confirmParticipation, userInParticipants, currentDate, modals ,openSummary, openEdit, openItems, modalOpened}
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
        CrudModal
	},

});
</script>

