<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { Search, Package, XCircle, AlertTriangle, SortAsc, SortDesc, User, Settings, LogOut } from 'lucide-vue-next';

interface Product {
    id: number;
    nama: string;
    harga: number;
    kategori: string;
    qty: number;
    gambar: string;
}

interface Props {
    user?: {
        name: string;
        email: string;
    };
    products?: Product[];
}

// Sample products data
const dummyProducts = [
    {
        id: 1,
        nama: 'Ducky One 2 Mini',
        harga: 1500000,
        kategori: 'Keyboard',
        qty: 15,
        gambar: 'https://www.galaxus.fr/productimages/7/7/3/7/5/8/9/8/6/1/1/3/2/3/7/4/5/3/4/d5dc5397-d6b5-436e-9c59-67b9668123b5_cropped.jpg'
    },
    {
        id: 2,
        nama: 'Ducky One 2 Midnight TKL',
        harga: 750000,
        kategori: 'Keyboard',
        qty: 0,
        gambar: 'https://blossomzones.com/wp-content/uploads/2018/11/MIDNIGHT.jpg'
    },
    {
        id: 3,
        nama: 'Magicforce mini 60%',
        harga: 4500000,
        kategori: 'Keyboard',
        qty: 5,
        gambar: 'https://www.mechanicalkeyboards.co.id/assets/uploads/products/thumbs/1125x1125/MKID_Smart_68_Silver_CherryMX_Brown_3.jpg'
    },
    {
        id: 4,
        nama: 'USB-C Custom Coiled Cable',
        harga: 300000,
        kategori: 'Accessories',
        qty: 20,
        gambar: 'https://m.media-amazon.com/images/I/81iVOI8vAML._UF1000,1000_QL80_.jpg'
    },
    {
        id: 5,
        nama: 'Keycaps Set PBT Sunrise',
        harga: 1600000,
        kategori: 'Keycaps',
        qty: 0,
        gambar: 'https://m.media-amazon.com/images/I/61G-SwXipVL.jpg'
    },
    {
        id: 6,
        nama: 'Cherry MX Switches speed silver',
        harga: 3500,
        kategori: 'Switch',
        qty: 3,
        gambar: 'https://customkeyboardsuk.co.uk/cdn/shop/files/CMX_RGB_Silver_Sh01_3797d701-c4d1-41b7-9a5f-2f4262f4a402.jpg?v=1738358999&width=1920'
    },
    {
        id: 7,
        nama: 'Varmilo TKL',
        harga: 5000000,
        kategori: 'Keyboard',
        qty: 8,
        gambar: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1V_USYtjWlLk5d8hoOO3mR9Vw5ACrxRmdHA&s'
    },
    {
        id: 8,
        nama: 'Gateron switches red',
        harga: 2000,
        kategori: 'Switch',
        qty: 25,
        gambar: 'https://cdn.shopify.com/s/files/1/0059/0630/1017/t/5/assets/pf-2bb5248f--Gateronredswitchkeychronmechanicalkeyboard1.jpg?v=1612263618'
    }
];

const products = ref(dummyProducts);
const searchQuery = ref('');
const selectedCategory = ref('');
const sortBy = ref('nama');
const sortOrder = ref<'asc' | 'desc'>('asc');
const showProfileMenu = ref(false);

// Get unique categories
const categories = computed(() => {
    const cats = products.value.map(p => p.kategori);
    return [...new Set(cats)];
});

// Get stock status
const getStockStatus = (qty: number) => {
    if (qty === 0) return { text: 'Out of Stock', class: 'text-red-600 bg-red-100', icon: XCircle };
    if (qty <= 2) return { text: 'Low Stock', class: 'text-yellow-600 bg-yellow-100', icon: AlertTriangle };
    return { text: 'In Stock', class: 'text-green-600 bg-green-100', icon: Package };
};

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

// Filtered and sorted products
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

// Add click outside handler
const profileDropdownRef = ref<HTMLElement | null>(null);
onMounted(() => {
    document.addEventListener('click', (event) => {
        if (profileDropdownRef.value && !profileDropdownRef.value.contains(event.target as Node)) {
            showProfileMenu.value = false;
        }
    });
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
    <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <!-- Header with Auth Buttons -->
        <header class="border-b border-gray-200 bg-white dark:bg-[#161615] dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Keyboard Warriors ID</h1>
                    <nav class="flex items-center gap-4">
                        <template v-if="$page.props.auth.user">
                            <div class="relative" ref="profileDropdownRef">
                                <button
                                    @click="showProfileMenu = !showProfileMenu"
                                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-[#3E3E3A]"
                                >
                                    <User class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $page.props.auth.user.name }}
                                    </span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    v-if="showProfileMenu"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#161615] rounded-md shadow-lg py-1 border border-gray-200 dark:border-gray-700"
                                >
                                    <Link
                                        :href="route('settings')"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#3E3E3A]"
                                    >
                                        <Settings class="h-4 w-4 mr-2" />
                                        Settings
                                    </Link>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#3E3E3A]"
                                    >
                                        <LogOut class="h-4 w-4 mr-2" />
                                        Logout
                                    </Link>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal hover:border-[#19140035] dark:text-[#EDEDEC]"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="route('register')"
                                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC]"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filters and Search -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari produk atau kategori..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md dark:bg-[#161615] dark:border-gray-700 dark:text-white"
                        />
                    </div>

                    <!-- Category and Sort Options -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select
                            v-model="selectedCategory"
                            class="px-3 py-2 border border-gray-300 rounded-md dark:bg-[#161615] dark:border-gray-700 dark:text-white"
                        >
                            <option value="">Semua Kategori</option>
                            <option v-for="category in categories" :key="category" :value="category">
                                {{ category }}
                            </option>
                        </select>

                        <!-- Sort buttons -->
                        <div class="flex gap-2">
                            <button
                                @click="toggleSort('harga')"
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100 dark:hover:bg-[#3E3E3A]"
                            >
                                <SortAsc class="h-5 w-5 mr-1" v-if="sortBy === 'harga' && sortOrder === 'asc'" />
                                <SortDesc class="h-5 w-5 mr-1" v-else-if="sortBy === 'harga' && sortOrder === 'desc'" />
                                <span>Harga</span>
                            </button>
                            <button
                                @click="toggleSort('nama')"
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100 dark:hover:bg-[#3E3E3A]"
                            >
                                <SortAsc class="h-5 w-5 mr-1" v-if="sortBy === 'nama' && sortOrder === 'asc'" />
                                <SortDesc class="h-5 w-5 mr-1" v-else-if="sortBy === 'nama' && sortOrder === 'desc'" />
                                <span>Nama</span>
                            </button>
                        </div>

                        <button
                            @click="clearFilters"
                            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Product cards -->
                <div
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden"
                >
                    <img :src="product.gambar" 
                         :alt="product.nama" 
                         class="w-full h-48 object-cover"
                         @error="$event.target.src = 'https://placehold.co/300x200?text=No+Image'">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ product.nama }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ product.kategori }}</p>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ formatCurrency(product.harga) }}
                            </span>
                            <span :class="[
                                'px-2 py-1 rounded-full text-xs font-medium',
                                getStockStatus(product.qty).class
                            ]">
                                {{ getStockStatus(product.qty).text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Add your stats cards here -->
            </div>
        </main>
    </div>
</template>
