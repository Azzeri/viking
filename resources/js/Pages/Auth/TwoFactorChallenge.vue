<template>
    <Head title="Weryfikacja dwuetapowa" />

    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content">
            <div class="card md:w-96 shadow-2xl bg-base-100">
                <div class="card-body">
                    <jet-application-mark class="block h-9 w-auto mx-auto" />
                    <h1 class="card-title text-lg text-center">Weryfikacja dwuetapowa</h1>

                    <template v-if="! recovery">
                        Wprowadź kod logowania dostarczony przez aplikację.
                    </template>

                    <template v-else>
                       Wprowadź jeden ze swoich kodów dostępu.
                    </template>

                    <form @submit.prevent="submit" class="mt-4">
                        <div v-if="! recovery">
                            <input type="text" id="code" ref="code" inputmode="numeric" placeholder="Kod" class="input input-primary input-bordered w-full" v-model="form.code" autofocus autocomplete="one-time-code" />
                        </div>

                        <div v-else>
                            <input type="text" id="recovery_code" ref="recovery_code" placeholder="Kod" class="input input-primary input-bordered w-full" v-model="form.recovery_code" autocomplete="one-time-code" />
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" @click.prevent="toggleRecovery">
                                <template v-if="! recovery">
                                    Użyj kodu dostępu
                                </template>

                                <template v-else>
                                    Użyj kodu aplikacji
                                </template>
                            </button>

                            <button class="ml-4 btn btn-primary" :class="{ 'loading': form.processing }" :disabled="form.processing">
                                Zaloguj
                            </button>
                        </div>
                    </form>
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

        data() {
            return {
                recovery: false,
                form: this.$inertia.form({
                    code: '',
                    recovery_code: '',
                })
            }
        },

        methods: {
            toggleRecovery() {
                this.recovery ^= true

                this.$nextTick(() => {
                    if (this.recovery) {
                        this.$refs.recovery_code.focus()
                        this.form.code = '';
                    } else {
                        this.$refs.code.focus()
                        this.form.recovery_code = ''
                    }
                })
            },

            submit() {
                this.form.post(this.route('two-factor.login'))
            }
        }
    })
</script>
