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
            {{ $page.props.bot?.chat?.title || "您尚未連接到任何群組！" }}

            <div class="mt-5">
              <jet-button type="button" @click="connectTelegramGroup">
                連結到群組
              </jet-button>
            </div>
          </template>
        </jet-action-section>

        <jet-section-border />

        <upload-domain-form />

        <jet-section-border />

        <div v-if="$page.props.domains?.length > 0">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div
                class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
              >
                <div
                  class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                >
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          網域名稱
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          標籤
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          到期時間
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          備註
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(domain, domainIdx) in $page.props.domains"
                        :key="domain.name"
                        :class="domainIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                      >
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                          {{ domain.name }}
                        </td>
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ domain.tag }}
                        </td>
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ domain.expired_at }}
                        </td>
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ domain.remark }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
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

export default {
  components: {
    AppLayout,
    JetButton,
    JetResponsiveNavLink,
    UploadDomainForm,
    JetActionSection,
    JetSectionBorder,
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
