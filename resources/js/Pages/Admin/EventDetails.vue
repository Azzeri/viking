<template>
  <admin-panel-layout title="Szczegóły wydarzenia">

        <!-- Name and actions -->
        <div class="flex flex-col space-y-3 w-full items-center sm:mt-8">
            <div class="flex flex-wrap gap-2 self-start shadow-lg p-3 rounded-2xl border w-full justify-center">
                <Link :href="route('admin.events.index')" as="button" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Powrót
                </Link>
                <Link :href="route('admin.events.task_manager', event)" as="button" class="btn btn-sm btn-accent">Zadania</Link>
                <template v-if="!event.is_finished">
                    <button v-if="!userInParticipants($page.props.user.id)" @click=confirmParticipation class="btn btn-sm">Biorę udział</button>
                    <button v-else @click=confirmParticipation class="btn btn-sm">Nie biorę udziału</button>
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
        <div class="w-full flex flex-col lg:flex-row justify-between max-w-4xl mt-5 gap-2">
            <!-- Details card -->
            <div class="card border shadow-lg w-full">
                <div class="pt-8 pl-8">
                    <div class="card-title sticky top-0 mb-0">
                        <h1>{{ event.name }}</h1>
                        <h2 class="text-sm text-gray-500">{{ event.is_public ? 'Publiczne' : 'Niepubliczne' }}</h2>
                    </div>
                </div>
                <div class="card-body pt-6 pl-10">
                    <div class="flex">
                        <img :src="event.photo_path" :alt="event.name" class="w-64 h-64 object-cover rounded-lg" />

                    </div>
                    <div class="flex space-x-2 items-center mt-4">
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
                    <p class="text-justify">{{ event.description }}</p>

                    <div v-if="event.is_finished" class="flex space-x-2 items-center mt-6">
                        <i class="fas fa-align-justify"></i>
                        <span>Podsumowanie</span>
                    </div>
                    <p>{{ event.description_summary }}</p>
                </div>
            </div> 

            <!-- Participants card -->
            <div class="card border shadow-lg w-full">
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
                                        <img :src="profilePhotoSource(row)" :alt="row.name" class="rounded-full">
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
                    <form @submit.prevent=finish>
                        <div class="form-control mt-4">
					        <textarea id="focus-summary" v-model=formSummary.description_summary class="textarea h-44 textarea-bordered textarea-primary resize-none" placeholder="Podsumowanie..." minlength="3" maxlength="512"></textarea>
					        <label v-if="formSummary.errors.description_summary" class="label label-text-alt text-error text-sm">{{ formSummary.errors.description_summary }}</label>
                        </div> 
                        <input type="submit" ref="finishEventSubmit" class="hidden" />
                    </form>
                </template>

                <!-- Edit -->
                <template v-if="modals.edit">
                    <form @submit.prevent="update">
                        <div class="form-control mt-4">
                            <form-input-field id="focus-create" type="text" name="Nazwa" :required="true" model="name" :form="formEdit" max="128" min="3"></form-input-field>

                            <div class="flex space-x-2 mt-4">
                                <div class="w-1/2">
                                    <form-input-field type="date" name="Rozpoczęcie" :required="true" model="date_start" :form="formEdit" :min="currentDate()" extraClass="w-full"></form-input-field>
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Rozpoczęcie</span></label> 
                                    <form-input-field type="time" name="Rozpoczęcie" :required="true" model="time_start" :form="formEdit" :label=false extraClass="w-full"></form-input-field>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <form-input-field type="date" name="Zakończenie" :required="true" model="date_end" :form="formEdit" :min="currentDate()" extraClass="w-full"></form-input-field>
                                </div>
                                <div class="w-1/2">
                                    <label class="label"><span class="label-text text-white">Zakończenie</span></label> 
                                    <form-input-field type="time" name="Zakończenie" :required="false" model="time_end" :form="formEdit" :label=false extraClass="w-full"></form-input-field>
                                </div>
                            </div>

                            <div class="flex mt-4 space-x-2">
                                <div class="w-full">
                                    <form-input-field type="text" name="Ulica" :required="true" model="addrStreet" :form="formEdit" extraClass="w-full" min="3" max="64"></form-input-field>
                                </div>
                                <div class="w-24">
                                    <form-input-field type="text" name="Nr" :required="true" model="addrNumber" :form="formEdit" extraClass="w-full" min="1" max="10"></form-input-field>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-48">
                                    <form-input-field type="text" name="Kod" :required="true" model="addrPostCode" :form="formEdit" extraClass="w-full" min="3" max="10"></form-input-field>
                                </div>
                                <div class="w-full">
                                    <form-input-field type="text" name="Miejscowość" :required="true" model="addrTown" :form="formEdit" extraClass="w-full" min="3" max="64"></form-input-field>
                                </div>
                            </div>

                            <div class="flex mt-4 items-center space-x-2">
                                <input v-model="formEdit.is_public" type="checkbox" class="checkbox checkbox-primary">
                                <span class="label-text">Publiczne</span> 
                            </div>

                            <label class="label mt-4">
                                <span class="label-text">Opis wydarzenia<span class="ml-1 text-red-500">*</span></span>
                            </label> 
                            <textarea v-model=formEdit.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." required min="3" max="512"></textarea>
                            <label v-if="formEdit.errors.description" class="label label-text-alt text-error text-sm">{{ formEdit.errors.description }}</label>
                                
                            <img id="event-image-update-form" v-if="formEdit.image == null" :src="event.photo_path" class="block h-24 w-24 object-cover mask mask-squircle mt-2 self-center" />

                            <input type="file" id="upload-file-store" @change="previewImage" ref="photo" accept="image/*" @input="formEdit.image = $event.target.files[0]" class="hidden" />
                            <div v-if="url && formEdit.image" class="mx-auto indicator mt-2">
                                <div class="indicator-item">
                                    <button v-if="url && formEdit.image" @click="formEdit.image=null" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></button>
                                </div> 
                                <img :src="url" class="block h-24 w-24 object-cover mask mask-squircle" />
                            </div>
                            <label for="upload-file-store" refs="upload-file" class="btn btn-primary mt-2">Zmień zdjęcie</label>
                            <label v-if="formEdit.hasErrors && formEdit.errors.image" class="label label-text-alt text-error text-sm">{{ formEdit.errors.image }}</label>
							<label v-if="event.photo_path != '/images/default.png'" @click=removeImage class="btn btn-error mt-2">Usuń zdjęcie</label>
                            
                        </div>
                        <input type="submit" ref="updateEventSubmit" class="hidden" />
                    </form>
                </template>

            </template>

            <template #footer>
                <button v-if="modals.summary" @click="$refs.finishEventSubmit.click()" :disabled="formSummary.processing" :class="{ 'loading': formSummary.processing }" class="btn btn-primary w-full">Zapisz</button>
                <button v-else @click="$refs.updateEventSubmit.click()" :disabled="formEdit.processing" :class="{ 'loading': formEdit.processing }" class="btn btn-primary w-full">Zapisz</button>
            </template>
        </Modal> 

  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from '@/Components/CrudModal.vue'
import FormInputField from "@/Components/FormInputField.vue";
import { isAuthAdmin, profilePhotoSource } from '@/shared.js'

export default defineComponent({

	props: {
		event: Object,
	},

	setup(props) {
        // Modal visibility
        const modalOpened = ref(false)
        const modals = ref({ edit:false, summary:false })

        const url = ref(null)

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

			description:props.event.description,
            image: null,
            deleteImage:false,

            is_public:props.event.is_public
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

            nextTick(() => {
				if(document.getElementById('focus-summary'))
					document.getElementById('focus-summary').focus()
			}) 
        }

        const openEdit = _ => {
            formEdit.name = props.event.name,

			formEdit.date_start = props.event.date_start,
			formEdit.time_start = props.event.time_start,
			formEdit.date_end = props.event.date_end,
			formEdit.time_end = props.event.time_end,
			
			formEdit.addrStreet = props.event.addrStreet,
			formEdit.addrNumber = props.event.addrNumber,
			formEdit.addrPostCode = props.event.addrPostCode,
			formEdit.addrTown = props.event.addrTown,

			formEdit.description = props.event.description,
            formEdit.is_public = props.event.is_public

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
			formEdit.post(route(`admin.events.update`, { event:props.event.id, _method:'put' }), {
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

        const previewImage = (e) => {
            url.value = URL.createObjectURL(e.target.files[0])
            formEdit.deleteImage = false
        }

		const removeImage = _ => {
			formEdit.image = null
            const element = document.getElementById('event-image-update-form')
            if (element)
			    element.src = "/images/default.png"
		}


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
            url,
            previewImage,
            removeImage,
            profilePhotoSource
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

