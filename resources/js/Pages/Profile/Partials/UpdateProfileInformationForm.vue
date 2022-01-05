<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            Dane użytkownika
        </template>

        <template #description>
            Zaktualizuj swoje dane
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="col-span-6 sm:col-span-4" v-if="$page.props.jetstream.managesProfilePhotos">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            ref="photo"
                            @change="updatePhotoPreview">

                <jet-label for="photo" value="Zdjęcie profilowe" />

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="user.profile_photo_path || `https://ui-avatars.com/api/?name=${$page.props.user.name} ${$page.props.user.surname}&color=7F9CF5&background=EBF4FF`" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          :style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <button class="btn btn-sm mt-2 mr-2" @click.prevent="selectNewPhoto">
                    Wybierz nowe zdjęcie
                </button>

                <button class="btn btn-sm mt-2 mr-2" @click.prevent="deletePhoto" v-if="user.profile_photo_path">
                    Usuń zdjęcie
                </button>

                <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Imię</span>
                </label> 
                <input type="text" placeholder="imię" class="input input-primary input-bordered w-full" v-model="form.name" autocomplete="name">
				<label v-if="form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
            </div>

            <!-- Surname -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Nazwisko</span>
                </label> 
                <input type="text" placeholder="Nazwisko" class="input input-primary input-bordered w-full" v-model="form.surname">
				<label v-if="form.errors.surname" class="label label-text-alt text-error text-sm">{{ form.errors.surname }}</label>
            </div>

            <!-- Nickname -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Nick</span>
                </label> 
                <input type="text" placeholder="Nick" class="input input-primary input-bordered w-full" v-model="form.nickname">
				<label v-if="form.errors.nickname" class="label label-text-alt text-error text-sm">{{ form.errors.nickname }}</label>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Email</span>
                </label> 
                <input type="email" placeholder="Email" class="input input-primary input-bordered w-full" v-model="form.email" autocomplete="email">
				<label v-if="form.errors.email" class="label label-text-alt text-error text-sm">{{ form.errors.email }}</label>
            </div>

            <!-- Date birth -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Data urodzenia</span>
                </label> 
                <input type="date" :max="currentDate()" class="input input-primary input-bordered w-full" v-model="form.date_birth">
				<label v-if="form.errors.date_birth" class="label label-text-alt text-error text-sm">{{ form.errors.date_birth }}</label>
            </div>

            <!-- Role -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Rola</span>
                </label> 
                <input type="text" class="input input-primary input-bordered w-full" :disabled="$page.props.user.privilege_id != $page.props.privileges.IS_ADMIN" v-model="form.role">
				<label v-if="form.errors.role" class="label label-text-alt text-error text-sm">{{ form.errors.role }}</label>
            </div>

            <!-- Description -->
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Opis</span>
                </label> 
                <textarea class="textarea h-44 textarea-bordered textarea-primary w-full resize-none" placeholder="Opis" v-model="form.description"></textarea>
				<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>
            </div>

        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Zapisano
            </jet-action-message>

            <button class="btn btn-primary" :class="{ 'loading': form.processing }" :disabled="form.processing">
                Zapisz
            </button>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    surname: this.user.surname,
                    nickname: this.user.nickname,
                    date_birth: this.user.date_birth,
                    role: this.user.role,
                    description: this.user.description,
                    email: this.user.email,
                    photo: null,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => (this.clearPhotoFileInput()),
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const photo = this.$refs.photo.files[0];

                if (! photo) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(photo);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.photoPreview = null;
                        this.clearPhotoFileInput();
                    },
                });
            },

            clearPhotoFileInput() {
                if (this.$refs.photo?.value) {
                    this.$refs.photo.value = null;
                }
            },

            currentDate() {
                return new Date().toISOString().split('T')[0]
            }
        },
    })
</script>
