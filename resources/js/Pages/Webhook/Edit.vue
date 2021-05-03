<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        編輯配置
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <jet-section-title>
          <template #title>
            Webhook 接收器設定說明
          </template>
          <template #description>
            我們將引導您完成配置傳入 Webhook 接收器所需的步驟，以便您可以開始接收資料。
          </template>
        </jet-section-title>

        <jet-section-border />

        <div class="mt-10 sm:mt-0">
          <jet-action-section>
            <template #title>
              Webhook URL
            </template>

            <template #description>
              發送你的 JSON payloads 到這個 URL。
            </template>

            <template #content>
              {{$page.props.webhookReceiver.uri}}

              <div class="mt-5">
                <jet-button
                  type="button"
                  v-clipboard="$page.props.webhookReceiver.uri"
                  v-clipboard:success="onSuccess"
                >
                  {{copyLabel}}
                </jet-button>
              </div>
            </template>
          </jet-action-section>

          <jet-section-border />

          <update-message-format-form />

          <jet-section-border />

          <jet-action-section>
            <template #title>
              發送到群組
            </template>

            <template #description>
              當 Webhook 接收器收到傳進來的訊息時，會將訊息發送到這個指定的群組中。
            </template>

            <template #content>
              {{$page.props.webhookReceiver.chat.title}}

              <div class="mt-5">
                <jet-button
                  type="button"
                  @click="reconnectWebhookReceiver"
                >
                  重新連結到其他群組
                </jet-button>
              </div>
            </template>
          </jet-action-section>

          <jet-section-border />

          <jet-action-section>
            <template #title>
              Bot 資訊
            </template>

            <template #content>
              {{$page.props.webhookReceiver.bot.name}}
              <br>
              <a
                class="font-medium text-gray-800 hover:text-gray-700"
                target="_blank"
                :href="`https://t.me/${$page.props.webhookReceiver.bot.username}`"
              >
              @{{$page.props.webhookReceiver.bot.username}}
              </a>
            </template>
          </jet-action-section>

          <jet-section-border />

          <delete-webhook-receiver-form
            class="mt-10 sm:mt-0"
            :webhook-receiver="$page.props.webhookReceiver"
          />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout";
import JetSectionTitle from "@/Jetstream/SectionTitle";
import JetActionSection from "@/Jetstream/ActionSection";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetFormSection from "@/Jetstream/FormSection";
import UpdateMessageFormatForm from "./UpdateMessageFormatForm";
import DeleteWebhookReceiverForm from "./DeleteWebhookReceiverForm";

export default {
  components: {
    AppLayout,
    JetSectionTitle,
    JetActionSection,
    JetSectionBorder,
    JetButton,
    JetInput,
    JetFormSection,
    UpdateMessageFormatForm,
    DeleteWebhookReceiverForm,
  },

  props: {
    webhookReceiver: Object,
  },

  setup() {
    const copyLabel = ref("複製");

    const onSuccess = () => {
      copyLabel.value = "複製成功！";
      setTimeout(() => (copyLabel.value = "複製"), 1500);
    };

    return {
      copyLabel,
      onSuccess,
    };
  },

  data() {
    return {
      processing: false,
    };
  },

  methods: {
    reconnectWebhookReceiver() {
      this.$inertia.post(
        route("webhooks.relink"),
        { id: this.webhookReceiver.id },
        {
          errorBag: "botLink",
        }
      );
    },
  },
};
</script>
