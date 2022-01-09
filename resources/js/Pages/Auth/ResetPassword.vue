<template>
    <Head title="Reset Password" />

    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content">
            <div class="card md:w-96 shadow-2xl bg-base-100">
                <div class="card-body">
                    <jet-application-mark class="block h-9 w-auto mx-auto" />
                    <h1 class="card-title text-lg text-center">Reset hasła</h1>

                    <jet-validation-errors class="pl-1" />
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent=submit>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Email</span></label> 
                            <input v-model=form.email type="email" placeholder="Adres email" class="input input-primary input-bordered" required autofocus autocomplete="email">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Hasło</span></label> 
                            <input v-model=form.password type="password" placeholder="Hasło" class="input input-primary input-bordered" required autocomplete="password">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Potwierdź hasło</span></label> 
                            <input v-model=form.password_confirmation type="password" placeholder="Potwierdź hasło" class="input input-primary input-bordered" required autocomplete="new-password">
                        </div>
                    </form>

                    <div class="card-actions justify-center">
                        <button @click=submit class="btn btn-primary w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Zapisz</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { defineComponent } from 'vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors
        },

        props: {
            email: String,
            token: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    })
</script>
