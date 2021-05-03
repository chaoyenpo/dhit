<template>
  <jet-form-section @submitted="updateWebhookReceiverDql">
    <template #title>
      訊息格式設定
    </template>

    <template #description>
      可以格式化要發送的訊息。
      <br>
      <br>
      <pre class="text-md bg-white p-4">
// 若填入以下內容：
{
    "user": {
        "name": {}
    }
}

// input:
{
    "user": {
        "name": "fish",
        "age": 27
    }
}

// output:
{
    "user": {
        "name": "fish"
    }
}</pre>
      <br>
      <pre class="text-md bg-white p-4">
// 如果要全部內容轉送請填入空的大括號即可：
{}</pre>
    </template>

    <template #form>
      <div class="col-span-6 h-80">
        <json-editor v-model="form.dql"></json-editor>
      </div>
    </template>

    <template #actions>
      <jet-input-error :message="form.errors.dql" />

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
import JsonEditor from "@/Components/JsonEditor";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JsonEditor,
  },

  props: ["webhookReceiver"],

  data() {
    return {
      form: this.$inertia.form({
        dql: this.webhookReceiver.dql,
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
