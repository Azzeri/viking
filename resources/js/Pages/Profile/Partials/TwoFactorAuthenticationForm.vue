<template>
    <jet-action-section>
        <template #title>
            Weryfikacja dwuetapowa
        </template>

        <template #description>
            Dodatkowe zabezpieczenie dostępu do Twojego konta
        </template>

        <template #content>
            <h3 class="text-lg font-medium text-gray-900" v-if="twoFactorEnabled">
                Włączono weryfikację dwuetapową
            </h3>

            <h3 class="text-lg font-medium text-gray-900" v-else>
                Wyłączono weryfikację dwuetapową
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    Jeśli włączysz weryfikację dwuetapową, aplikacja zażąda od Ciebie losowego tokenu bezpieczeństwa podczas logowania . Tokeny dostępne są w aplikacji Google Authenticator na Twoim telefonie.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Weryfikacja dwuetapowa jest teraz włączona. Zeskanuj widoczny kod QR za pomocą aplikacji w telefonie.
                        </p>
                    </div>

                    <div class="mt-4" v-html="qrCode">
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Zachowaj widoczne kody bezpieczeństwa, najlepiej w menedżerze haseł. Będą potrzebne aby uzyskać dostęp do Twojego konta, jeśli nie będziesz w stanie skorzystać z aplikacji na telefonie.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <jet-confirms-password @confirmed="enableTwoFactorAuthentication">
                        <button class="btn btn-primary" :class="{ 'loading': enabling }" :disabled="enabling">
                            Włącz
                        </button>
                    </jet-confirms-password>
                </div>

                <div v-else>
                    <jet-confirms-password @confirmed="regenerateRecoveryCodes">
                        <jet-secondary-button class="mr-3"
                                        v-if="recoveryCodes.length > 0">
                            Wygeneruj nowe kody bezpieczeństwa
                        </jet-secondary-button>
                    </jet-confirms-password>

                    <jet-confirms-password @confirmed="showRecoveryCodes">
                        <jet-secondary-button class="mr-3" v-if="recoveryCodes.length === 0">
                            Pokaż kody bezpieczeństwa
                        </jet-secondary-button>
                    </jet-confirms-password>

                    <jet-confirms-password @confirmed="disableTwoFactorAuthentication">
                        <button class="btn btn-error"
                                        :class="{ 'loading': disabling }"
                                        :disabled="disabling">
                            Wyłącz
                        </button>
                    </jet-confirms-password>
                </div>
            </div>
        </template>
    </jet-action-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        components: {
            JetActionSection,
            JetButton,
            JetConfirmsPassword,
            JetDangerButton,
            JetSecondaryButton,
        },

        data() {
            return {
                enabling: false,
                disabling: false,

                qrCode: null,
                recoveryCodes: [],
            }
        },

        methods: {
            enableTwoFactorAuthentication() {
                this.enabling = true

                this.$inertia.post('/user/two-factor-authentication', {}, {
                    preserveScroll: true,
                    onSuccess: () => Promise.all([
                        this.showQrCode(),
                        this.showRecoveryCodes(),
                    ]),
                    onFinish: () => (this.enabling = false),
                })
            },

            showQrCode() {
                return axios.get('/user/two-factor-qr-code')
                        .then(response => {
                            this.qrCode = response.data.svg
                        })
            },

            showRecoveryCodes() {
                return axios.get('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.recoveryCodes = response.data
                        })
            },

            regenerateRecoveryCodes() {
                axios.post('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.showRecoveryCodes()
                        })
            },

            disableTwoFactorAuthentication() {
                this.disabling = true

                this.$inertia.delete('/user/two-factor-authentication', {
                    preserveScroll: true,
                    onSuccess: () => (this.disabling = false),
                })
            },
        },

        computed: {
            twoFactorEnabled() {
                return ! this.enabling && this.$page.props.user.two_factor_enabled
            }
        }
    })
</script>
