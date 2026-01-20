<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">User Management</h1>
      <el-button type="primary" @click="openCreateDialog">Add User</el-button>
    </div>

    <el-card>
      <el-table :data="users" v-loading="loading" style="width: 100%">
        <el-table-column prop="user_id" label="ID" width="80" />
        <el-table-column prop="firstname" label="First Name" />
        <el-table-column prop="lastname" label="Last Name" />
        <el-table-column prop="email" label="Email" />
        <el-table-column prop="mobile" label="Mobile" />
        <el-table-column prop="role" label="Role" width="120">
          <template #default="scope">
            <el-tag :type="getRoleType(scope.row.role)">{{ scope.row.role }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="Actions" width="180">
          <template #default="scope">
            <el-button size="small" @click="handleEdit(scope.row)">Edit</el-button>
            <el-button size="small" type="danger" @click="handleDelete(scope.row)">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <el-dialog v-model="dialogVisible" :title="dialogTitle">
      <el-form :model="form" :rules="rules" ref="formRef" label-position="top">
        <div class="grid grid-cols-2 gap-4">
          <el-form-item label="First Name" prop="firstname">
            <el-input v-model="form.firstname" />
          </el-form-item>
          <el-form-item label="Last Name" prop="lastname">
            <el-input v-model="form.lastname" />
          </el-form-item>
        </div>
        
        <el-form-item label="Email" prop="email">
          <el-input v-model="form.email" />
        </el-form-item>
        
        <el-form-item label="Mobile" prop="mobile">
          <el-input v-model="form.mobile" />
        </el-form-item>

        <div class="grid grid-cols-2 gap-4">
            <el-form-item label="DOB" prop="dob">
                <el-date-picker v-model="form.dob" type="date" placeholder="Select Date" value-format="YYYY-MM-DD" style="width: 100%" />
            </el-form-item>
            <el-form-item label="Gender" prop="gender">
                <el-select v-model="form.gender">
                    <el-option label="Male" value="Male" />
                    <el-option label="Female" value="Female" />
                </el-select>
            </el-form-item>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <el-form-item label="Branch" prop="branch_id">
                <el-select v-model="form.branch_id" placeholder="Select Branch">
                    <el-option v-for="branch in branches" :key="branch.branch_id" :label="branch.name" :value="branch.branch_id" />
                </el-select>
            </el-form-item>
             <el-form-item label="Belt" prop="belt_id">
                <el-select v-model="form.belt_id" placeholder="Select Belt">
                    <el-option v-for="belt in belts" :key="belt.belt_id" :label="belt.name" :value="belt.belt_id" />
                </el-select>
            </el-form-item>
        </div>

        <el-form-item label="Address" prop="address">
            <el-input v-model="form.address" type="textarea" />
        </el-form-item>
        <el-form-item label="Pincode" prop="pincode">
            <el-input v-model="form.pincode" />
        </el-form-item>

        <el-form-item label="Password" prop="password" v-if="!isEditMode">
          <el-input v-model="form.password" type="password" show-password />
        </el-form-item>
        
        <el-form-item label="Role" prop="role">
            <el-select v-model="form.role" placeholder="Select Role">
                <el-option label="Admin" value="admin" />
                <el-option label="Instructor" value="instructor" />
                <el-option label="User" value="user" />
            </el-select>
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">Cancel</el-button>
          <el-button type="primary" :loading="submitting" @click="handleSubmit">
            {{ isEditMode ? 'Update' : 'Create' }}
          </el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { userService } from '@/api/users';
import { ElMessage, ElMessageBox } from 'element-plus';

const users = ref([]);
const branches = ref([]);
const belts = ref([]);
const loading = ref(false);
const dialogVisible = ref(false);
const submitting = ref(false);
const isEditMode = ref(false);
const formRef = ref(null);

const form = reactive({
  user_id: null,
  firstname: '',
  lastname: '',
  email: '',
  mobile: '',
  password: '',
  role: 'user',
  // New Fields
  dob: '',
  gender: 'Male',
  branch_id: null,
  belt_id: null,
  address: '',
  pincode: ''
});

const rules = {
  firstname: [{ required: true, message: 'First name is required', trigger: 'blur' }],
  lastname: [{ required: true, message: 'Last name is required', trigger: 'blur' }],
  email: [
      { required: true, message: 'Email is required', trigger: 'blur' },
      { type: 'email', message: 'Invalid email address', trigger: 'blur' }
  ],
  mobile: [{ required: true, message: 'Mobile is required', trigger: 'blur' }],
  password: [{ required: true, message: 'Password is required', trigger: 'blur' }],
  role: [{ required: true, message: 'Role is required', trigger: 'change' }],
  dob: [{ required: true, message: 'Date of birth is required', trigger: 'change' }],
  branch_id: [{ required: true, message: 'Branch is required', trigger: 'change' }],
  belt_id: [{ required: true, message: 'Belt is required', trigger: 'change' }]
};

const dialogTitle = computed(() => isEditMode.value ? 'Edit User' : 'Create User');

const fetchOptions = async () => {
    try {
        const [branchRes, beltRes] = await Promise.all([
            userService.getBranches(),
            userService.getBelts()
        ]);
        branches.value = branchRes.data.data ? branchRes.data.data : branchRes.data;
        belts.value = beltRes.data.data ? beltRes.data.data : beltRes.data;
    } catch (error) {
        console.error("Failed to fetch options", error);
    }
};

const fetchUsers = async () => {
  loading.value = true;
  try {
    const response = await userService.getUsers();
    // Expected structure: response.data.data.users (nested in success helper)
    // or response.data.users (if success helper puts it directly)
    const resData = response.data;
    if (resData.data && resData.data.users) {
        users.value = resData.data.users;
    } else if (resData.users) {
        users.value = resData.users;
    } else {
        // Fallback for flat array or paginated data
        users.value = resData.data ? resData.data : resData;
    }
  } catch (error) {
    ElMessage.error('Failed to fetch users');
  } finally {
    loading.value = false;
  }
};

const getRoleType = (role) => {
    switch(role) {
        case 'admin': return 'danger';
        case 'instructor': return 'warning';
        default: return 'success';
    }
}

const openCreateDialog = () => {
    isEditMode.value = false;
    resetForm();
    dialogVisible.value = true;
};

const handleEdit = (row) => {
    isEditMode.value = true;
    form.user_id = row.user_id; // Mapping backend ID
    form.firstname = row.firstname;
    form.lastname = row.lastname;
    form.email = row.email;
    form.mobile = row.mobile;
    form.role = row.role;
    // Password usually not filled on edit
    dialogVisible.value = true;
};

const resetForm = () => {
    if(formRef.value) formRef.value.resetFields();
    form.user_id = null;
    form.firstname = '';
    form.lastname = '';
    form.email = '';
    form.mobile = '';
    form.password = '';
    form.role = 'user';
    form.dob = '';
    form.gender = 'Male';
    form.branch_id = null;
    form.belt_id = null;
    form.address = '';
    form.pincode = '';
};

const handleSubmit = async () => {
    if (!formRef.value) return;
    
    await formRef.value.validate(async (valid) => {
        if (valid) {
            submitting.value = true;
            try {
                if (isEditMode.value) {
                    await userService.updateUser(form.user_id, form);
                    ElMessage.success('User updated successfully');
                } else {
                    await userService.createUser(form);
                    ElMessage.success('User created successfully');
                }
                dialogVisible.value = false;
                fetchUsers();
            } catch (error) {
                console.error(error);
                ElMessage.error(error.response?.data?.message || 'Operation failed');
            } finally {
                submitting.value = false;
            }
        }
    });
};

const handleDelete = (row) => {
    ElMessageBox.confirm(
        'Are you sure you want to delete this user?',
        'Warning',
        {
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            type: 'warning',
        }
    )
    .then(async () => {
        try {
            await userService.deleteUser(row.user_id);
            ElMessage.success('Delete completed');
            fetchUsers();
        } catch (error) {
             ElMessage.error('Delete failed');
        }
    })
    .catch(() => {});
};

onMounted(() => {
  fetchUsers();
  fetchOptions();
});
</script>
