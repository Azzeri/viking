<template>
    <Head title="Logowanie" />

    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content">
            <div class="card md:w-96 shadow-2xl bg-base-100">
                <div class="card-body">
                    <jet-application-mark class="block h-9 w-auto mx-auto" />
                    <h1 class="card-title text-lg text-center">Logowanie</h1>

                    <jet-validation-errors class="pl-1" />
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                     <form @submit.prevent=submit>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Email</span></label> 
                            <input v-model=form.email type="email" placeholder="Adres email" class="input input-primary input-bordered" required autofocus autocomplete="email">

                            <label class="label"><span class="label-text">Hasło</span></label> 
                            <input v-model=form.password type="password" placeholder="Hasło" class="input input-primary input-bordered" required autocomplete="current-password">

                            <!-- <label class="cursor-pointer label justify-start mt-2">
                                <input type="checkbox" class="checkbox checkbox-primary checkbox-sm">
                                <span class="ml-2 label-text">Zapamiętaj mnie</span> 
                            </label> -->
                        </div>
				    </form>

                    <div class="card-actions justify-center">
                        <button @click=submit class="btn btn-primary w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Zaloguj</button>

                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-gray-800 hover:text-gray-900 mt-3">
                            Nie pamiętam hasła
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 
    <div class="block mt-6 ml-2 font-semibold text-gray-800">
        <label class="flex items-center">
            <jet-checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600">Zapamiętaj mnie</span>
        </label>
    </div>
    -->
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

    export default defineComponent({
        components: {
            Head,
            JetCheckbox,
            JetValidationErrors,
            Link,
            JetApplicationMark
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
