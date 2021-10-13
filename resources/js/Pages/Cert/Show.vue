<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ domain.name }}
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <update-domain-form :domain="domain" />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import UpdateDomainForm from "./UpdateDomainForm";

export default {
  props: ["domain"],

  components: {
    AppLayout,
    JetSectionBorder,
    UpdateDomainForm,
  },
};
</script>
