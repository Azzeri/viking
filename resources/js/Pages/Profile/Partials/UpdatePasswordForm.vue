<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            Zmień hasło
        </template>

        <template #description>
            Upewnij się, że hasło jest odpowiednio długie i skomplikowane
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Obecne hasło</span>
                </label> 
                <input type="password" class="input input-primary input-bordered w-full" v-model="form.current_password" ref="current_password" autocomplete="current-password">
				<label v-if="form.errors.current_password" class="label label-text-alt text-error text-sm">{{ form.errors.current_password }}</label>
            </div>

            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Nowe hasło</span>
                </label> 
                <input type="password" class="input input-primary input-bordered w-full" v-model="form.password" ref="password" autocomplete="new-password">
				<label v-if="form.errors.password" class="label label-text-alt text-error text-sm">{{ form.errors.password }}</label>
            </div>

            <div class="col-span-6 sm:col-span-4 form-control">
                <label class="label">
                    <span class="label-text">Potwierdź hasło</span>
                </label> 
                <input type="password" class="input input-primary input-bordered w-full" v-model="form.password_confirmation" autocomplete="new-password">
				<label v-if="form.errors.password_confirmation" class="label label-text-alt text-error text-sm">{{ form.errors.password_confirmation }}</label>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Zapisano.
            </jet-action-message>

            <button class="btn btn-primary" :class="{ 'loading': form.processing }" :disabled="form.processing">
                Zapisz
            </button>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(route('user-password.update'), {
                    errorBag: 'updatePassword',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                        if (this.form.errors.password) {
                            this.form.reset('password', 'password_confirmation')
                            this.$refs.password.focus()
                        }

                        if (this.form.errors.current_password) {
                            this.form.reset('current_password')
                            this.$refs.current_password.focus()
                        }
                    }
                })
            },
        },
    })
</script>
