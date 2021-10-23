<template>
    <Head title="Rejestracja" />

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="my-6" />

        <form @submit.prevent="submit">
            <div class="mt-6">
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus placeholder="Imię" autocomplete="name" />
            </div>

            <div class="mt-6">
                <jet-input id="surname" type="text" class="mt-1 block w-full" v-model="form.surname" required placeholder="Nazwisko" autocomplete="surname" />
            </div>

            <div class="mt-6">
                <jet-input id="nickname" type="text" class="mt-1 block w-full" v-model="form.nickname" placeholder="Nick" autocomplete="nickname" />
            </div>

            <div class="mt-6">
                <jet-input id="date_birth" type="date" class="mt-1 block w-full" v-model="form.date_birth" :max="currentDate()" min="1900-01-01" autocomplete="date_birth" />
            </div>

            <div class="mt-6">
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required placeholder="Adres email" />
            </div>

            <div class="mt-6">
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required  placeholder="Hasło" autocomplete="new-password" />
            </div>

            <div class="mt-6">
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" placeholder="Powtórz hasło" required autocomplete="new-password" />
            </div>

            <div class="mt-6" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                <jet-label for="terms">
                    <div class="flex items-center">
                        <jet-checkbox name="terms" id="terms" v-model:checked="form.terms" />

                        <div class="ml-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                        </div>
                    </div>
                </jet-label>
            </div>

            <div class="flex flex-col items-center justify-end mt-8">
                <jet-button class="" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Zarejestruj
                </jet-button>

                <Link :href="route('login')" class="text-sm font-semibold text-gray-800 hover:text-gray-900 mt-3">
                    Logowanie
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
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    surname: '',
                    nickname: '',
                    date_birth: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            currentDate() {
		        return new Date().toISOString().split('T')[0];
	    },
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        },

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
    })
</script>
