<template>
  <jet-form-section @submitted="updateTeamName">
    <template #title> 團隊名稱 </template>

    <template #description> 顯示團隊名稱和一些資訊。 </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="hidden col-span-6">
        <jet-label value="Team Owner" />

        <div class="flex items-center mt-2">
          <img
            class="w-12 h-12 rounded-full object-cover"
            :src="team.owner.profile_photo_url"
            :alt="team.owner.name"
          />

          <div class="ml-4 leading-tight">
            <div>{{ team.owner.name }}</div>
            <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="團隊名稱" />

        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          :disabled="!permissions.canUpdateTeam"
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

    <template #actions v-if="permissions.canUpdateTeam">
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

  props: ["team", "permissions"],

  data() {
    return {
      form: this.$inertia.form({
        name: this.team.name,
        permissions: this.team.features,
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
    updateTeamName() {
      this.form.put(route("teams.update", this.team), {
        errorBag: "updateTeamName",
        preserveScroll: true,
      });
    },
  },
};
</script>
