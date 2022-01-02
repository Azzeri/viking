<template>
  <admin-panel-layout title="Szczegóły postu">
    <div class="flex flex-col gap-4 items-center justify-center">
      <div class="flex flex-wrap gap-2 mt-6">
        <Link :href="route('admin.posts.index')" class="btn btn-primary btn-sm">
          <i class="fas fa-arrow-left mr-2"></i>
          Powrót
        </Link>
        <button @click="edit" class="btn btn-info btn-sm">
          <i class="fas fa-edit mr-2"></i>
          Edytuj
        </button>
        <button @click="deletePost" class="btn btn-error btn-sm">
          <i class="fas fa-trash mr-2"></i>
          Usuń
        </button>
      </div>

      <div class="card bordered shadow-lg max-w-3xl">
        <figure class="">
          <img :src="post.photo_path" class="h-64 object-cover" />
        </figure>
        <div class="card-body">
          <div class="card-title">
            <span>{{ post.title }}</span>
            <h2 class="text-gray-500 text-base text-ju">
              <div>
                {{
                  `${post.user.name} ${
                    post.user.nickname ? `"${post.user.nickname}"` : ""
                  } ${post.user.surname}`
                }}
              </div>
              <div class="text-sm">
                {{ post.date_time_created_formatted }}
              </div>
            </h2>
          </div>
          <p class="text-justify">{{ post.body }}</p>
          <div class="card-actions">
            <button v-if="post.resource_link" class="btn btn-sm btn-secondary">
              Szczegóły
            </button>
          </div>
        </div>
      </div>
    </div>

    <Modal
      :show="modalOpened"
      @close="close"
      :id="'modal-1'"
      :maxWidth="'max-w-3xl'"
    >
      <template #side>
        <h1 class="text-lg font-semibold">Nowy post</h1>
      </template>

      <template #content>
        <form @submit.prevent="update">
          <div class="form-control mt-4">
            <form-input-field
              id="focus-create"
              type="text"
              name="Tytuł"
              :required="true"
              model="title"
              :form="form"
              max="128"
              min="3"
            ></form-input-field>

            <label class="label"
              ><span class="label-text"
                >Treść<span class="ml-1 text-red-500">*</span></span
              ></label
            >
            <textarea
              v-model="form.body"
              class="
                textarea textarea-bordered textarea-primary
                h-64
                resize-none
              "
              placeholder="Treść..."
              maxlength="2048"
              minlength="3"
              required
            ></textarea>

            <label
              v-if="form.errors.body"
              class="label label-text-alt text-error text-sm"
              >{{ form.errors.body }}</label
            >

            <input
              type="file"
              id="upload-file-store"
              @change="previewImage"
              ref="photo"
              accept="image/*"
              @input="form.image = $event.target.files[0]"
              class="hidden"
            />
            <div v-if="url && form.image" class="mx-auto indicator mt-6">
              <div class="indicator-item">
                <button
                  v-if="url && form.image"
                  @click="form.image = null"
                  class="btn btn-xs btn-ghost"
                >
                  <i class="fas fa-times text-error"></i>
                </button>
              </div>
              <img
                :src="url"
                class="block h-24 w-24 object-cover mask mask-squircle"
              />
            </div>
            <label
              for="upload-file-store"
              refs="upload-file"
              class="btn btn-primary mt-4"
              >Zmień zdjęcie</label
            >
            <label
              v-if="form.errors.image"
              class="label label-text-alt text-error text-sm"
              >{{ form.errors.image }}</label
            >
          </div>
          <input type="submit" ref="createPostSubmit" class="hidden" />
        </form>
      </template>

      <template #footer>
        <button
          @click="$refs.createPostSubmit.click()"
          :disabled="form.processing"
          :class="{ loading: form.processing }"
          class="btn btn-info w-full"
        >
          Zapisz
        </button>
      </template>
    </Modal>
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import Modal from "@/Components/CrudModal.vue";
import FormInputField from "@/Components/FormInputField.vue";

export default defineComponent({
  props: {
    post: Object,
  },

  setup(props) {
    const modalOpened = ref(false);
    const url = ref(null);

    const form = useForm({
      title: props.post.title,
      image: null,
      body: props.post.body,
      resource_link: props.post.resource_link,
    });

    const edit = (_) => {
      modalOpened.value = true;
      nextTick(() => document.getElementById("focus-create").focus());
    };

    const close = (_) => {
      modalOpened.value = false;
      form.reset();
      form.clearErrors();
    };

    const update = (_) =>
      form.post(route("admin.posts.update", { post:props.post.id, _method:'put' }), {
        onSuccess: () => close(),
      });

    const deletePost = (_) => {
      if (!confirm("Na pewno?")) return;
      Inertia.delete(route("admin.posts.destroy", props.post.id));
    };

    const previewImage = (e) =>
      (url.value = URL.createObjectURL(e.target.files[0]));

    return {
      form,
      modalOpened,
      url,
      close,
      update,
      deletePost,
      edit,
      previewImage,
    };
  },

  components: {
    AdminPanelLayout,
    Link,
    Modal,
    FormInputField,
  },
});
</script>