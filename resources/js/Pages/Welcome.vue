<template>
  <div
    class="
      relative
      flex
      items-top
      justify-center
      min-h-screen
      bg-gray-100
      dark:bg-gray-900
      sm:items-center
      sm:pt-0
    "
  >
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
      <inertia-link
        v-if="$page.props.user"
        href="/dashboard"
        class="text-sm text-gray-700 underline"
      >
        Dashboard
      </inertia-link>

      <div v-else class="flex items-center justify-end">
        <a
          :href="route('login.google')"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          登入
        </a>

        <inertia-link :href="route('register')">
          <jet-button class="ml-4" type="button"> 開始使用 </jet-button>
        </inertia-link>
      </div>
    </div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        後台資訊管理系統
      </div>
    </div>
  </div>
</template>

<script>
import JetButton from "@/Jetstream/Button";

export default {
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
  },
  components: {
    JetButton,
  },
};
</script>
