<template>
  <div>
    <div
      v-if="!links.prev"
      class="
        inline-flex
        items-center
        px-4
        py-2
        bg-gray-800
        border border-transparent
        rounded-md
        font-semibold
        text-xs text-white
        uppercase
        tracking-widest
        opacity-25
        transition
      "
    >
      上一頁
    </div>
    <inertia-link
      v-else
      class="
        inline-flex
        items-center
        px-4
        py-2
        bg-gray-800
        border border-transparent
        rounded-md
        font-semibold
        text-xs text-white
        uppercase
        tracking-widest
        hover:bg-gray-700
        active:bg-gray-900
        focus:outline-none
        focus:border-gray-900
        focus:ring focus:ring-gray-300
        disabled:opacity-25
        transition
      "
      :href="links.prev"
      preserve-state
      preserve-scroll
    >
      上一頁
    </inertia-link>

    <div
      v-if="!links.next"
      class="
        ml-3
        inline-flex
        items-center
        px-4
        py-2
        bg-gray-800
        border border-transparent
        rounded-md
        font-semibold
        text-xs text-white
        uppercase
        tracking-widest
        opacity-25
        transition
      "
    >
      下一頁
    </div>
    <inertia-link
      v-else
      class="
        ml-3
        inline-flex
        items-center
        px-4
        py-2
        bg-gray-800
        border border-transparent
        rounded-md
        font-semibold
        text-xs text-white
        uppercase
        tracking-widest
        hover:bg-gray-700
        active:bg-gray-900
        focus:outline-none
        focus:border-gray-900
        focus:ring focus:ring-gray-300
        disabled:opacity-25
        transition
      "
      :href="links.next"
      preserve-state
      preserve-scroll
    >
      下一頁
    </inertia-link>
  </div>
</template>

<script>
export default {
  props: {
    links: Object,
  },
};
</script>
