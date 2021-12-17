<template>
    <app-layout title="Aktualności">
        <div class="flex-col hero-content place-self-start">

            <template v-if="posts == null">
                <h1 class="text-lg font-semibold">Nie dodano jeszcze żadnych postów</h1>
            </template>

            <template v-else>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-16">
                    <article v-for="row in posts.data" :key="row.id" class="card shadow-lg bordered rounded-lg">
                        <figure>
                            <img :src=row.photo_path class="">
                        </figure> 
                        <div class="card-body justify-between">
                            <div class="card-title">
                                <span>{{ row.title }}</span>
                                <h2 class="text-gray-500 text-base">
                                    <div>{{ `${row.user.name} ${row.user.nickname ? `"${row.user.nickname}"` : ''} ${row.user.surname}` }}</div> 
                                    <div class="text-sm">{{ `${row.date_created} ${row.time_created}` }}</div>
                                </h2>
                            </div>
                            <p>{{ row.body }}</p>
                            <div class="card-actions">
                                <Link as="button" :href="route('news.show', row.id)" class="btn btn-primary w-full md:w-auto">Więcej</Link>
                            </div>
                        </div>
                    </article>
                </div>
                <Pagination :links=posts.links :lg="true" class="md:self-start"></Pagination>
            </template>

        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'  
import Pagination from "@/Components/Pagination.vue";

export default defineComponent({
    props: {
        posts: Object
    },

    components: {
        AppLayout,
        Link,
        Pagination
    },
})
</script>