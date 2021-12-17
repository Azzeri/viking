<template>
    <Link :href="href" :class="classes" class="btn btn-ghost btn-sm rounded-btn">
        <slot />
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active ? ' text-accent' : 'text-neutral-content'
            }
        }
    })
</script>
