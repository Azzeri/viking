<template>
    <app-layout title="Wydarzenia">
        <div class="flex-col hero-content place-self-start">
            
            <template v-if="events == null">
                <h1 class="text-lg font-semibold">Nie dodano jeszcze żadnych wydarzeń</h1>
            </template>

            <template v-else>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/3">
                    </div>
                    <div class="lg:w-2/3">
                        <div class="grid md:grid-cols-2 gap-4 mt-16">
                            <article v-for="row in events.data" :key="row.id" class="card shadow-lg bordered rounded-lg">
                                <figure>
                                    <img :src=row.photo_path class="">
                                </figure> 
                                <div class="card-body justify-between">
                                    <div class="card-title">
                                        <span>{{ row.name }}</span>
                                        <h2 class="text-gray-500 text-base">
                                            <div>{{ `${row.date_start} - ${row.date_end}` }}</div> 
                                            <div class="text-sm">{{ `${row.addrTown}` }}</div>
                                        </h2>
                                    </div>
                                    <p>{{ row.description }}</p>
                                    <div class="card-actions">
                                        <Link as="button" :href="route('news.show', row.id)" class="btn btn-primary w-full md:w-auto">Więcej</Link>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <Pagination :links=events.links :lg="true" class="md:self-start"></Pagination>
                    </div>
                </div>
                
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
        events:Object
    },

    setup() {
      
    },

    components: {
        AppLayout,
        Pagination,
        Link
    },
})
</script>
