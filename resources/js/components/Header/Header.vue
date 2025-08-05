<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { User, Settings, LogOut } from 'lucide-vue-next';

const showProfileMenu = ref(false);
const profileDropdownRef = ref<HTMLElement | null>(null);

defineProps<{
    user?: {
        name: string;
        email: string;
    }
}>();
</script>

<template>
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
</template>