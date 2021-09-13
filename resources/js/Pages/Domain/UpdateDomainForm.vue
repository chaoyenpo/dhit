<template>
  <jet-form-section @submitted="updateDomain">
    <template #title> 網域資訊 </template>

    <template #description> 顯示網域名稱和一些資訊。 </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="網域名稱" />

        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
        />

        <jet-input-error :message="form.errors.name" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="product" value="產品" />

        <jet-input
          id="product"
          type="text"
          class="mt-1 block w-full"
          v-model="form.product"
        />

        <jet-input-error :message="form.errors.product" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="submit" value="提交者" />

        <jet-input
          id="submit"
          type="text"
          class="mt-1 block w-full"
          v-model="form._submit"
        />

        <jet-input-error :message="form.errors._submit" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="dns" value="DNS" />

        <jet-input
          id="dns"
          type="text"
          class="mt-1 block w-full"
          v-model="form.dns"
        />

        <jet-input-error :message="form.errors.dns" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="nameservers1" value="名稱伺服器1" />

        <jet-input
          id="nameservers1"
          type="text"
          class="mt-1 block w-full"
          v-model="form.nameservers[0]"
        />

        <jet-input-error :message="form.errors.nameservers" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="nameservers2" value="名稱伺服器2" />

        <jet-input
          id="nameservers2"
          type="text"
          class="mt-1 block w-full"
          v-model="form.nameservers[1]"
        />

        <jet-input-error :message="form.errors.nameservers" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="nameservers3" value="名稱伺服器3" />

        <jet-input
          id="nameservers3"
          type="text"
          class="mt-1 block w-full"
          v-model="form.nameservers[2]"
        />

        <jet-input-error :message="form.errors.nameservers" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="vendor" value="域名商" />

        <jet-input
          id="vendor"
          type="text"
          class="mt-1 block w-full"
          v-model="form.vendor"
        />

        <jet-input-error :message="form.errors.vendor" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="remark" value="備註" />

        <jet-input
          id="remark"
          type="text"
          class="mt-1 block w-full"
          v-model="form.remark"
        />

        <jet-input-error :message="form.errors.remark" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <jet-action-message :on="form.recentlySuccessful" class="mr-3">
        已儲存。
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
import JetCheckbox from "@/Jetstream/Checkbox";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetCheckbox,
  },

  props: ["domain"],

  data() {
    return {
      form: this.$inertia.form({
        name: this.domain.name,
        domain_expired_at: this.domain.domain_expired_at,
        certificate_expired_at: this.domain.certificate_expired_at,
        product: this.domain.product,
        _submit: this.domain.submit,
        dns: this.domain.dns,
        vendor: this.domain.vendor,
        remark: this.domain.remark,
        nameservers: this.domain.nameservers,
      }),
    };
  },

  methods: {
    updateDomain() {
      this.form.put(route("domains.update", this.domain), {
        errorBag: "updateDomain",
        preserveScroll: true,
      });
    },
  },
};
</script>
