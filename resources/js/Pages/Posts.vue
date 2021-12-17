<template>
    <app-layout title="Aktualności">

        <!-- <div class="hero bg-base-100 place-items-start"> -->
            <!-- <div class="flex-col bg-base-100 hero-content"> -->

               <template v-if="posts == null">
                   <h1 class="text-lg font-semibold">Nie dodano jeszcze żadnych postów</h1>
               </template>

               <template v-else>
                   <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                       <article v-for="row in posts.data" :key="row.id" class="card shadow-2xl bordered">
                           <figure>
                                <img :src=row.photo_path class="">
                            </figure> 
                            <div class="card-body">
                                <div class="card-title">{{ row.title }}</div>
                                <p>{{ row.body.substring(0.25) }}</p>
                            </div>
                       </article>
                   </div>
               </template>

            <!-- </div> -->
        <!-- </div> -->
    </app-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'  
import GroupMember from '@/Components/GroupMember.vue'  
import ActionCard from '@/Components/ActionCard.vue'  

export default defineComponent({
    props: {
        posts: Object
    },

    setup() {


        return {  }
    },

    components: {
        AppLayout,
        GroupMember,
        ActionCard
    },
})
</script>