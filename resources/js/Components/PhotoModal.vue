<template>
    <modal :show="show" @close="closePhotoModal">
        <div class="px-6 py-4">
            <div class="text-lg">
                Edycja zdjęcia
            </div>

            <div class="mt-4">
                <div class="p-4 flex flex-col">
                    <div class="mx-auto">
		            <jet-validation-errors class="my-6" />
                    <img v-if=!changed :src="src" alt="img" class="w-24 h-24">
                    <img v-else :src="isrc" alt="img" class="w-24 h-24">
                    </div>
                    <div class="flex justify-center">
                        <form @submit.prevent="submit" class="hidden">
                            <input type="file" ref="file" accept="image/*" @input="form.avatar = $event.target.files[0]" @change="change"/>
                        </form>
                        <jet-button class="mt-2" @click="browse()"> {{ buttonString }} </jet-button>
                        <jet-button v-if="src != '/images/default.png'" @click="deleteAvatar()" class="mt-2 ml-2"> Usuń</jet-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 space-x-3 bg-gray-100 text-right">
            <jet-button type="submit" @click=submit class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Zapisz
			</jet-button>
            <button @click="closePhotoModal()" class="p-3 rounded-full border-2">Zamknij</button>
        </div>
    </modal>
</template>

<script>
import JetButton from '@/Jetstream/Button.vue'
import { defineComponent, ref, computed } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Modal from '@/Jetstream/Modal.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    emits: ['close-photo-modal'],

    props: {
        item_id: Number,
        show: Boolean,
        path: String,
        src: String
    },

    setup(props, { emit }) {

        const form = useForm({
            avatar: null,
		})

        const changed = ref(false)
        const isrc = ref('')

        const buttonString = computed(() => {
            return props.src == '/images/default.png' ? 'Dodaj' : 'Zmień'
        })

        const submit = _ => { 
			form.post(props.path + '/StorePhoto/'+props.item_id, {
				onSuccess: () => closePhotoModal()
			}) 
		}

        const deleteAvatar = _ => {
            if (!confirm('Na pewno?')) return;
            Inertia.post(props.path + '/DeletePhoto/' + props.item_id)
            closePhotoModal();
        }

        const change = (e) => {
            let reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = (e) => {
                changed.value = true
                isrc.value = e.target.result;
            };
        }

        const closePhotoModal = _ => {
            changed.value = false
            form.reset()
            emit('close-photo-modal')
        }

        return {  buttonString, form, changed, isrc, submit, change, deleteAvatar, closePhotoModal }
        
    },
    
    methods: {
        browse(){
            this.$refs.file.click();
        },
    },

    components: {
        Modal,
        defineComponent,
        JetButton,
        useForm,
        JetValidationErrors,
    },
});
</script>