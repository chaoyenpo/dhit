<template>
  <template v-if="Array.isArray(message)">
    <div v-for="text in message" :key="text">
      <p class="text-sm text-red-600">
        {{ text }}
      </p>
    </div>
  </template>
  <template v-else>
    <div v-show="message">
      <p class="text-sm text-red-600">
        {{ message }}
      </p>
    </div>
  </template>
</template>

<script>
export default {
  props: ["message"],
};
</script>
