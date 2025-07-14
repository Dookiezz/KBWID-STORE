<script setup lang="ts">
import { LogOut } from 'lucide-vue-next';

const handleLogout = () => {
    route.flushAll();
}
</script>

<template>
    <div>
        <h1>successful</h1>
        <Link class="block w-full" method="post" :href="route('logout')" @click="handleLogout" as="button">
            <LogOut class="mr-2 h-4 w-4" />
            Log out
        </Link>
    </div>
</template>