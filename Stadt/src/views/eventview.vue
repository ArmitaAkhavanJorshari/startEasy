<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import EventCard from '../components/EventCard.vue'
import EventForm from '../components/EventForm.vue'
import SignupBox from '../components/SignupBox.vue'

const API_URL = 'http://127.0.0.1:8000/api'

const showCreateForm = ref(false)
const showSignup = ref(false)

const events = ref([])
const pendingEvent = ref(null)
const token = ref(localStorage.getItem('token'))

async function loadEvents() {
  try {
    const response = await axios.get(`${API_URL}/events`)
    events.value = response.data
  } catch (error) {
    console.error(error.response?.data || error)
    alert('Events konnten nicht geladen werden.')
  }
}

function openCreateForm() {
  showCreateForm.value = true
  showSignup.value = false
}

function saveToken(newToken) {
  token.value = newToken
  localStorage.setItem('token', newToken)
}

function signOut() {
  localStorage.removeItem('token')
  token.value = null
  showSignup.value = false
  pendingEvent.value = null
}

async function addEvent(newEvent) {
  pendingEvent.value = newEvent

  if (!token.value) {
    showSignup.value = true
    return
  }

  await saveEvent()
}

async function saveEvent() {
  if (!pendingEvent.value) {
    return
  }

  try {
    const response = await axios.post(
      `${API_URL}/events`,
      pendingEvent.value,
      {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      }
    )

    events.value.push(response.data)

    pendingEvent.value = null
    showSignup.value = false
    showCreateForm.value = false
  } catch (error) {
    console.error(error.response?.data || error)
    alert(getErrorText(error, 'Event konnte nicht gespeichert werden.'))
  }
}

async function handleAuthSuccess(newToken) {
  saveToken(newToken)

  if (pendingEvent.value) {
    await saveEvent()
  } else {
    showSignup.value = false
  }
}

async function joinEvent(id) {
  if (!token.value) {
    showSignup.value = true
    return
  }

  try {
    const response = await axios.post(
      `${API_URL}/events/${id}/join`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      }
    )

    const index = events.value.findIndex(event => event.id === id)

    if (index !== -1) {
      events.value[index] = response.data
    }
  } catch (error) {
    console.error(error.response?.data || error)
    alert(getErrorText(error, 'Beitreten fehlgeschlagen.'))
  }
}

function getErrorText(error, fallbackMessage) {
  if (error.response?.data?.errors) {
    return Object.values(error.response.data.errors).flat().join('\n')
  }

  if (error.response?.data?.message) {
    return error.response.data.message
  }

  return fallbackMessage
}

onMounted(() => {
  loadEvents()
})
</script>

<template>
  <main class="page">
    <header class="header">
    <div class="login-status">
  <span v-if="token">Angemeldet</span>
  <span v-else>Nicht angemeldet</span>
</div>
      <h1>Events</h1>

      <div class="location-box">
        <p>Bochum</p>
      </div>

      <button class="create-button" @click="openCreateForm">
        + Neues Event erstellen
      </button>
      <button
  v-if="token"
  class="signout-button"
  @click="signOut"
>
  Sign out
</button>
    </header>

    <section class="events">
      <EventCard
        v-for="event in events"
        :key="event.id"
        :event="event"
        @join="joinEvent"
      />
    </section>

    <section v-if="showCreateForm" class="create-area">
      <EventForm @formSubmit="addEvent" />

      <SignupBox
        v-if="showSignup"
        @authSuccess="handleAuthSuccess"
      />
      
    </section>
  </main>
</template>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #edf5a8;
}

.page {
  min-height: 100vh;
  padding: 30px 50px;
}

.header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
}

h1 {
  margin: 0;
  background-color: #f5f5c9;
  padding: 10px 40px 25px 10px;
  font-size: 36px;
}

.location-box {
  margin-right: auto;
  margin-left: 500px;
  font-size: 24px;
}

.location-box p {
  margin: 0 0 10px 0;
 
}

.create-button {
  background-color: #483826;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 18px 40px;
  font-size: 20px;
  cursor: pointer;
}

.events {
  display: flex;
  justify-content: center;
  gap: 60px;
  margin-top: 110px;
  border-radius: 20px;
}

.create-area {
  position: relative;
  width: 700px;
  min-height: 330px;
  margin: 100px auto 0;
  background-color: #483826;
  padding: 30px;
  color: white;

}
.signout-button {
  background-color: #846c4b;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 18px 30px;
  font-size: 20px;
  cursor: pointer;
  margin-left: 15px;
}

.login-status {
  background-color: #f5f5c9;
  color: #483826;
  border: 2px solid #483826;
  border-radius: 20px;
  padding: 10px 20px;
  font-size: 18px;
  margin-right: 20px;
  align-self: center;
}
</style>