<template>
  <jet-form-section @submitted="createTeam">
    <template #title> 專案資訊 </template>

    <template #description> </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="專案名稱" />
        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autofocus
        />
        <jet-input-error :message="form.errors.name" class="mt-2" />
      </div>

      <!-- Token Permissions -->
      <div class="col-span-6" v-if="availablePermissions.length > 0">
        <jet-label for="permissions" value="功能" />

        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="permission in availablePermissions"
            :key="permission.slug"
          >
            <label class="flex items-center">
              <jet-checkbox
                :value="permission.slug"
                v-model:checked="form.permissions"
              />
              <span class="ml-2 text-sm text-gray-600">
                {{ permission.name }}
              </span>
            </label>
          </div>
        </div>
      </div>
    </template>

    <template #actions>
      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Create
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
import JetCheckbox from "@/Jetstream/Checkbox";

export default {
  components: {
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetCheckbox,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
        permissions: [],
      }),
      availablePermissions: [
        {
          name: "Webhook 接收器",
          slug: "webhook_receiver",
        },
        {
          name: "網域通知服務",
          slug: "domain_notify",
        },
      ],
    };
  },

  methods: {
    createTeam() {
      this.form.post(route("teams.store"), {
        errorBag: "createTeam",
        preserveScroll: true,
      });
    },
  },
};
</script>
