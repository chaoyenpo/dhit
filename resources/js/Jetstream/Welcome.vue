<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <div class="text-2xl">{{ $page.props.user.name }}，您好：</div>

      <div class="mt-6 text-gray-500">
        目前團隊名稱<br />
        {{ $page.props.user.current_team.name }}

        <pre>{{ JSON.stringify($page.props, null, "\t") }}</pre>
      </div>
    </div>
  </div>
</template>

<script>
import JetApplicationLogo from "@/Jetstream/ApplicationLogo";

export default {
  components: {
    JetApplicationLogo,
  },
};
</script>
