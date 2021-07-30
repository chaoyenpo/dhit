<template>
  <jet-form-section @submitted="createUser">
    <template #title> 使用者資訊 </template>

    <template #description> </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="姓名" />
        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autofocus
        />
        <jet-input-error :message="form.errors.name" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="email" value="電子郵件地址" />
        <jet-input
          id="email"
          type="text"
          class="mt-1 block w-full"
          v-model="form.email"
        />
        <jet-input-error :message="form.errors.email" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="organization" value="機構單位" />
        <jet-input
          id="organization"
          type="text"
          class="mt-1 block w-full"
          v-model="form.organization"
        />
        <jet-input-error :message="form.errors.organization" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        新增使用者
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
        name: "",
        email: "",
        organization: "",
      }),
    };
  },

  methods: {
    createUser() {
      this.form.post(route("users.store"), {
        errorBag: "createUser",
        preserveScroll: true,
        onSuccess: () => {
          // this.displayingToken = true
          this.form.reset();
        },
      });
    },
  },
};
</script>
