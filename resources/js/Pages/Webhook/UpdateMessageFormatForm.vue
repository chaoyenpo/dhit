<template>
  <jet-form-section @submitted="updateWebhookReceiverDql">
    <template #title> 訊息模板設定 </template>

    <template #description> JMTE templates。</template>

    <template #form>
      <div class="col-span-6 h-80">
        <text-editor v-model="form.jmte"></text-editor>
      </div>
    </template>

    <template #actions>
      <jet-input-error :message="form.errors.jmte" />

      <jet-action-message :on="form.recentlySuccessful" class="mr-3">
        成功儲存。
      </jet-action-message>

      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        儲存
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import TextEditor from "@/Components/TextEditor";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    TextEditor,
  },

  props: ["webhookReceiver"],

  data() {
    return {
      form: this.$inertia.form({
        jmte: this.webhookReceiver.jmte,
      }),
    };
  },

  methods: {
    updateWebhookReceiverDql() {
      this.form.put(route("webhooks.update", this.webhookReceiver), {
        errorBag: "updateWebhookReceiver",
        preserveScroll: true,
      });
    },
  },
};
</script>
