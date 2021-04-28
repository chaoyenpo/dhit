<template>
  <jet-action-section>
    <template #title>
      刪除 Webhook 接收器
    </template>

    <template #description>
      將會永久刪除 Webhook 接收器。
    </template>

    <template #content>
      <div>
        <jet-danger-button @click="confirmTeamDeletion">
          刪除 Webhook 接收器
        </jet-danger-button>
      </div>

      <!-- Delete Team Confirmation Modal -->
      <jet-confirmation-modal
        :show="confirmingTeamDeletion"
        @close="confirmingTeamDeletion = false"
      >
        <template #title>
          刪除 Webhook 接收器
        </template>

        <template #content>
          您確定要刪除此配置嗎？
        </template>

        <template #footer>
          <jet-secondary-button @click="confirmingTeamDeletion = false">
            取消
          </jet-secondary-button>

          <jet-danger-button
            class="ml-2"
            @click="deleteTeam"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            刪除 Webhook 接收器
          </jet-danger-button>
        </template>
      </jet-confirmation-modal>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  props: ["webhookReceiver"],

  components: {
    JetActionSection,
    JetConfirmationModal,
    JetDangerButton,
    JetSecondaryButton,
  },

  data() {
    return {
      confirmingTeamDeletion: false,
      deleting: false,

      form: this.$inertia.form(),
    };
  },

  methods: {
    confirmTeamDeletion() {
      this.confirmingTeamDeletion = true;
    },

    deleteTeam() {
      this.form.delete(route("webhooks.destroy", this.webhookReceiver), {
        errorBag: "deleteWebhookReceiver",
      });
    },
  },
};
</script>
