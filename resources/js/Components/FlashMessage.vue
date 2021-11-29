<template>
    <div v-if="$page.props.flash.message" class="m-2 alert alert-success">
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