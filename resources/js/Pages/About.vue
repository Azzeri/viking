<template>
    <app-layout title="O nas">

                <!-- Group description -->
                    <img class="w-11/12 sm:w-auto max-w-sm rounded-lg shadow-2xl" src="https://cdn.pixabay.com/photo/2020/08/17/08/45/axe-5494732_960_720.jpg"> 
                    <div>
                        <h1 class="text-5xl font-bold">
                            {{ $page.props.groupInfo.name }}
                        </h1> 
                        <h2 class="mt-2 text-2xl font-bold text-gray-500">
                            {{ $page.props.groupInfo.motto }}
                        </h2> 
                        <p class="mt-5 text-justify">
                            {{ $page.props.groupInfo.description }}                
                        </p> 
                    </div>
<img class="w-11/12 sm:w-auto max-w-sm rounded-lg shadow-2xl" src="https://cdn.pixabay.com/photo/2020/08/17/08/45/axe-5494732_960_720.jpg"> 
                    <div>
                        <h1 class="text-5xl font-bold">
                            {{ $page.props.groupInfo.name }}
                        </h1> 
                        <h2 class="mt-2 text-2xl font-bold text-gray-500">
                            {{ $page.props.groupInfo.motto }}
                        </h2> 
                        <p class="mt-5 text-justify">
                            {{ $page.props.groupInfo.description }}                
                        </p> 
                    </div>

                <!-- Group Members -->
                <!-- <div class="hero-content w-full justify-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-500">
                            Nasi członkowie
                        </h1> 
                        
                        <div class="my-3 grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                            <template v-for="row in users" :key="row">
                                <GroupMember class="w-full" :user=row></GroupMember>
                            </template>
                        </div>
                        
                    </div>
                    
                </div>
                 -->
    </app-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'  
import GroupMember from '@/Components/GroupMember.vue'  
import ActionCard from '@/Components/ActionCard.vue'  

export default defineComponent({
    props: {
        users: Object
    },

    setup() {
        const groupName = 'Kuźnia Barbarian'
        const groupSlogan = 'Wykuj z nami swoje marzenia'
        const groupDescription = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

        const groupMembers = ref([
            {name : 'Jan', surname : 'Nowak', role : 'earl', photo : 'https://data.whicdn.com/images/322027365/original.jpg?t=1541703413'},
            {name : 'Jan', surname : 'Nowak', role : 'earl', photo : 'https://data.whicdn.com/images/322027365/original.jpg?t=1541703413'},
            {name : 'Jan', surname : 'Nowak', role : 'earl', photo : 'https://data.whicdn.com/images/322027365/original.jpg?t=1541703413'},
            {name : 'Jan', surname : 'Nowak', role : 'earl', photo : 'https://data.whicdn.com/images/322027365/original.jpg?t=1541703413'},
            {name : 'Jan', surname : 'Nowak', role : 'earl', photo : 'https://data.whicdn.com/images/322027365/original.jpg?t=1541703413'},
            ])

        return { groupName, groupSlogan, groupDescription, groupMembers }
    },

    components: {
        AppLayout,
        GroupMember,
        ActionCard
    },
})
</script>

<style>
/* Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
