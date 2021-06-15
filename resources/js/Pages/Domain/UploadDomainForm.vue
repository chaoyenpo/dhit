<template>
  <jet-form-section @submitted="submit">
    <template #title> 匯入域名與過期資訊 </template>

    <template #description>
      <div class="mt-6 text-gray-500">
        範本連結：
        <a
          href="https://bit.ly/2S8qi8q"
          class="text-sm text-gray-700 underline"
          target="_blank"
        >
          https://bit.ly/2S8qi8q
        </a>
      </div>

      <div class="mt-6 text-gray-500">網域名稱若相同則會覆蓋舊資料。</div>
    </template>

    <template #form>
      <div class="col-span-6">

        <input
          type="file"
          ref="file"
          @input="form.domains = $event.target.files[0]"
        />
        <div class="mt-6 text-gray-500">支援 Excel (.xlsx)</div>

        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
        >
          {{ form.progress.percentage }}

          {#if $form.progress} {$form.progress.percentage}% {/if} %
        </progress>
      </div>
    </template>

    <template #actions>
      <jet-button
        type="submit"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        匯入
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
      domains: null,
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
