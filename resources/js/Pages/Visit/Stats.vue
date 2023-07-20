<template>
    <div>

    </div>
</template>

<script>
    import { ref, reactive } from 'vue';

    export default {
        setup(props, context) {
            const featureRef = ref(value);
            const featureState = reactive({ property: 'value' });

            return {
                featureRef,
                featureState
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
