<template>
    <Head title="Rejestracja" />

    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content">
            <div class="card md:w-96 shadow-2xl bg-base-100">
                <div class="card-body">
                    <jet-application-mark class="block h-9 w-auto mx-auto" />
                    <h1 class="card-title text-lg text-center">Rejestracja</h1>

                    <jet-validation-errors class="pl-1" />
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                     <form @submit.prevent=submit>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Email<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.email type="email" placeholder="Adres email" class="input input-primary input-bordered" required disabled="disabled">

                            <label class="label"><span class="label-text">Rola<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.role type="text" placeholder="Adres email" class="input input-primary input-bordered" required disabled="disabled">

                            <label class="label"><span class="label-text">Imię<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.userName type="text" placeholder="Imię" class="input input-primary input-bordered" required autofocus autocomplete="name">

                            <label class="label"><span class="label-text">Nazwisko<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.surname type="text" placeholder="Nazwisko" class="input input-primary input-bordered" required autocomplete="surname">

                            <label class="label"><span class="label-text">Nick</span></label> 
                            <input v-model=form.nickname type="text" placeholder="Nick" class="input input-primary input-bordered" autocomplete="nickname">

                            <label class="label"><span class="label-text">Data urodzenia<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.date_birth type="date" :max="currentDate()" min="1900-01-01" class="input input-primary input-bordered" required autocomplete="date_birth">

                            <label class="label"><span class="label-text">Hasło<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.password type="password" placeholder="Hasło" class="input input-primary input-bordered" required autocomplete="new-password">

                            <label class="label"><span class="label-text">Hasło<span class="ml-1 text-red-500">*</span></span></label> 
                            <input v-model=form.password_confirmation type="password" placeholder="Powtórz Hasło" class="input input-primary input-bordered" required>
                        </div>
				    </form>

                    <div class="card-actions justify-center">
                        <button @click=submit class="btn btn-primary w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Rejestracja</button>
                        <Link :href="route('login')" class="text-sm font-semibold text-gray-800 hover:text-gray-900 mt-1">
                            Logowanie
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </div>
<!-- 
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
 -->
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

    export default defineComponent({
        props: {
            email: String,
            role: String
        },

        setup(props) {
            const form = useForm({
                userName: '',
                surname: '',
                nickname: '',
                date_birth: '',
                email: props.email,
                role: props.role,
                password: '',
                password_confirmation: '',
                terms: false,
            })

            function submit() {
                form.post('storeMember', {
                    onFinish: () => form.reset('password', 'password_confirmation'),
                })
            }

            let currentDate = () => new Date().toISOString().split('T')[0];
            
            return { form, submit, currentDate }
        },

        components: {
            Head,
            JetCheckbox,
            JetValidationErrors,
            Link,
            JetApplicationMark
        },
    })
</script>
