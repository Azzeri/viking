<template>
    <div v-if="links.length > 3" class="btn-group">
        <template v-for="(link, p) in links" :key="p">
            <div v-if="link.url === null" class="btn btn-disabled" v-html="link.label" />
            <Link v-else class="btn" :class="{ 'btn-active': link.active }" :href="link.url" v-html="link.label" />
        </template>
    </div>

        <!-- <div v-if="links.length > 3">
        <div class="flex flex-wrap justify-center">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />
                <Link v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded text-text-200 hover:bg-primary-200 hover:text-tertiary focus:border-primary-200 focus:text-tertiary"
                    :class="{ 'bg-tertiary text-text-200 focus:text-text-200': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div> -->
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

    export default {
        props: {
            links: Array
        },
        components: {
            Link
        }
    }
</script>