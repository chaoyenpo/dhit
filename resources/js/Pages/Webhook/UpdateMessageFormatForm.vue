<template>
  <jet-form-section @submitted="updateTeamName">
    <template #title>
      訊息格式設定
    </template>

    <template #description>
      可以格式化要發送的訊息。
    </template>

    <template #form>
      <div class="col-span-6 h-72">
        <json-editor v-model="code"></json-editor>
      </div>
    </template>

    <template #actions>
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
import { ref } from "vue";
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

  props: ["team", "permissions"],

  setup() {
    const code = ref("");

    return {
      code,
    };
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
      }),
    };
  },

  methods: {
    updateTeamName() {
      console.log(this.code);
      //   this.form.put(route("teams.update", this.team), {
      //     errorBag: "updateTeamName",
      //     preserveScroll: true,
      //   });
    },
  },
};
</script>
