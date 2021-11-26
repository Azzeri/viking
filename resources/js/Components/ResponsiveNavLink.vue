<template>
    <li :class="classes">
        <Link :href="href">
            <slot />
        </Link>
    </li>
</template>

<script>
    import { defineComponent } from 'vue';
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['active', 'href'],

        computed: {
            classes() {
                return this.active ? 'bordered bg-primary bg-opacity-50' : ''
            }
        }
    })
</script>
