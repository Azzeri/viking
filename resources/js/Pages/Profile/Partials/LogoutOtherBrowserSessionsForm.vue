<template>
    <jet-action-section>
        <template #title>
            Sesje przeglądarki
        </template>

        <template #description>
            Zarządzaj sesjami i wyloguj się z innych urządzeń
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Jeśli zajdzie taka potrzeba, możesz wylogować się ze wszystkich innych urządzeń, na których jesteś zalogowany/a. Poniżej widoczne są niektóre z ostatnich sesji. Jeśli uważasz, że Twoje konto może być narażone, powinienieś zmienić hasło.
            </div>

            <!-- Other Browser Sessions -->
            <div class="mt-5 space-y-6" v-if="sessions.length > 0">
                <div class="flex items-center" v-for="(session, i) in sessions" :key="i">
                    <div>
                        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500" v-if="session.agent.is_desktop">
                            <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500" v-else>
                            <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                        </svg>
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{ session.agent.platform }} - {{ session.agent.browser }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span class="text-green-500 font-semibold" v-if="session.is_current_device">To urządzenie</span>
                                <span v-else>Ostatnia aktywna sesja {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-5">
                <button class="btn btn-success" @click="confirmLogout">
                    Wyloguj z innych urządzeń
                </button>

                <jet-action-message :on="form.recentlySuccessful" class="ml-3">
                    Gotowe.
                </jet-action-message>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <jet-dialog-modal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    Wyloguj z innych urządzeń
                </template>

                <template #content>
                    Proszę potwierdzić swoje hasło

                    <div class="mt-4">
                        <label class="label">
                            <span class="label-text">Nowe hasło</span>
                        </label> 
                        <input type="password" class="input input-primary input-bordered w-3/4" v-model="form.password" ref="password" placeholder="Hasło" @keyup.enter="logoutOtherBrowserSessions" >
                        <label v-if="form.errors.password" class="label label-text-alt text-error text-sm">{{ form.errors.password }}</label>
                    </div>
                </template>

                <template #footer>
                    <button class="btn btn-error" @click="closeModal">
                        Anuluj
                    </button>

                    <button class="btn ml-2" @click="logoutOtherBrowserSessions" :class="{ 'loading': form.processing }" :disabled="form.processing">
                        Wyloguj z innych urządzeń
                    </button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        props: ['sessions'],

        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        data() {
            return {
                confirmingLogout: false,

                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            confirmLogout() {
                this.confirmingLogout = true

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            logoutOtherBrowserSessions() {
                this.form.delete(route('other-browser-sessions.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                    onError: () => this.$refs.password.focus(),
                    onFinish: () => this.form.reset(),
                })
            },

            closeModal() {
                this.confirmingLogout = false

                this.form.reset()
            },
        },
    })
</script>
