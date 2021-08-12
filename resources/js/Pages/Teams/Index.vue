<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        專案列表
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <team-table />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";
import JetActionSection from "@/Jetstream/ActionSection";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import JetCheckbox from "@/Jetstream/Checkbox";
import TeamTable from "./TeamTable";

export default {
  components: {
    AppLayout,
    JetButton,
    JetResponsiveNavLink,
    JetActionSection,
    JetSectionBorder,
    JetCheckbox,
    TeamTable,
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
