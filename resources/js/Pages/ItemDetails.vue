<template>
    <app-layout>
        <FlashMessage></FlashMessage>
        <div class="main mx-auto max-w-6xl py-10 px-2 lg:pt-16">

            <div class="w-full rounded-lg bg-white">
                <img :alt=item.name class="rounded-t-lg w-full object-fill" :src=item.photo_path>
                <header class="p-2 md:p-4">
                    <div class="flex justify-between" items-center>
                        <div>
                            <h1 class="text-lg capitalize">
                                {{ item.name }}
                            </h1>
                            <p class="text-gray-700 text-sm">
                                {{ item.category.name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg">{{ item.price }} zł</p>
                            <p>{{ item.quantity }} szt.</p>
                        </div>
                    </div>
                    <p class="mt-4">{{ item.description }}</p>
                </header>
            </div>

            <div class="w-full rounded-lg bg-white p-3 mt-4">
			    <jet-validation-errors class="my-6" />
                <h1 class='font-semibold'>Zainteresowany? Zostaw nam swoje dane, a skontaktujemy się z Tobą!</h1>
                <form @submit.prevent="store">
                    <div class="mt-6">
					    <label for=name>Imię</label>
					    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.client_name"  autofocus placeholder="Imię" autocomplete="name" />
				    </div>
                    <div class="mt-6">
					    <label for=name>Nr telefonu</label>
					    <jet-input id="phone" type="tel" class="mt-1 block w-full" v-model="form.client_phone" placeholder="Nr telefonu" autocomplete="phone" />
				    </div>
                    <div class="mt-6">
					    <label for=name>Email</label>
					    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.client_email"  placeholder="Email" autocomplete="email" />
				    </div>
                    <div class="mt-6">
					    <label for=name>Opis</label>
					    <jet-input id="desc" type="text" class="mt-1 block w-full" v-model="form.description" placeholder="Napisz kilka słów" autocomplete="desc" />
				    </div>
                    <div class="mt-6">
					    <jet-input id="desc" type="submit" class="mt-1 block w-full" v-model="form.description"/>
				    </div>
                </form>
            </div>
       </div>
    </app-layout>
</template>

<script>
import { defineComponent, ref, reactive } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import JetInput from '@/Jetstream/Input.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import FlashMessage from '@/Components/FlashMessage.vue'

export default defineComponent({
  props: {
    item: Object,
  },

  setup(props) {
      const form = useForm({
            store_item_id: props.item.id,
            description: null,
			client_name: null,
			client_phone: null,
			client_email: null
		})

        const store = _ => form.post('/storeRequestCreate')

        return { form, store }
  },

  components: {
    AppLayout,
    JetInput,
    JetValidationErrors,
    FlashMessage
  },
});
</script>