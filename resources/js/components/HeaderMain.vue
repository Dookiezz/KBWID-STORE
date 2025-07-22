<script setup lang="ts">
import { ref } from 'vue';
import { User, Settings, LogOut } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const showDropdown = ref(false);
const props = defineProps<{
    username: string
}>();

const handleLogout = () => {
    route.flushAll();
}
</script>

<template>
    <header class="bg-white shadow-sm">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <h1 class="text-2xl font-semibold">Products</h1>
                
                <div class="relative">
                    <button @click="showDropdown = !showDropdown" 
                            class="flex items-center space-x-2 hover:bg-gray-100 p-2 rounded-lg">
                        <User class="h-5 w-5" />
                        <span>{{ username }}</span>
                    </button>
                    
                    <div v-if="showDropdown" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                        <Link :href="route('settings')" 
                              class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <Settings class="h-4 w-4 mr-2" />
                            Settings
                        </Link>
                        <Link :href="route('logout')" 
                              method="post" 
                              as="button"
                              @click="handleLogout"
                              class="flex items-center px-4 py-2 hover:bg-gray-100 w-full">
                            <LogOut class="h-4 w-4 mr-2" />
                            Log out
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>