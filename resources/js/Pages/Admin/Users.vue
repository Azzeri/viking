<template>
  <admin-panel-layout title="Użytkownicy">
    <div class="mx-auto max-w-6xl text-center">
      <form @submit.prevent="submit">

            <div class="mt-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required placeholder="Wprowadź email" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Wygeneruj link do rejestracji
                </jet-button>
            </div>
        </form>

      <!-- <Link :href="route('generateRegistrationLink', 0)">Wygeneruj koordynatora</Link> -->
      <!-- <Link :href="route('generateRegistrationLink', 1)">Wygeneruj członka</Link> -->
        <!-- {{users}} -->
    </div>
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
import { reactive } from 'vue'

export default defineComponent({
  props: {
    users: Object,
  },

  setup() {
    const form = reactive({
      email: null,
    })

    function submit() {
      this.$inertia.post('generateRegistrationLink', form)
    }

    return { form, submit }
  },


  // data() {
  //   return {
  //     form: {
  //       email: ''
  //     }
  //   }
  // },
  // methods: {
  //   generate(type) {
  //     type == 0 ? 
  //     this.$inertia.post('/generateRegistrationLink', 0, this.form) :
  //     this.$inertia.post('generateRegistrationLink', 1, this.form)
  //   }
  // },


  components: {
    AdminPanelLayout,
    Link,
    JetButton,
    JetInput,
    JetLabel
  },
});
</script>
        AdminPanelLayout