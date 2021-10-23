<template>
    <div class="bg-white pl-4 py-3 rounded-lg bg-opacity-60" v-if="hasErrors">
        <div class="font-semibold text-red-600">Ups! Coś poszło nie tak.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    })
</script>
