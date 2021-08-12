<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        網域到期通知
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <jet-action-section>
          <template #title> 將通知發送到 Telegram 群組 </template>

          <template #description>
            每天早上 7 點，會將網域到期資訊發送到這個指定的 Telegram 群組中。
          </template>

          <template #content>
            {{ $page.props.bot?.chat?.title ?? "請將機器人連接至群組中" }}

            <div class="mt-5">
              <jet-button type="button" @click="connectTelegramGroup">
                連結到群組
              </jet-button>
            </div>
          </template>
        </jet-action-section>

        <jet-section-border />

        <upload-domain-form />

        <div v-if="$page.props.domains?.data?.length > 0">
          <jet-section-border />

          <domain-table />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";
import UploadDomainForm from "./UploadDomainForm";
import JetActionSection from "@/Jetstream/ActionSection";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import JetCheckbox from "@/Jetstream/Checkbox";
import DomainTable from "./DomainTable";

export default {
  components: {
    AppLayout,
    JetButton,
    JetResponsiveNavLink,
    UploadDomainForm,
    JetActionSection,
    JetSectionBorder,
    JetCheckbox,
    DomainTable,
  },

  data() {
    return {
      form: this.$inertia.form(),
      processing: false,
    };
  },

  methods: {
    connectTelegramGroup() {
      this.form.post(route("domains.link"), {
        preserveScroll: true,
        errorBag: "botLink",
        onStart: () => (this.processing = true),
        onSuccess: this.link,
      });
    },

    link() {
      const tgWindow = window.open();

      tgWindow.location.href = this.$page.props.flash.url;

      Echo.private(`webhook.receiver.${this.$page.props.flash.token}`).listen(
        "TelegramConnected",
        (e) => {
          tgWindow.close();

          this.$inertia.get(route("domains"));
        }
      );
    },
  },
};
</script>
