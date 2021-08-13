<template>
  <jet-form-section @submitted="updateTeamName">
    <template #title> 管理員角色 </template>

    <template #description> 可以指派使用者成為管理員。 {{ user }}</template>

    <template #form>
      <!-- Token Permissions -->
      <div class="col-span-6" v-if="availablePermissions.length > 0">
        <jet-label for="permissions" value="角色" />

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

  props: ["user", "permissions"],

  data() {
    return {
      form: this.$inertia.form({
        permissions: this.user.roles,
      }),
      availablePermissions: [
        {
          name: "超級管理員",
          slug: "super_admin",
        },
        {
          name: "使用者管理員",
          slug: "user_management_admin",
        },
        {
          name: "專案管理員",
          slug: "team_management_admin",
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
