<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        等待連接提醒
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        正在等待您將機器人連接至群組中，連接成功會將會自行跳轉到設定頁面。
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    AppLayout,
  },

  props: {
    url: String,
    token: String,
  },

  setup(props) {
    const tgWindow = window.open();

    tgWindow.location.href = props.url;

    Echo.private(`webhook.receiver.${props.token}`).listen(
      "TelegramConnected",
      (e) => {
        tgWindow.close();

        Inertia.get(route("webhooks.edit"), {
          id: e.id,
        });
      }
    );

    return {};
  },
};
</script>
