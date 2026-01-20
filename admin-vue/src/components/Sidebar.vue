<template>
  <el-menu
    :default-active="activeMenu"
    class="h-full border-r-0"
    background-color="#304156"
    text-color="#bfcbd9"
    active-text-color="#409EFF"
    router
    :collapse="isCollapse"
  >
    <div class="flex items-center justify-center h-16 bg-gray-900 text-white font-bold text-xl" v-if="!isCollapse">
      Admin Panel
    </div>
    <div class="flex items-center justify-center h-16 bg-gray-900 text-white font-bold text-xl" v-else>
      AP
    </div>

    <el-menu-item index="/dashboard">
      <el-icon><Odometer /></el-icon>
      <template #title>Dashboard</template>
    </el-menu-item>

    <el-menu-item index="/users">
      <el-icon><User /></el-icon>
      <template #title>Users</template>
    </el-menu-item>

    <el-menu-item index="/profile">
      <el-icon><User /></el-icon>
      <template #title>Profile</template>
    </el-menu-item>

    <el-sub-menu index="1">
      <template #title>
        <el-icon><Setting /></el-icon>
        <span>Settings</span>
      </template>
      <el-menu-item index="/settings/general">General</el-menu-item>
      <el-menu-item index="/settings/security">Security</el-menu-item>
    </el-sub-menu>
  </el-menu>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps({
  isCollapse: {
    type: Boolean,
    default: false
  }
});

const route = useRoute();
const activeMenu = computed(() => route.path);
</script>

<style scoped>
.el-menu {
  border-right: none;
}
</style>
