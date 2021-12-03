<template>
    <div v-if="$page.props.flash.message" class="alert alert-success fixed bg-opacity-100 bottom-0 text-white w-screen" style="z-index: 999;">
        <div class="flex-1 space-x-2 items-center">
            <i class="fas fa-check"></i>
            <label>{{ $page.props.flash.message }}</label>
        </div>
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