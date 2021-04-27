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
              {{`https://${hostname}/webhookReceiver?token=${$page.props.webhookRecevier.token}`}}

              <div class="mt-5">
                <jet-button
                  type="button"
                  v-clipboard="`https://${hostname}/webhookReceiver?token=${$page.props.webhookRecevier.token}`"
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
              {{$page.props.webhookRecevier.chat.title}}

              <div class="mt-5">
                <jet-button
                  type="button"
                  :disabled="true"
                >
                  重新連結到其他群組
                </jet-button>
              </div>
            </template>
          </jet-action-section>

          <jet-section-border />

          <jet-form-section>
            <template #title>
              機器人名稱
            </template>

            <template #description>

            </template>

            <template #form>
              <div class="col-span-6 sm:col-span-4">
                <jet-input
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  value="Siri"
                  :disabled="true"
                />
              </div>
            </template>

            <template #actions>
              <jet-button :disabled="true">
                儲存修改
              </jet-button>
            </template>
          </jet-form-section>
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

const hostname = window.location.hostname;

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
  },
  setup() {
    const copyLabel = ref("複製");

    const onSuccess = () => {
      copyLabel.value = "複製成功！";
      setTimeout(() => (copyLabel.value = "複製"), 1500);
    };

    return {
      hostname,
      copyLabel,
      onSuccess,
    };
  },
};
</script>
