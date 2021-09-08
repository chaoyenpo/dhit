<template>
  <jet-action-section>
    <template #title> Webhook 接收器細節 </template>

    <template #description>
      Webhook 接收器是一種可以將來自外部的消息發布到通訊軟體的一種方法。
    </template>

    <template #content>
      <div class="grid grid-cols-6 gap-6">
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
            <label for="channel" class="flex items-center ml-3">
              <img
                class="w-12 h-12 rounded-full object-cover"
                src="https://upload.wikimedia.org/wikipedia/commons/8/83/Telegram_2019_Logo.svg"
                alt="Telegram Logo"
              />

              <div class="ml-4 leading-tight">
                <div>Telegram</div>
              </div>
            </label>
          </div>
        </div>

        <div class="col-span-6">
          <jet-label for="bot_token" value="Bot Token" />

          <jet-input
            id="bot_token"
            type="text"
            class="mt-1 block w-full"
            v-model="form.bot_token"
          />

          <jet-input-error :message="errors.bot_token" class="mt-2" />
        </div>

        <div class="col-span-6">
          <jet-button type="button" @click="connectTelegramGroup">
            連結到群組
          </jet-button>
        </div>
      </div>
    </template>
  </jet-action-section>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetActionSection from "@/Jetstream/ActionSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    JetButton,
    JetActionSection,
    JetInput,
    JetInputError,
    JetLabel,
  },

  data() {
    return {
      form: {
        bot_token: "",
      },
      errors: {},
      processing: false,
    };
  },

  methods: {
    connectTelegramGroup() {
      if (window.tgWindow) {
        window.tgWindow.close();
        window.tgWindow = null;
      }
      window.tgWindow = window.open();
      window.axios
        .get("../api/customBotLink", {
          params: {
            bot_token: this.form.bot_token,
          },
        })
        .then(this.link)
        .catch((err) => {
          window.tgWindow.close();
          this.errors = _.mapValues(err.response.data.errors, (o) => o.join());
        });
    },

    link(res) {
      this.errors = {};
      window.tgWindow.location.href = res.data.url;

      Echo.private(`webhook.receiver.${res.data.token}`).listen(
        "TelegramConnected",
        (e) => {
          window.tgWindow.close();

          this.$inertia.get(route("webhooks.edit"), {
            id: e.id,
          });
        }
      );
    },
  },
};
</script>
