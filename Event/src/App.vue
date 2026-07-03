<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import EventCard from './components/EventCard.vue'
import EventForm from './components/EventForm.vue'
import SignupBox from './components/SignupBox.vue'

const showCreateForm = ref(false)
const showSignup = ref(false)
const event2 = ref(null)

const events = ref([])

async function loadEvents() {
  const response = await axios.get('http://127.0.0.1:8000/api/events')
  events.value = response.data
}

function openCreateForm() {
  showCreateForm.value = true
  showSignup.value = false
}

function closeSignup() {
  showSignup.value = false
}

async function addEvent(newEvent) {
  event2.value = newEvent
  showSignup.value = true
}
async function savePendingEvent() {
  if (!event2.value) {
    return
  }

  const response = await axios.post('http://127.0.0.1:8000/api/events', event2.value)

  events.value.push(response.data)
  event2.value = null
  showSignup.value = false
  showCreateForm.value = false
}

async function joinEvent(id) {
  const response = await axios.post(`http://127.0.0.1:8000/api/events/${id}/join`)

  const index = events.value.findIndex(event => event.id === id)
  events.value[index] = response.data
}

onMounted(() => {
  loadEvents()
})
</script>

<template>
  <main class="page">
    <header class="header">
      <h1>Events</h1>

      <div class="location-box">
        <p>+ Standort</p>
        <p>Bochum</p>
      </div>

      <button class="create-button" @click="openCreateForm">
        + Neues Event erstellen
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
  @login-success="savePendingEvent"
  @register-success="savePendingEvent"
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
  margin-left: 40px;
  font-size: 24px;
}

.location-box p {
  margin: 0 0 10px 0;
}

.create-button {
  background-color: #2d007d;
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
}

.create-area {
  position: relative;
  width: 700px;
  min-height: 330px;
  margin: 100px auto 0;
  background-color: #ddffd7;
  padding: 30px;
}
</style>