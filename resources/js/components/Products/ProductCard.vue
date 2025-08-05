<script setup lang="ts">
import { Package, XCircle, AlertTriangle } from 'lucide-vue-next';

interface Product {
    id: number;
    nama: string;
    harga: number;
    kategori: string;
    qty: number;
    gambar?: string;
}

defineProps<{
    product: Product;
}>();

const getStockStatus = (qty: number) => {
    if (qty === 0) return { text: 'Out of Stock', class: 'text-red-600 bg-red-100', icon: XCircle };
    if (qty <= 2) return { text: 'Low Stock', class: 'text-yellow-600 bg-yellow-100', icon: AlertTriangle };
    return { text: 'In Stock', class: 'text-green-600 bg-green-100', icon: Package };
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};
</script>

<template>
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <!-- Product Image -->
        <div class="aspect-w-16 aspect-h-12 bg-gray-100 dark:bg-gray-800">
            <img
                :src="product.gambar"
                :alt="product.nama"
                class="w-full h-48 object-cover"
                @error="$event.target.src = 'https://placehold.co/300x200?text=No+Image'"
            />
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <div class="mb-2">
                <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-1">{{ product.nama }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ product.kategori }}</p>
            </div>

            <div class="mb-3">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ formatCurrency(product.harga) }}
                </p>
            </div>

            <!-- Stock Status -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <component 
                        :is="getStockStatus(product.qty).icon" 
                        class="h-4 w-4 mr-1"
                        :class="getStockStatus(product.qty).class.split(' ')[0]"
                    />
                    <span 
                        class="text-xs px-2 py-1 rounded-full font-medium"
                        :class="getStockStatus(product.qty).class"
                    >
                        {{ getStockStatus(product.qty).text }}
                    </span>
                </div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ product.qty }}</span>
            </div>
        </div>
    </div>
</template>