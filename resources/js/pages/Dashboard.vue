<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { 
    TrendingUp, 
    TrendingDown, 
    Package, 
    Users, 
    ShoppingCart, 
    DollarSign,
    Eye,
    MoreHorizontal 
} from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Sample data - replace with actual API calls
const stats = ref({
    totalRevenue: 45231.89,
    revenueChange: 20.1,
    totalOrders: 2350,
    ordersChange: 180.1,
    totalProducts: 12234,
    productsChange: 19,
    totalUsers: 573,
    usersChange: 201
});

const recentOrders = ref([
    { id: 'ORD-001', customer: 'John Doe', product: 'Laptop Gaming', amount: 15000000, status: 'completed' },
    { id: 'ORD-002', customer: 'Jane Smith', product: 'Smartphone', amount: 8000000, status: 'processing' },
    { id: 'ORD-003', customer: 'Bob Johnson', product: 'Tablet', amount: 5000000, status: 'pending' },
    { id: 'ORD-004', customer: 'Alice Brown', product: 'Headphones', amount: 1500000, status: 'completed' },
    { id: 'ORD-005', customer: 'Charlie Wilson', product: 'Monitor', amount: 3000000, status: 'processing' },
]);

const topProducts = ref([
    { name: 'Laptop Gaming', sales: 142, revenue: 71000000, change: 12 },
    { name: 'Smartphone', sales: 98, revenue: 49000000, change: 8 },
    { name: 'Tablet', sales: 67, revenue: 20100000, change: -5 },
    { name: 'Headphones', sales: 89, revenue: 13350000, change: 15 },
    { name: 'Monitor', sales: 54, revenue: 16200000, change: 3 },
]);

const salesData = ref([
    { month: 'Jan', sales: 4000, revenue: 240000000 },
    { month: 'Feb', sales: 3000, revenue: 180000000 },
    { month: 'Mar', sales: 5000, revenue: 300000000 },
    { month: 'Apr', sales: 4500, revenue: 270000000 },
    { month: 'May', sales: 6000, revenue: 360000000 },
    { month: 'Jun', sales: 5500, revenue: 330000000 },
]);

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-100 text-green-800';
        case 'processing': return 'bg-blue-100 text-blue-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getStatusText = (status: string) => {
    switch (status) {
        case 'completed': return 'Selesai';
        case 'processing': return 'Proses';
        case 'pending': return 'Menunggu';
        default: return status;
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Stats Cards -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Revenue Card -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pemasukan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ formatCurrency(stats.totalRevenue) }}
                            </p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <DollarSign class="h-6 w-6 text-green-600" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <TrendingUp class="h-4 w-4 text-green-500 mr-1" />
                        <span class="text-sm text-green-600">+{{ stats.revenueChange }}%</span>
                        <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ stats.totalOrders.toLocaleString() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3">
                            <ShoppingCart class="h-6 w-6 text-blue-600" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <TrendingUp class="h-4 w-4 text-green-500 mr-1" />
                        <span class="text-sm text-green-600">+{{ stats.ordersChange }}%</span>
                        <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ stats.totalProducts.toLocaleString() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-purple-100 p-3">
                            <Package class="h-6 w-6 text-purple-600" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <TrendingUp class="h-4 w-4 text-green-500 mr-1" />
                        <span class="text-sm text-green-600">+{{ stats.productsChange }}%</span>
                        <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Sales Chart -->
                <div class="lg:col-span-2 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Penjualan</h3>
                        <button class="text-sm text-blue-600 hover:text-blue-800">Lihat Detail</button>
                    </div>
                    
                    <!-- Simple Chart Representation -->
                    <div class="space-y-4">
                        <div class="flex items-end justify-between h-48 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div v-for="(data, index) in salesData" :key="index" class="flex flex-col items-center flex-1">
                                <div 
                                    class="w-8 bg-blue-500 rounded-t-sm mb-2 transition-all duration-300 hover:bg-blue-600"
                                    :style="{ height: `${(data.sales / 6000) * 160}px` }"
                                ></div>
                                <span class="text-xs text-gray-500">{{ data.month }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>0</span>
                            <span>6000</span>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk Terlaris</h3>
                        <button class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div v-for="(product, index) in topProducts" :key="index" class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <Package class="h-5 w-5 text-gray-600" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</p>
                                    <p class="text-xs text-gray-500">{{ product.sales }} terjual</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ formatCurrency(product.revenue) }}</p>
                                <div class="flex items-center">
                                    <TrendingUp v-if="product.change > 0" class="h-3 w-3 text-green-500 mr-1" />
                                    <TrendingDown v-else class="h-3 w-3 text-red-500 mr-1" />
                                    <span class="text-xs" :class="product.change > 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ product.change > 0 ? '+' : '' }}{{ product.change }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                    <button class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID Pesanan</th>
                                <th scope="col" class="px-6 py-3">Pelanggan</th>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in recentOrders" :key="order.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ order.id }}
                                </td>
                                <td class="px-6 py-4">{{ order.customer }}</td>
                                <td class="px-6 py-4">{{ order.product }}</td>
                                <td class="px-6 py-4">{{ formatCurrency(order.amount) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusColor(order.status)">
                                        {{ getStatusText(order.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <Eye class="h-4 w-4" />
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-800">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>