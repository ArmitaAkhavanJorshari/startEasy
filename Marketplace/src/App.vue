<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import MarketplaceCard from './components/MarketplaceCard.vue'
import MarketplaceForm from './components/MarketplaceForm.vue'
import SignupBox from './components/SignupBox.vue'

const API_URL = 'http://127.0.0.1:8000/api'

const showCreateForm = ref(false)
const showSignup = ref(false)

const token = ref(localStorage.getItem('token'))
function logout() {
  localStorage.removeItem('token')
  token.value = null
  showSignup.value = false
}
const pendingAdvertisement = ref(null)

const defaultItems = [
  {
    id: 1,
    title: "Zwischenmiete",
    description: "1 Zimmer ",
    location: "Laerholzstr.21",
    contact: "test@email.de",
    image: "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85"
  },
  {
    id: 2,
    title: "Fahrrad",
    description: "preis: 40€",
    location: "Bochum",
    contact: "017612345678",
    image: "https://images.unsplash.com/photo-1485965120184-e220f721d03e"
  }
]

const items = ref(defaultItems)

function openCreateForm() {
  showCreateForm.value = true
  showSignup.value = false
}

async function loadAdvertisements() {
  try {
    const response = await axios.get(`${API_URL}/advertisements`)

    const backendItems = response.data.map(ad => ({
      id: `db-${ad.id}`,
      title: ad.title,
      description: ad.description,
      location: ad.location,
      contact: ad.contact,
      image: ad.image_url || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3'
    }))

    items.value = [...defaultItems, ...backendItems]
  } catch (error) {
    console.error('Fehler beim Laden der Anzeigen:', error)
  }
}

async function storeAdvertisement(formData) {
  try {
    await axios.post(
      `${API_URL}/advertisements`,
      {
        title: formData.title,
        description: formData.description,
        location: formData.location,
        contact: formData.contact,
        image_url: formData.imageUrl
      },
      {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      }
    )

    pendingAdvertisement.value = null
    showSignup.value = false
    showCreateForm.value = false

    await loadAdvertisements()
  } catch (error) {
    console.error('Fehler beim Speichern der Anzeige:', error.response?.data || error)
    if (error.response?.status === 401 || error.response?.status === 419) {
    localStorage.removeItem('token')
    token.value = null
    pendingAdvertisement.value = formData
    showSignup.value = true
    return
  }

  alert('Anzeige konnte nicht gespeichert werden.')
}
  
}

async function handleFormSubmit(formData) {
  if (!token.value) {
    pendingAdvertisement.value = formData
    showSignup.value = true
    return
  }

  await storeAdvertisement(formData)
}

async function handleAuthSuccess(newToken) {
  token.value = newToken
  localStorage.setItem('token', newToken)

  if (pendingAdvertisement.value) {
    await storeAdvertisement(pendingAdvertisement.value)
  } else {
    showSignup.value = false
  }
}

onMounted(() => {
  loadAdvertisements()
})
</script>

<template>
  <main class="page">
    <header class="header">
      <h1>Marketplace</h1>

      <div class="location-box">
        <p>+ Standort</p>
        <p>Bochum</p>
      </div>

      <div class="login-status">
        <p v-if="token">Angemeldet</p>
        <p v-else>Nicht angemeldet</p>

        <button v-if="token" class="logout-button" @click="logout">
          Logout
        </button>
      </div>

      <button class="create-button" @click="openCreateForm">
        + Neue Anzeige erstellen
      </button>
    </header>

    <section class="items">
      <MarketplaceCard
        v-for="item in items"
        :key="item.id"
        :item="item"
      />
    </section>

    <section v-if="showCreateForm" class="create-area">
      <MarketplaceForm  @formSubmit="handleFormSubmit" />

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
  width: 330px;
  height: 58px;
  font-size: 20px;
  cursor: pointer;

  display: flex;
  align-items: center;
  justify-content: center;

  white-space: nowrap;
  flex-shrink: 0;
}

.items {
  display: flex;
  justify-content: center;
  gap: 60px;
  margin-top: 110px;
}

.create-area {
  position: relative;
  width: 700px;
  min-height: 470px;
  margin: 100px auto 0;
  background-color: #ddffd7;
  padding: 30px;
}

.login-status {
  font-size: 16px;
  margin-right: 30px;
  text-align: center;
}

.login-status p {
  margin: 0 0 8px 0;
  font-weight: bold;
}

.logout-button {
  background-color: #2d007d;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 8px 18px;
  cursor: pointer;
}

.header h1,
.location-box p,
.login-status p {
  color: black;
}
</style>