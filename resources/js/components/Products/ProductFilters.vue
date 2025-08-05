<script setup lang="ts">
import { Search, SortAsc, SortDesc } from 'lucide-vue-next';

defineProps<{
    searchQuery: string;
    selectedCategory: string;
    categories: string[];
    sortBy: string;
    sortOrder: 'asc' | 'desc';
}>();

const emit = defineEmits<{
    'update:searchQuery': [value: string];
    'update:selectedCategory': [value: string];
    'sort': [field: string];
    'clear': [];
}>();
</script>

<template>
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                <input
                    :value="searchQuery"
                    @input="e => emit('update:searchQuery', (e.target as HTMLInputElement).value)"
                    type="text"
                    placeholder="Cari produk atau kategori..."
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md dark:bg-[#161615] dark:border-gray-700 dark:text-white"
                />
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Category Filter -->
                <select
                    :value="selectedCategory"
                    @change="e => emit('update:selectedCategory', (e.target as HTMLSelectElement).value)"
                    class="px-3 py-2 border border-gray-300 rounded-md dark:bg-[#161615] dark:border-gray-700 dark:text-white"
                >
                    <option value="">Semua Kategori</option>
                    <option v-for="category in categories" :key="category" :value="category">
                        {{ category }}
                    </option>
                </select>

                <!-- Sort Options -->
                <div class="flex gap-2">
                    <button
                        @click="emit('sort', 'nama')"
                        class="flex items-center px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800"
                        :class="{ 'bg-indigo-50 border-indigo-300 dark:bg-gray-800': sortBy === 'nama' }"
                    >
                        Nama
                        <component
                            :is="sortBy === 'nama' ? (sortOrder === 'asc' ? SortAsc : SortDesc) : ''"
                            class="ml-1 h-4 w-4"
                        />
                    </button>
                    <button
                        @click="emit('sort', 'harga')"
                        class="flex items-center px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800"
                        :class="{ 'bg-indigo-50 border-indigo-300 dark:bg-gray-800': sortBy === 'harga' }"
                    >
                        Harga
                        <component
                            :is="sortBy === 'harga' ? (sortOrder === 'asc' ? SortAsc : SortDesc) : ''"
                            class="ml-1 h-4 w-4"
                        />
                    </button>
                </div>

                <!-- Clear Filters -->
                <button
                    @click="emit('clear')"
                    class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>
</template>