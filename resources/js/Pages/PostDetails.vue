<template>
    <app-layout title="Szczegóły postu">
        <div class="flex-col hero-content place-self-start mx-auto">
            <Link :href="route('news.index')" class="btn btn-primary btn-sm self-start">
                <i class="fas fa-arrow-left mr-2"></i>
                Powrót
            </Link>
            <div class="card bordered shadow-lg max-w-3xl">
                <figure>
                    <img :src=post.photo_path class="">
                </figure> 
                <div class="card-body">
                    <div class="card-title">
                        <span>{{ post.title }}</span>
                        <h2 class="text-gray-500 text-basez">
                            <div>{{ `${post.user.name} ${post.user.nickname ? `"${post.user.nickname}"` : ''} ${post.user.surname}` }}</div> 
                            <div class="text-sm">{{ `${post.date_created} ${post.time_created}` }}</div>
                        </h2>
                    </div>
                    <p>{{ post.body }}</p>
                    <div class="card-actions">
                        <button v-if="post.resource_link" class="btn btn-sm btn-secondary">Szczegóły</button>
                    </div>
                </div>
            </div> 
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'  

export default defineComponent({
    props: {
        post: Object
    },

    components: {
        AppLayout,
        Link,
    },
})
</script>