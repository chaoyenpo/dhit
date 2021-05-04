<template>
  <div
    class="editor"
    ref="dom"
  >
  </div>
</template>

<script setup>
import { onMounted, defineProps, defineEmit, ref } from "vue";
import * as monaco from "monaco-editor/esm/vs/editor/editor.api";

const props = defineProps({
  modelValue: String,
});

const emit = defineEmit(["update:modelValue"]);

const dom = ref();

let instance;

onMounted(() => {
  const modelUri = monaco.Uri.parse("json://grid/settings.json");

  const jsonModel = monaco.editor.createModel(
    JSON.stringify(props.modelValue, null, "\t"),
    "json",
    modelUri
  );

  instance = monaco.editor.create(dom.value, {
    model: jsonModel,
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
