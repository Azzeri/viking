<template>
  <admin-panel-layout title="Wydarzenie">
    <template #page-title>Szczegóły wydarzenia</template>

    <div class="hero-content h-full gap-8 flex-col overflow-y-auto lg:overflow-y-hidden place-items-start mx-auto">
        <div class="mt-32 flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between w-full items-center ">
            <h1 class="text-2xl font-bold">{{ event.name }}</h1>
            <div class="space-x-3">
                <button class="btn btn-sm btn-primary">Zadania</button>
                <button class="btn btn-sm btn-secondary">Biorę udział</button>
                <button class="btn btn-sm btn-accent">Podsumuj</button>
            </div>
        </div>
        <div class="w-full h-full flex lg:flex-row flex-col space-y-4 lg:space-x-7 lg:space-y-0">
            <div class="h-full lg:w-1/3">
                <div class="h-full card ">
                    <div class="card-title px-1">
                        Szczegóły
                    </div>
                    <div class="card-body py-0 px-2 overflow-y-auto">
                        <div class="flex space-x-2 items-center mt-3">
                            <i class="fas fa-calendar-week"></i>
                            <span>{{ event.date_start + ' - ' + event.date_end }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-3">
                            <i class="fas fa-clock"></i>
                            <span>{{ event.time_start }}</span>
                            <span v-if="event.time_end">{{ ' - ' + event.time_end }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-6">
                            <i class="fas fa-map"></i>
                            <span>Adres</span>
                        </div>
                        <div>
                            <span>{{ 'ul. ' + event.addrStreet + ' ' + event.addrNumber }}</span>
                            <br>
                            <span>{{ event.addrPostCode + ' ' + event.addrTown }}</span>
                        </div>
                        <div class="flex space-x-2 items-center mt-6">
                            <i class="fas fa-align-justify"></i>
                            <span>Opis</span>
                        </div>
                        <p>{{ event.description }}</p>
                        <template v-if="event.description_summary">
                            <div class="flex space-x-2 items-center mt-6">
                                <i class="fas fa-align-justify"></i>
                                <span>Podsumowanie</span>
                            </div>
                            <p>{{ event.description_summary }}</p>
                        </template>

                    </div>
                </div> 
            </div>
            <div class="h-full lg:w-1/3">
                <div class="card h-full ">
                    <div class="card-title px-1">
                        Uczestnicy
                    </div>
                    <div class="card-body py-0 px-2 overflow-y-auto">
                        <ul class="space-y-3">
                            <li v-for="row in event.participants" :key=row.id class="cursor-pointer px-3 py-1 rounded-lg hover:text-white hover:bg-primary transition duration-300 ease-in-out">
                                <div class="flex space-x-2 items-center">
                                    <div class="avatar">
                                        <div class="rounded-full w-10 h-10">
                                            <img src="http://daisyui.com/tailwind-css-component-profile-1@40w.png">
                                        </div>
                                    </div>
                                    <span>{{ row.name + ' \"' + row.nickname + '\" ' + row.surname }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div> 
            </div>
            <div class="lg:w-1/3 h-full">
                <div class="card h-full ">
                    <div class="card-title px-1 flex space-x-2 items-center">
                        <span>Sprzęt</span>
                        <button class="btn btn-shadow btn-circle btn-sm lg:btn-xs">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body py-0 px-2 overflow-y-auto">
                        <ul class="space-y-2">
                            <li v-for="row in event.items" :key=row.id class="px-3 py-1 rounded-lg hover:text-white hover:bg-primary transition duration-300 ease-in-out">
                                <div class="flex justify-between items-center">
                                    <span>{{ row.name }}</span>
                                    <button class="btn btn-ghost btn-sm">
                                        <i class="cursor-pointer fas fa-trash text-red-600"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div> 
            </div>
        </div>


    </div>
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({

	props: {
		event: Object,
	},

	setup() {
		

		return {  }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
	},

    // <div class="hero-content gap-8 flex-col overflow-hidden place-items-start mx-auto">
//         <div class="flex items-center space-x-16">
//             <h1 class="text-2xl font-bold">{{ event.name }}</h1>
//             <button class="btn btn-sm">Zadania</button>
//         </div>
//         <div class="w-full flex overflow-x-scroll no-scrollbar space-x-7">
//             <div class="flex-shrink-0 w-96">

});
</script>

