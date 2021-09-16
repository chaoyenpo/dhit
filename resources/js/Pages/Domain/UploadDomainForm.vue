<template>
  <jet-form-section @submitted="submit">
    <template #title> 匯入網域與過期資訊 </template>

    <template #description>
      <div class="mt-6 text-gray-500">
        <a
          href="/domains.csv"
          class="text-sm text-gray-700 underline"
          target="_blank"
        >
          下載 CSV 範本檔案
        </a>
      </div>

      <div class="mt-6 text-gray-500">
        在試算表應用程式 (例如 Google 試算表或 Microsoft Excel) 中開啟 CSV
        檔案。
      </div>
    </template>

    <template #form>
      <div class="col-span-6">
        <input
          type="file"
          ref="file"
          accept=".csv"
          @input="form.file = $event.target.files[0]"
        />

        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
        >
          {{ form.progress.percentage }}

          {#if $form.progress} {$form.progress.percentage}% {/if} %
        </progress>

        <jet-input-error :message="form.errors.file" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <jet-button
        type="submit"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        上傳
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
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

  setup() {
    const file = ref();
    const form = useForm({
      file: null,
    });

    function submit() {
      form.post(route("domains.store"), {
        errorBag: "uploadDomain",
        preserveScroll: true,
        onSuccess: () => {
          file.value.value = "";
        },
      });
    }

    return { form, submit, file };
  },
};
</script>
