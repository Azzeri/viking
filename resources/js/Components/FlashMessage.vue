<template>
    <div v-if="$page.props.flash.message" class="bg-green-600 fixed left-100 text-white font-bold p-3 border-l-4 border-white rounded-lg flex">
        <div>{{ $page.props.flash.message }}</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            visible: false
        }
    },

    methods: {
        close() {
            this.visible = false
        }
    }
}
</script>