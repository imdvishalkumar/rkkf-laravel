<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <el-card shadow="hover">
        <template #header>
          <div class="card-header">
            <span>Total Users</span>
          </div>
        </template>
        <div class="text-4xl font-bold text-blue-500">1,234</div>
      </el-card>
      <el-card shadow="hover">
         <template #header>
          <div class="card-header">
            <span>Revenue</span>
          </div>
        </template>
         <div class="text-4xl font-bold text-green-500">$54,000</div>
      </el-card>
      <el-card shadow="hover">
         <template #header>
          <div class="card-header">
            <span>Orders</span>
          </div>
        </template>
         <div class="text-4xl font-bold text-purple-500">567</div>
      </el-card>
    </div>
  </div>
</template>

<script setup>
// Dashboard logic here
</script>
