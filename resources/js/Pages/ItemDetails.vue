<template>
  <app-layout>
    <div class="hero-content place-self-start mx-auto w-full flex-col">
      <Link
        :href="route('store.index')"
        class="btn btn-primary btn-sm self-start"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Powrót
      </Link>
      <div class="card lg:card-side">
        <figure>
          <img :src="item.photo_path" class="h-64 object-cover" :alt="item.name" />
        </figure>
        <div class="card-body justify-between py-3 px-2 lg:px-6">
          <div>
            <h1 class="card-title capitalize">
              <div>{{ item.name }}</div>
              <div class="text-gray-500 text-sm">{{ item.category.name }}</div>
            </h1>
            <p class="text-justify">{{ item.description }}</p>
          </div>
          <div>
            <h2 class="mt-8 card-title">{{ item.price }} zł</h2>
            <h3 class="">{{ item.quantity }} szt.</h3>
          </div>
        </div>
      </div>

      <div class="ml-1 self-start mt-32">
        <h1 class="card-title">
          Zainteresowany? Zostaw nam swoje dane, a skontaktujemy się z Tobą!
        </h1>
      </div>
      <div class="rounded-lg self-start w-full">
        <jet-validation-errors class="my-6" />
        <form class="form-control space-y-2" @submit.prevent="store">
          <div class="lg:flex lg:space-x-3 lg:w-1/2">
            <div>
              <label class="label">
                <span class="label-text">Imię<span class="ml-1 text-red-500">*</span></span>
              </label>
              <input
                v-model="form.client_name"
                type="text"
                placeholder="Imię"
                autocomplete="name"
                class="input input-info input-bordered w-full"
                autofocus
                required
              />
            </div>
            <div>
              <label class="label">
                <span class="label-text">Nr telefonu</span>
              </label>
              <input
                v-model="form.client_phone"
                type="tel"
                placeholder="Nr telefonu"
                autocomplete="phone"
                class="input input-info input-bordered w-full"
              />
            </div>
            <div>
              <label class="label">
                <span class="label-text">Email<span class="ml-1 text-red-500">*</span></span>
              </label>
              <input
                v-model="form.client_email"
                type="email"
                placeholder="Email"
                autocomplete="email"
                class="input input-info input-bordered w-full"
                required
              />
            </div>
          </div>
          <div class="lg:w-1/2">
            <label class="label">
              <span class="label-text">Opis</span>
            </label>
            <textarea
              class="textarea h-24 textarea-bordered textarea-info w-full"
              placeholder="Możesz podać krótki opis zamówienia"
              v-model="form.description"
            ></textarea>
          </div>
          <button type="submit" class="btn btn-info w-full lg:w-1/2">
            Wyślij
          </button>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

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
      client_email: null,
    });

    const store = (_) =>
      form.post(route("store.store"), { onSuccess: () => form.reset() });

    return { form, store };
  },

  components: {
    AppLayout,
    JetValidationErrors,
    FlashMessage,
    Link,
  },
});
</script>