<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Webhook 接收器
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

          <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <inertia-link
              :href="route('webhooks.create')"
              :active="route().current('webhooks.create')"
            >
              <jet-button
                type="button"
                :class="{ 'opacity-25': enabling }"
                :disabled="enabling"
              >
                建立 Webhook 接收器
              </jet-button>
            </inertia-link>

            <div class="mt-6 text-gray-500">
              Webhook 接收器是一種可以將來自外部的消息發布到通訊軟體的一種方法。
            </div>
          </div>

          <div
            v-if="$page.props.webhookReceviers.length > 0"
            class="p-6 sm:px-20 bg-white border-b border-gray-200"
          >
            <div class="text-2xl">
              配置
            </div>

            <div class="mt-6 text-gray-500">
              <div class="flow-root mt-6">
                <ul class="-my-5 divide-y divide-gray-200">
                  <li
                    v-for="webhookRecevier in $page.props.webhookReceviers"
                    :key="webhookRecevier.id"
                    class="py-4"
                  >
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0">
                        <img
                          class="h-8 w-8 rounded-full"
                          src="https://ui-avatars.com/api/?name=TG&color=7F9CF5&background=EBF4FF"
                          alt=""
                        />
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          以 Siri 發布到 {{ webhookRecevier.chat.title }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          {{ webhookRecevier.user.name }} on {{ webhookRecevier.created_at }}
                        </p>
                      </div>
                      <div>
                        <jet-button
                          type="button"
                          :class="{ 'opacity-25': enabling }"
                          :disabled="enabling"
                        >
                          編輯
                        </jet-button>
                      </div>
                    </div>
                  </li>
                </ul>
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

const people = [
  {
    name: "Leonard Krasner",
    handle: "leonardkrasner",
    imageUrl:
      "https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
  },
  {
    name: "Floyd Miles",
    handle: "floydmiles",
    imageUrl:
      "https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
  },
  {
    name: "Emily Selman",
    handle: "emilyselman",
    imageUrl:
      "https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
  },
  {
    name: "Kristin Watson",
    handle: "kristinwatson",
    imageUrl:
      "https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
  },
];

const datetime = new Date().toDateString();

const applications = [
  {
    applicant: {
      name: "Ricardo Cooper",
      email: "ricardo.cooper@example.com",
      imageUrl:
        "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
    },

    date: "2020-01-07",
    dateFull: "January 7, 2020",
    stage: "Completed phone screening",
    href: "#",
  },
  {
    applicant: {
      name: "Kristen Ramos",
      email: "kristen.ramos@example.com",
      imageUrl:
        "https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
    },
    date: "2020-01-07",
    dateFull: "January 7, 2020",
    stage: "Completed phone screening",
    href: "#",
  },
  {
    applicant: {
      name: "Ted Fox",
      email: "ted.fox@example.com",
      imageUrl:
        "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
    },
    date: "2020-01-07",
    dateFull: "January 7, 2020",
    stage: "Completed phone screening",
    href: "#",
  },
];

export default {
  components: {
    AppLayout,
    JetButton,
    JetResponsiveNavLink,
  },
  setup() {
    return {
      people,
      datetime,
    };
  },
};
</script>
