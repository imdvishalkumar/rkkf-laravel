<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6">User Profile</h1>
    <el-card class="max-w-xl">
      <template #header>
        <div class="flex items-center justify-between">
          <span>Personal Information</span>
          <el-button type="primary" size="small">Edit</el-button>
        </div>
      </template>
      <div v-if="user" class="space-y-4">
        <div class="flex border-b pb-2">
          <span class="w-1/3 font-semibold text-gray-600">First Name:</span>
          <span>{{ user.firstname }}</span>
        </div>
        <div class="flex border-b pb-2">
          <span class="w-1/3 font-semibold text-gray-600">Last Name:</span>
          <span>{{ user.lastname }}</span>
        </div>
        <div class="flex border-b pb-2">
          <span class="w-1/3 font-semibold text-gray-600">Email:</span>
          <span>{{ user.email }}</span>
        </div>
        <div class="flex pb-2">
          <span class="w-1/3 font-semibold text-gray-600">Mobile:</span>
          <span>{{ user.mobile }}</span>
        </div>
      </div>
      <div v-else class="text-center py-4 text-gray-500">
        Loading profile...
      </div>
    </el-card>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const user = computed(() => authStore.user);
</script>
