<template>
  <div class="editor" ref="dom"></div>
</template>

<script setup>
import { onMounted, defineProps, defineEmits, ref } from "vue";
import * as monaco from "monaco-editor/esm/vs/editor/editor.api";

const props = defineProps({
  modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

const dom = ref();

let instance;

onMounted(() => {
  const textModel = monaco.editor.createModel(props.modelValue, "text");

  instance = monaco.editor.create(dom.value, {
    model: textModel,
    scrollBeyondLastLine: false,
    minimap: {
      enabled: false,
    },
  });

  instance.onDidChangeModelContent(() => {
    const value = instance.getValue();
    emit("update:modelValue", value);
  });
});
</script>

<style scoped>
.editor {
  height: 100%;
}
</style>
