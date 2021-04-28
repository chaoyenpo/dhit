<template>
  <jet-form-section @submitted="createWebhookReceiver">
    <template #title>
      Webhook 接收器細節
    </template>

    <template #description>
      Webhook 接收器是一種可以將來自外部的消息發布到通訊軟體的一種方法。
    </template>

    <template #form>
      <div class="col-span-6">
        <jet-label value="通訊軟體" />

        <div class="flex items-center mt-2">
          <input
            id="channel"
            name="channel"
            type="radio"
            class="focus:ring-gray-500 h-4 w-4 text-gray-600 border-gray-300"
            checked
          />
          <label
            for="channel"
            class="flex items-center ml-3"
          >
            <img
              class="w-12 h-12 rounded-full object-cover"
              src="https://upload.wikimedia.org/wikipedia/commons/8/83/Telegram_2019_Logo.svg"
              alt="Telegram Logo"
            >

            <div class="ml-4 leading-tight">
              <div>Telegram</div>
            </div>
          </label>
        </div>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label value="機器人" />

        <div class="mt-4 space-y-4">
          <div class="flex items-center">
            <input
              id="bot"
              name="bot"
              type="radio"
              class="focus:ring-gray-500 h-4 w-4 text-gray-600 border-gray-300"
              checked
            />
            <label
              for="bot"
              class="flex items-center ml-3"
            >
              <div class="leading-tight">
                <div>Siri (預設)</div>
              </div>
            </label>
          </div>
        </div>
      </div>
    </template>

    <template #actions>
      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        連接機器人到聊天室中
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
      }),
    };
  },

  methods: {
    createWebhookReceiver() {
      const tgWindow = window.open();

      axios.get("/api/botLink").then((response) => {
        tgWindow.location.href = response.data.url;

        Echo.private(`webhook.receiver.${response.data.token}`).listen(
          "TelegramConnected",
          (e) => {
            tgWindow.close();

            this.$inertia.get(route("webhooks.edit"), {
              id: e.id,
            });
          }
        );
      });
    },
  },
};
</script>
