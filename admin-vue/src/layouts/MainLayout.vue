<template>
  <el-container class="h-screen w-full">
    <el-aside width="auto" class="h-full bg-gray-800 transition-all duration-300">
      <Sidebar :is-collapse="isCollapse" />
    </el-aside>
    
    <el-container>
      <el-header class="bg-white border-b border-gray-200 p-0 h-16">
        <Header :is-collapse="isCollapse" @toggle-sidebar="toggleSidebar" />
      </el-header>
      
      <el-main class="bg-gray-50 p-6">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '@/components/Sidebar.vue';
import Header from '@/components/Header.vue';

const isCollapse = ref(false);

const toggleSidebar = () => {
  isCollapse.value = !isCollapse.value;
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
