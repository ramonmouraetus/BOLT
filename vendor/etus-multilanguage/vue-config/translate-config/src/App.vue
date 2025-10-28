<template>
  <VaTabs v-model="selectedTab">
    <template #tabs>
      <VaTab v-for="(title, index) in data.langs" :key="index" :name="index">
        {{ title }}
      </VaTab>
    </template>
  </VaTabs>

  <div class="translation-card-wrapper">
    <div class="translation-title">Traduções para a língua: <b>{{ data.langs[selectedTab] }}</b></div>
    <VaForm ref="formRef" class="translate-form">
      <div class="form-content">
        <div v-for="translate_key in translation_keys" :key="translate_key" class="form-row">
          <label :for="translate_key">
            {{ translate_key }}
          </label>
          <VaTextarea
              v-model="data.translations[data.langs[selectedTab]][translate_key]"
              style="max-width: 300px;"
              auto-grow
          />
        </div>
      </div>
    </VaForm>

    <div class="form-send">
      <VaButton :loading="isTranslating" color="success" @click="translateAll" :disabled="isTranslating">
        Traduzir tudo automaticamente para: {{ data.langs[selectedTab] }}
      </VaButton>
      <VaButton @click="submit()">
        Salvar Traduções
      </VaButton>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, reactive, shallowRef } from 'vue';
import { watch, toRaw } from 'vue';
import { useToast } from "vuestic-ui";

const toast = useToast();
const selectedTab = ref(0);
const isTranslating = ref(false);
const data = shallowRef(window.wpData || {});
data.value.langs = data.value.langs.filter(l => l !=='pt_BR');
data.value.translations = reactive(data.value.translations || {});

const keys = Object.keys(data.value.translations);
data.value.langs.forEach(l => {
  if (!keys.includes(l) || typeof data.value.translations[l] !== 'object') {
    data.value.translations[l] = {};
  }
});

const translation_keys = computed(() => {
  return [...data.value.translation_keys];
});

watch(() => selectedTab.value, () => console.log('mudou', selectedTab.value));

async function translateAll() {
  const lang = data.value.langs[selectedTab.value];

  if (!lang) {
    toast.init({ message: "Error: No language selected!", color: "danger" });
    return;
  }

  if (!data.value.translations[lang]) {
    data.value.translations[lang] = {};
  }

  const keysToTranslate = translation_keys.value.filter(
      (key) => !data.value.translations[lang][key]
  );

  if (keysToTranslate.length === 0) {
    toast.init({ message: "Todas as chaves já estão traduzidas!", color: "info" });
    return;
  }

  toast.init({ message: `Traduzindo ${keysToTranslate.length} itens...`, color: "info" });
  isTranslating.value = true;
  const jsonToTranslate = {};
  keysToTranslate.forEach(key => {
    jsonToTranslate[key] = key;
  });

  try {
    let response = await fetch(data.value.ajaxurl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "etus_translate_json",
        etus_nonce: data.value.etus_nonce,
        lang: lang,
        json: JSON.stringify(jsonToTranslate)
      })
    });

    let result = await response.json();
    if (result.success) {
      let translations = result.data.translations;

      Object.keys(translations).forEach(key => {
        data.value.translations[lang][key] = translations[key];
      });

      toast.init({ message: "Tradução concluída!", color: "success" });

    } else {
      toast.init({ message: "Erro na tradução!", color: "danger" });
    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    toast.init({ message: "Erro ao se conectar ao servidor!", color: "danger" });
  } finally {
    isTranslating.value = false;
  }
}

async function submit() {
  try {
    let response = await fetch(data.value.ajaxurl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "etus_update_translations",
        etus_nonce: data.value.etus_nonce,
        data: JSON.stringify({ "translations": data.value.translations })
      })
    });

    let result = await response.json();
    if (!!result) toast.init({ message: 'Dados atualizados com sucesso!' });

  } catch (error) {
    console.error("Erro na requisição:", error);
  }
}
</script>

<style >
@import"https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;1,700&display=swap";
@import"https://fonts.googleapis.com/icon?family=Material+Icons";

.va-toast--top-right {
  top: 30px !important;
}

.translation-title {
  font-size: 20px;
  font-weight: 600;
  margin: 15px 0;
  text-align: left;
}

form {
  margin-top: 40px;
  max-width: 600px;
}
.form-row label{
  display: flex;
    align-items: center;
    margin-bottom: 10px;
    justify-content: end;
    margin-right: 20px;
    font-weight: 600;
}
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-bottom: 10px;
  text-align: right;
}
.form-send {
  margin: 50px 0;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}
.translation-card {
  display: block;
  padding-top: 30px;
}
.alert {
  max-width: 800px;
  margin: auto 50px;
}
.va-toast {
  top: 50px !important;
}
</style>
