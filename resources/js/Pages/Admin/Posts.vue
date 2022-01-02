<template>
  <admin-panel-layout title="Posty">
    <!-- Data not present -->
    <div
      v-if="!posts.data.length && filters.search == null"
      class="mt-6 lg:mt-12 flex flex-col items-center justify-center"
    >
      <h1 class="text-4xl font-bold text-center">
        Nie dodano jeszcze żadnego postu
      </h1>
      <button @click="createPost" class="btn btn-wide btn-secondary mt-4">
        <i class="fas fa-plus fa-lg mr-3"></i>
        Dodaj Post
      </button>
    </div>

    <!-- Data present -->
    <template v-else>
      <DataTable
        :columns="columns"
        :data="posts"
        :filters="filters"
        sortRoute="admin.posts.index"
      >
        <template #buttons>
          <button
            @click="createPost"
            class="btn btn-primary w-full sm:w-auto sm:btn-sm"
          >
            <i class="fas fa-plus mr-2"></i>
            Dodaj post
          </button>
        </template>

        <template #content>
          <tr v-for="row in posts.data" :key="row" class="hover">
            <th class="font-bold">{{ row.id }}</th>
            <td>{{ row.title }}</td>
            <td>{{ row.date_time_created_formatted }}</td>
            <td>
              {{
                `${row.user.name} ${
                  row.user.nickname ? `"${row.user.nickname}"` : ""
                } ${row.user.surname}`
              }}
            </td>
            <td class="space-x-2 text-center">
              <Link
                :href="route('admin.posts.show', row.id)"
                class="btn btn-primary btn-xs"
                >Szczegóły</Link
              >
              <button @click="deleteRow(row.id)" class="btn btn-xs btn-error">
                <i class="fas fa-trash cursor-pointer"></i>
                <span class="ml-1">Usuń</span>
              </button>
            </td>
          </tr>
        </template>
      </DataTable>
    </template>

    <!-- Create post modal -->
    <Modal
      :show="createModalOpened"
      @close="close"
      :id="'modal-1'"
      :maxWidth="'max-w-3xl'"
    >
      <template #side>
        <h1 class="text-lg font-semibold">Nowy post</h1>
      </template>

      <template #content>
        <form @submit.prevent="store">
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
                textarea
                textarea-bordered textarea-primary h-64
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
              >Wybierz zdjęcie</label
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
          :class="{ 'loading': form.processing }"
          class="btn btn-info w-full"
        >
          Dodaj
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
import DataTable from "@/Components/DataTable.vue";
import Modal from "@/Components/CrudModal.vue";
import FormInputField from "@/Components/FormInputField.vue";

export default defineComponent({
  props: {
    posts: Object,
    filters: Object,
  },

  setup() {
    // Modal visibility
    const createModalOpened = ref(false);

    // Image upload
    const url = ref(null);

    // Data form
    const form = useForm({
      title: null,
      body: null,
      image: null,
      resource_link: null,
    });

    // Close modal
    const close = (_) => {
      createModalOpened.value = false;
      form.reset();
      form.clearErrors();
    };

    const createPost = (_) => {
      createModalOpened.value = true;
      nextTick(() => document.getElementById("focus-create").focus());
    };

    // Store post
    const store = (_) => {
      form.post(route("admin.posts.store"), {
        onSuccess: () => close(),
      });
    };

    // Delete post
    const deleteRow = (id) => {
      if (!confirm("Na pewno?")) return;
      Inertia.delete(route("admin.posts.destroy", id), {
        onSuccess: () => close(),
      });
    };

    // Images
    const previewImage = (e) =>
      (url.value = URL.createObjectURL(e.target.files[0]));

    // Columns for table
    const columns = [
      { name: "id", label: "ID", sortable: true },
      { name: "title", label: "Tytuł", sortable: true },
      { name: "created_at", label: "Dodano", sortable: true },
      { name: "user_id", label: "Autor", sortable: true },
      { name: "actions", label: "", sortable: false },
    ];

    // Returned data
    return {
      form,
      columns,
      createModalOpened,
      close,
      store,
      deleteRow,
      url,
      previewImage,
      createPost,
    };
  },

  components: {
    AdminPanelLayout,
    Link,
    DataTable,
    Modal,
    FormInputField
  },
});
</script>