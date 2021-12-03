<template>
    <div id="modal" class="modal">
        <div class="modal-box">
            <h1 class="text-lg font-bold">
                <slot name="title"></slot>
            </h1>
            
            <slot name="content"></slot>

            <div class="modal-action">
                <slot name="footer"></slot>
                <button @click="close()" class="btn">Zamknij</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent, watch } from 'vue'

    export default defineComponent({
        emits: ['close'],

        props: {
            show: Boolean
        },

        setup(props) {
            watch(() => props.show, _ => document.getElementById('modal').classList.contains('modal-open') ? 
            document.getElementById('modal').classList.remove('modal-open') : 
            document.getElementById('modal').classList.add('modal-open'))
    
        },

        methods: {
            close() {
                this.$emit('close')
            }
        }
    })
</script>
