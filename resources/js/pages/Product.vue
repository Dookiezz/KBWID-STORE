<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { 
    Plus, 
    Edit, 
    Trash2, 
    Search, 
    Package, 
    TrendingUp,
    AlertCircle,
    X,
    Save
} from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    category: string;
    price: number;
    stock: number;
    description: string;
    created_at: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Product',
        href: '/product',
    },
];

// State management
const products = ref<Product[]>([]);
const searchQuery = ref('');
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedProduct = ref<Product | null>(null);
const loading = ref(false);

// Form data
const formData = ref({
    name: '',
    category: '',
    price: 0,
    stock: 0,
    description: ''
});

// Form validation
const formErrors = ref({
    name: '',
    category: '',
    price: '',
    stock: ''
});

// Sample data (in real app, this would come from API)
const sampleProducts: Product[] = [
    {
        id: 1,
        name: 'MacBook Pro 14"',
        category: 'Electronics',
        price: 25999000,
        stock: 15,
        description: 'Apple MacBook Pro with M3 chip',
        created_at: '2024-01-15'
    },
    {
        id: 2,
        name: 'Gaming Chair',
        category: 'Furniture',
        price: 2500000,
        stock: 8,
        description: 'Ergonomic gaming chair with RGB lighting',
        created_at: '2024-01-20'
    },
    {
        id: 3,
        name: 'Wireless Mouse',
        category: 'Electronics',
        price: 450000,
        stock: 32,
        description: 'High-precision wireless gaming mouse',
        created_at: '2024-02-01'
    },
    {
        id: 4,
        name: 'Office Desk',
        category: 'Furniture',
        price: 3200000,
        stock: 5,
        description: 'Modern minimalist office desk',
        created_at: '2024-02-10'
    },
    {
        id: 5,
        name: 'Smartphone',
        category: 'Electronics',
        price: 12000000,
        stock: 0,
        description: 'Latest flagship smartphone',
        created_at: '2024-02-15'
    }
];

// Computed properties
const filteredProducts = computed(() => {
    if (!searchQuery.value) return products.value;
    
    return products.value.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        product.category.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const totalProducts = computed(() => products.value.length);
const totalStock = computed(() => products.value.reduce((sum, product) => sum + product.stock, 0));
const lowStockProducts = computed(() => products.value.filter(product => product.stock <= 5).length);
const outOfStockProducts = computed(() => products.value.filter(product => product.stock === 0).length);

// Utility functions
const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('id-ID');
};

const getStockStatus = (stock: number): string => {
    if (stock === 0) return 'Out of Stock';
    if (stock <= 5) return 'Low Stock';
    return 'In Stock';
};

const getStockStatusColor = (stock: number): string => {
    if (stock === 0) return 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
    if (stock <= 5) return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
    return 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
};

// Form functions
const resetForm = () => {
    formData.value = {
        name: '',
        category: '',
        price: 0,
        stock: 0,
        description: ''
    };
    formErrors.value = {
        name: '',
        category: '',
        price: '',
        stock: ''
    };
};

const validateForm = (): boolean => {
    let isValid = true;
    formErrors.value = { name: '', category: '', price: '', stock: '' };

    if (!formData.value.name.trim()) {
        formErrors.value.name = 'Product name is required';
        isValid = false;
    }

    if (!formData.value.category.trim()) {
        formErrors.value.category = 'Category is required';
        isValid = false;
    }

    if (formData.value.price <= 0) {
        formErrors.value.price = 'Price must be greater than 0';
        isValid = false;
    }

    if (formData.value.stock < 0) {
        formErrors.value.stock = 'Stock cannot be negative';
        isValid = false;
    }

    return isValid;
};

// CRUD operations
const openAddModal = () => {
    resetForm();
    showAddModal.value = true;
};

const openEditModal = (product: Product) => {
    selectedProduct.value = product;
    formData.value = {
        name: product.name,
        category: product.category,
        price: product.price,
        stock: product.stock,
        description: product.description
    };
    formErrors.value = { name: '', category: '', price: '', stock: '' };
    showEditModal.value = true;
};

const openDeleteModal = (product: Product) => {
    selectedProduct.value = product;
    showDeleteModal.value = true;
};

const addProduct = async () => {
    if (!validateForm()) return;

    loading.value = true;
    
    // Simulate API call
    setTimeout(() => {
        const newProduct: Product = {
            id: Math.max(...products.value.map(p => p.id)) + 1,
            ...formData.value,
            created_at: new Date().toISOString().split('T')[0]
        };
        
        products.value.unshift(newProduct);
        showAddModal.value = false;
        resetForm();
        loading.value = false;
    }, 1000);
};

const updateProduct = async () => {
    if (!validateForm() || !selectedProduct.value) return;

    loading.value = true;
    
    // Simulate API call
    setTimeout(() => {
        const index = products.value.findIndex(p => p.id === selectedProduct.value!.id);
        if (index !== -1) {
            products.value[index] = {
                ...products.value[index],
                ...formData.value
            };
        }
        
        showEditModal.value = false;
        selectedProduct.value = null;
        resetForm();
        loading.value = false;
    }, 1000);
};

const deleteProduct = async () => {
    if (!selectedProduct.value) return;

    loading.value = true;
    
    // Simulate API call
    setTimeout(() => {
        products.value = products.value.filter(p => p.id !== selectedProduct.value!.id);
        showDeleteModal.value = false;
        selectedProduct.value = null;
        loading.value = false;
    }, 1000);
};

// Initialize data
onMounted(() => {
    products.value = [...sampleProducts];
});
</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Stats Cards -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Products</p>
                            <p class="text-2xl font-bold">{{ totalProducts }}</p>
                        </div>
                        <Package class="h-8 w-8 text-primary" />
                    </div>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Stock</p>
                            <p class="text-2xl font-bold">{{ totalStock }}</p>
                        </div>
                        <TrendingUp class="h-8 w-8 text-green-500" />
                    </div>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Low Stock</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ lowStockProducts }}</p>
                        </div>
                        <AlertCircle class="h-8 w-8 text-yellow-500" />
                    </div>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Out of Stock</p>
                            <p class="text-2xl font-bold text-red-600">{{ outOfStockProducts }}</p>
                        </div>
                        <AlertCircle class="h-8 w-8 text-red-500" />
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="relative min-h-[60vh] flex-1 rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <h2 class="text-2xl font-bold tracking-tight">Products Management</h2>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <!-- Search -->
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search products..."
                                    class="pl-10 pr-4 py-2 border border-input rounded-lg bg-background text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>
                            <!-- Add Product Button -->
                            <button
                                @click="openAddModal"
                                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors"
                            >
                                <Plus class="h-4 w-4" />
                                Add Product
                            </button>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="w-full border-collapse">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="border-b p-4 text-left text-sm font-medium">Product Name</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Category</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Price</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Stock</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Status</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Created</th>
                                    <th class="border-b p-4 text-left text-sm font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="product in filteredProducts"
                                    :key="product.id"
                                    class="hover:bg-muted/30 transition-colors"
                                >
                                    <td class="border-b p-4">
                                        <div>
                                            <div class="font-medium">{{ product.name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ product.description }}</div>
                                        </div>
                                    </td>
                                    <td class="border-b p-4">{{ product.category }}</td>
                                    <td class="border-b p-4 font-medium">{{ formatCurrency(product.price) }}</td>
                                    <td class="border-b p-4">{{ product.stock }}</td>
                                    <td class="border-b p-4">
                                        <span :class="['inline-flex items-center rounded-full px-2 py-1 text-xs font-medium', getStockStatusColor(product.stock)]">
                                            {{ getStockStatus(product.stock) }}
                                        </span>
                                    </td>
                                    <td class="border-b p-4 text-sm text-muted-foreground">{{ formatDate(product.created_at) }}</td>
                                    <td class="border-b p-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                @click="openEditModal(product)"
                                                class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400"
                                            >
                                                <Edit class="h-3 w-3" />
                                                Edit
                                            </button>
                                            <button
                                                @click="openDeleteModal(product)"
                                                class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400"
                                            >
                                                <Trash2 class="h-3 w-3" />
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredProducts.length === 0">
                                    <td colspan="7" class="p-8 text-center text-muted-foreground">
                                        No products found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showAddModal = false"
        >
            <div class="mx-4 w-full max-w-md rounded-xl bg-background p-6 shadow-lg">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Add New Product</h3>
                    <button
                        @click="showAddModal = false"
                        class="rounded-full p-1 hover:bg-muted"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
                
                <form @submit.prevent="addProduct" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.name }"
                        />
                        <p v-if="formErrors.name" class="mt-1 text-xs text-red-500">{{ formErrors.name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select
                            v-model="formData.category"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.category }"
                        >
                            <option value="">Select category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Books">Books</option>
                            <option value="Sports">Sports</option>
                        </select>
                        <p v-if="formErrors.category" class="mt-1 text-xs text-red-500">{{ formErrors.category }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Price (IDR)</label>
                        <input
                            v-model.number="formData.price"
                            type="number"
                            min="0"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.price }"
                        />
                        <p v-if="formErrors.price" class="mt-1 text-xs text-red-500">{{ formErrors.price }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Stock</label>
                        <input
                            v-model.number="formData.stock"
                            type="number"
                            min="0"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.stock }"
                        />
                        <p v-if="formErrors.stock" class="mt-1 text-xs text-red-500">{{ formErrors.stock }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            v-model="formData.description"
                            rows="3"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button
                            type="button"
                            @click="showAddModal = false"
                            class="flex-1 rounded-lg border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                        >
                            <Save class="h-4 w-4" />
                            {{ loading ? 'Adding...' : 'Add Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showEditModal = false"
        >
            <div class="mx-4 w-full max-w-md rounded-xl bg-background p-6 shadow-lg">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Edit Product</h3>
                    <button
                        @click="showEditModal = false"
                        class="rounded-full p-1 hover:bg-muted"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
                
                <form @submit.prevent="updateProduct" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.name }"
                        />
                        <p v-if="formErrors.name" class="mt-1 text-xs text-red-500">{{ formErrors.name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select
                            v-model="formData.category"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.category }"
                        >
                            <option value="">Select category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Books">Books</option>
                            <option value="Sports">Sports</option>
                        </select>
                        <p v-if="formErrors.category" class="mt-1 text-xs text-red-500">{{ formErrors.category }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Price (IDR)</label>
                        <input
                            v-model.number="formData.price"
                            type="number"
                            min="0"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.price }"
                        />
                        <p v-if="formErrors.price" class="mt-1 text-xs text-red-500">{{ formErrors.price }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Stock</label>
                        <input
                            v-model.number="formData.stock"
                            type="number"
                            min="0"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            :class="{ 'border-red-500': formErrors.stock }"
                        />
                        <p v-if="formErrors.stock" class="mt-1 text-xs text-red-500">{{ formErrors.stock }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            v-model="formData.description"
                            rows="3"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button
                            type="button"
                            @click="showEditModal = false"
                            class="flex-1 rounded-lg border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                        >
                            <Save class="h-4 w-4" />
                            {{ loading ? 'Updating...' : 'Update Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showDeleteModal = false"
        >
            <div class="mx-4 w-full max-w-sm rounded-xl bg-background p-6 shadow-lg">
                <div class="mb-4 flex items-center justify-center">
                    <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/20">
                        <AlertCircle class="h-6 w-6 text-red-600 dark:text-red-400" />
                    </div>
                </div>
                
                <div class="text-center">
                    <h3 class="mb-2 text-lg font-semibold">Delete Product</h3>
                    <p class="mb-4 text-sm text-muted-foreground">
                        Are you sure you want to delete "<strong>{{ selectedProduct?.name }}</strong>"? This action cannot be undone.
                    </p>
                    
                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="flex-1 rounded-lg border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deleteProduct"
                            :disabled="loading"
                            class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
                        >
                            {{ loading ? 'Deleting...' : 'Delete' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>