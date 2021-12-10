<template>
    <div v-show=message id="response-alert" class="alert alert-success fixed bg-opacity-100 bottom-10 right-0 rounded-r-none text-white " style="z-index: 999;">
        <div class="flex-1 space-x-2 items-center">
            <i class="fas fa-check"></i>
            <label>{{ message }}</label>
        </div>
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'
import { computed, watch, defineComponent } from 'vue'

export default defineComponent ({

    setup() {
        const message = computed(() => usePage().props.value.flash.message)

        // watch(() => message.value, _ => fade())

        // const fade = _ => {
        //     let timer = 3
        //     let interval = setInterval(() => {
        //         if (timer === 0) {
        //             clearInterval(interval)
        //         }
        //         else {
        //             timer--
        //             console.log(timer)
        //         }
        //     }, 1000)
        // }

        return { message }
    }
})
</script>