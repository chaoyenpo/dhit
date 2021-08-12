<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        管理員角色
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <role-table />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import RoleTable from "./RoleTable";

export default {
  components: {
    AppLayout,
    RoleTable,
  },

  data() {
    return {
      //
    };
  },

  methods: {
    //
  },
};
</script>
