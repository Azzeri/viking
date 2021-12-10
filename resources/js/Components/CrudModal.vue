<template>
    <div :id="id" class="modal">
        <div class="modal-box" :class="maxWidth">
            <div class="flex items-center justify-between">
                <div>
                    <slot name="side"></slot>
                </div>
                <button @click="close()" class="btn btn-sm btn-square btn-ghost">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <slot name="content"></slot>

            <div class="modal-action">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent, watch } from 'vue'

    export default defineComponent({
        emits: ['close'],

        props: {
            show: Boolean,
            id: String,
            maxWidth: String
        },

        setup(props) {
            watch(() => props.show, _ => document.getElementById(props.id).classList.contains('modal-open') 
                ? document.getElementById(props.id).classList.remove('modal-open') 
                : document.getElementById(props.id).classList.add('modal-open'))
        },

        methods: {
            close() {
                this.$emit('close')
            }
        }
    })
</script>
