<script setup>
import { ref } from 'vue'

const emit = defineEmits(['formSubmit'])

const title = ref('')
const description = ref('')
const location = ref('')
const contact = ref('')
const imageUrl = ref('')

function handleFileUpload(event) {
  const file = event.target.files[0]

  if (!file) {
    return
  }

  const reader = new FileReader()

  reader.onload = () => {
    imageUrl.value = reader.result
  }

  reader.readAsDataURL(file)
}

function submitForm() {
  emit('formSubmit', {
    title: title.value,
    description: description.value,
    location: location.value,
    contact: contact.value,
    imageUrl: imageUrl.value
  })
}
</script>

<template>
  <form class="marketplace-form" @submit.prevent="submitForm">
    <label>Artikel * :</label>
    <input v-model="title" type="text" required>

    <label>Beschreibung * :</label>
    <input v-model="description" type="text" required>

    <label>Standort * :</label>
    <input v-model="location" type="text" required>

    <label>Email oder Telefonnummer * :</label>
    <input v-model="contact" type="text" required>

    <label>Bild auswählen * :</label>
    <input
      class="file-input"
      type="file"
      accept="image/*"
      @change="handleFileUpload"
    >

    <button class="submit-button" type="submit">
      erstellen
    </button>
  </form>
</template>

<style>
.marketplace-form {
  width: 500px;
  display: flex;
  flex-direction: column;
}

.marketplace-form label {
  font-size: 20px;
  margin-top: 22px;
  color: white;
}

.marketplace-form input {
  width: 470px;
  height: 32px;
  border: 1px solid gray;
  background-color: white;
  font-size: 18px;
  outline: none;
}

.marketplace-form .file-input {
  border: none;
  background-color: transparent;
  font-size: 16px;
  height: auto;
}

.submit-button {
  margin-top: 25px;
  width: 120px;
  background-color: #483826;
  color: white;
  border: none;
  border-radius: 12px;
  padding: 14px 25px;
  font-size: 18px;
  cursor: pointer;
}
</style>