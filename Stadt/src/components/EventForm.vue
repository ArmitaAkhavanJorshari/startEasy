<script setup>
import { ref } from 'vue'

const emit = defineEmits(['formSubmit'])

const title = ref('')
const date = ref('')
const time = ref('')
const location = ref('')
const description = ref('')

function submitForm() {
  const newEvent = {
    title: title.value,
    date: date.value,
    time: time.value,
    location: location.value,
    description: description.value
  }

  emit('formSubmit', newEvent)
}
</script>

<template>
  <form class="event-form" @submit.prevent="submitForm">
    <label>Titel:</label>
    <input v-model="title" type="text" required>

    <label>Datum:</label>
    <input v-model="date" type="text" required>

    <label>Uhrzeit:</label>
    <input v-model="time" type="text" required>

    <label>Standort:</label>
    <input v-model="location" type="text" required>

    <label>Beschreibung:</label>
    <input v-model="description" type="text">

    <button class="submit-button" type="submit">
      erstellen
    </button>
  </form>
</template>

<style>
.event-form {
   width: 500px;
  display: flex;
  flex-direction: column;
  border-radius: 20px;
}

.event-form label {
  font-size: 20px;
  margin-top: 22px;
  color: white;
}

.event-form input {
  width: 470px;
  height: 32px;
  border: 1px solid gray;
  background-color: white;
  font-size: 18px;
  outline: none;
}

.submit-button {
  position: absolute;
  bottom: 25px;
  left: 330px;
  background-color: #483826;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 14px 45px;
  font-size: 18px;
  cursor: pointer;
}
</style>