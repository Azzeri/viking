<template>
    <Head title="Nie pamietam hasła" />
    
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
                    </form>

                    <div class="card-actions justify-center">
                        <button @click=submit class="btn btn-primary w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Wyślij link</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors,
            JetApplicationMark
        },

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    })
</script>
