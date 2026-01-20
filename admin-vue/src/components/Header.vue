<template>
  <div class="flex items-center justify-between h-full px-4 bg-white shadow-sm">
    <div class="flex items-center">
      <el-button type="text" @click="$emit('toggle-sidebar')" class="text-gray-600 focus:outline-none">
        <el-icon :size="20">
          <Fold v-if="!isCollapse" />
          <Expand v-else />
        </el-icon>
      </el-button>
      <el-breadcrumb separator="/" class="ml-4">
        <el-breadcrumb-item :to="{ path: '/' }">Home</el-breadcrumb-item>
        <el-breadcrumb-item>{{ routeName }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="flex items-center">
      <div class="mr-4 text-gray-600 text-sm">
        Welcome, {{ user?.firstname || 'Admin' }}
      </div>
      <el-dropdown trigger="click" @command="handleCommand">
        <span class="el-dropdown-link flex items-center cursor-pointer">
          <el-avatar :size="32" icon="UserFilled" />
          <el-icon class="el-icon--right"><arrow-down /></el-icon>
        </span>
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item command="profile">Profile</el-dropdown-item>
            <el-dropdown-item divided command="logout">Logout</el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const props = defineProps({
  isCollapse: Boolean
});

const emit = defineEmits(['toggle-sidebar']);

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const routeName = computed(() => route.name);
const user = computed(() => authStore.user);

const handleCommand = (command) => {
  if (command === 'logout') {
    authStore.logout();
    router.push('/login');
  } else if (command === 'profile') {
    router.push('/profile');
  }
};
</script>
