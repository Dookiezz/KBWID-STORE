<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Header from '@/components/Header/Header.vue';
import ProductFilters from '@/components/Products/ProductFilters.vue';
import ProductCard from '@/components/Products/ProductCard.vue';

interface Product {
    id: number;
    nama: string;
    harga: number;
    kategori: string;
    qty: number;
}

interface Props {
    user?: {
        name: string;
        email: string;
    };
    products: Product[];
}

const props = withDefaults(defineProps<Props>(), {
    products: () => [],
    user: undefined
});

const products = ref(props.products || []);
const searchQuery = ref('');
const selectedCategory = ref('');
const sortBy = ref('nama');
const sortOrder = ref<'asc' | 'desc'>('asc');

const categories = computed(() => {
    if (!products.value) return [];
    const cats = products.value.map(p => p.kategori);
    return [...new Set(cats)];
});

const filteredProducts = computed(() => {
    let filtered = products.value.filter(product => {
        const matchesSearch = product.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            product.kategori.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCategory = !selectedCategory.value || product.kategori === selectedCategory.value;
        return matchesSearch && matchesCategory;
    });

    filtered.sort((a, b) => {
        let aValue = a[sortBy.value as keyof Product];
        let bValue = b[sortBy.value as keyof Product];
        
        if (typeof aValue === 'string') {
            aValue = aValue.toLowerCase();
            bValue = (bValue as string).toLowerCase();
        }
        
        const comparison = aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
        return sortOrder.value === 'asc' ? comparison : -comparison;
    });

    return filtered;
});

const toggleSort = (field: string) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortOrder.value = 'asc';
    }
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = '';
    sortBy.value = 'nama';
    sortOrder.value = 'asc';
};
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
    <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <Header :user="user" />
        
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <ProductFilters
                v-model:searchQuery="searchQuery"
                v-model:selectedCategory="selectedCategory"
                :categories="categories"
                :sortBy="sortBy"
                :sortOrder="sortOrder"
                @sort="toggleSort"
                @clear="clearFilters"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <ProductCard
                    v-for="product in filteredProducts"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </main>
    </div>
</template>
