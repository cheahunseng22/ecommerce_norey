<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-[700px] rounded-lg shadow-lg p-6">
            <header class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">{{ title }}</h2>
                <button @click="close" class="text-gray-500 hover:text-gray-200">&times;</button>
            </header>
            <main class="py-4">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isVisible: { type: Boolean, required: true },
        title: { type: String, default: "Modal" },
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped>
/* Add your modal styles here */
</style>
