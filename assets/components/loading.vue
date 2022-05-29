<template>
    <div
        v-show="isLoading"
        class="loading"
        :style="`background-color: ${options.bg};`"
    >
        <div
            role="status"
            class="spinner-border"
        >
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Loading',
    props: {
        isLoading: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            options: {
                bg: '#6d6f6ead',
            },
        };
    },
};
</script>

<style lang="scss">
.loading {
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    height: 100vh;
    z-index: 99999 !important;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.loading i {
    position: absolute;
    opacity: 1;
}
</style>
