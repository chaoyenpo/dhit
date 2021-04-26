<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        建立 Webhook 接收器
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <create-webhook-receiver-form />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CreateWebhookReceiverForm from "./CreateWebhookReceiverForm";

export default {
  components: {
    AppLayout,
    CreateWebhookReceiverForm,
  },
};
</script>
