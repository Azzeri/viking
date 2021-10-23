<template>
    <Head title="Log in" />

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="my-6" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="mt-6">
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" placeholder="Adres email" required autofocus />
            </div>

            <div class="mt-6">
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" placeholder="Hasło" required autocomplete="current-password" />
            </div>

            <div class="block mt-6 ml-2 font-semibold text-gray-800">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Zapamiętaj mnie</span>
                </label>
            </div>

            <div class="flex flex-col items-center justify-end mt-8">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Zaloguj
                </jet-button>

                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-gray-800 hover:text-gray-900 mt-3">
                    Nie pamiętam hasła
                </Link>

                <Link :href="route('register')" class="text-sm font-semibold text-gray-800 hover:text-gray-900 mt-2">
                    Zarejestruj się
                </Link>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Link,
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    })
</script>
