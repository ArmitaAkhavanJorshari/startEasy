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
    participants: 0
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
  width: 250px;
  display: flex;
  flex-direction: column;
}

.event-form label {
  font-size: 20px;
  font-weight: bold;
  margin-top: 12px;
}

.event-form input {
  width: 120px;
  border: none;
  border-bottom: 2px solid gray;
  background-color: transparent;
  font-size: 18px;
   outline: none;
}

.submit-button {
  position: absolute;
  bottom: 25px;
  left: 330px;
  background-color: #003c9e;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 14px 45px;
  font-size: 18px;
  cursor: pointer;
}
</style>
