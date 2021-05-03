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

        <jet-label
          for="bot_token"
          value="Bot Token"
        />

        <jet-input
          id="bot_token"
          type="text"
          class="mt-1 block w-full"
          v-model="form.bot_token"
        />

        <jet-input-error
          :message="form.errors.bot_token"
          class="mt-2"
        />

      </div>
    </template>

    <template #actions>
      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        連接 Bot 到聊天室中
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
        bot_token: "",
      }),
    };
  },

  methods: {
    createWebhookReceiver() {
      this.form.post(route("webhooks.store"), {
        errorBag: "botLink",
      });
    },
  },
};
</script>
