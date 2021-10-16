<template>
    <app-layout title="O nas">
        <div class="main mx-auto max-w-6xl text-center">
                <div class="pt-10 lg:pt-20">
                    <h1 class="text-3xl font-bold"> {{groupName}} </h1>
                    <h2 class="text-xl font-semibold">  {{groupSlogan}} </h2>
                    <p class="mt-4 text-justify px-4 lg:px-40 lg:text-center"> {{groupDescription}} </p>
                </div>
                <div class="pt-20">
                    <h1 class="text-xl font-semibold">Nasi członkowie</h1>
                    <div class="flex flex-wrap gap-3 mt-4 justify-center">
                        <template v-for="row in groupMembers" :key="row">
                            <GroupMember :name=row.name :surname=row.surname :role=row.role :photo=row.photo></GroupMember>
                        </template>
                    </div>
                </div>
                <div class="pt-20 pb-2 flex gap-3 justify-center overflow-auto">
                    <ActionCard>Dołącz do nas</ActionCard>
                    <ActionCard>Zobacz sklep</ActionCard>
                    <ActionCard>Naucz się czegoś</ActionCard>
                    <ActionCard>Zapoznaj się z wydarzeniami</ActionCard>
                </div>
            
        <!-- <div class="mx-auto px-4 mt-6 max-w-7xl md:px-20 lg:flex lg:mt-16 lg:justify-around border-2 h-32">
            <section class="lg:w-1/2 ">
                <div>
                    <h1 class="text-3xl font-bold"> {{groupName}} </h1>
                    <h2 class="text-xl font-semibold">  {{groupSlogan}} </h2>
                    <p class="mt-4"> {{groupDescription}} </p>
                </div>
                <div>
                    <h1 class="text-xl font-semibold mt-6">Nasi członkowie</h1>
                    <div class="flex flex-wrap gap-3 mt-4">
                        <template v-for="row in groupMembers" :key="row">
                            <GroupMember :name=row.name :surname=row.surname :role=row.role :photo=row.photo></GroupMember>
                        </template>
                    </div>
                </div>
            </section>
            <section class="lg:w-1/2">
                <img class="rounded-lg hidden lg:block mt-20 w-96" src="http://graphics8.nytimes.com/images/2013/02/24/arts/24VIKINGS1_SPAN/24VIKINGS1-superJumbo.jpg"/>
                <div class="flex lg:flex-wrap gap-3 py-4 lg:px-8 overflow-auto lg:overflow-hidden">
                    <ActionCard>Dołącz do nas</ActionCard>
                    <ActionCard>Zobacz sklep</ActionCard>
                    <ActionCard>Naucz się czegoś</ActionCard>
                    <ActionCard>Zapoznaj się z wydarzeniami</ActionCard>
                </div>
            </section>
        </div> -->
        </div>
    </app-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'  
import GroupMember from '@/Components/GroupMember.vue'  
import ActionCard from '@/Components/ActionCard.vue'  

export default defineComponent({
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
.main {
    min-height: 75vh
}
</style>
