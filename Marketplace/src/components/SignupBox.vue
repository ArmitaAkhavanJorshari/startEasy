<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['authSuccess'])

const API_URL = 'http://127.0.0.1:8000/api'

const loginEmail = ref('')
const loginPassword = ref('')

const name = ref('')
const vorname = ref('')
const email = ref('')
const password = ref('')

const errorMessage = ref('')
const successMessage = ref('')

function getToken(response) {
  return response.data.token ||
         response.data.access_token ||
         response.data.accessToken ||
         response.data.plainTextToken
}

async function signIn() {
  try {
    errorMessage.value = ''
    successMessage.value = ''

    const response = await axios.post(`${API_URL}/login`, {
      email: loginEmail.value,
      password: loginPassword.value
    })

    const token = getToken(response)

    if (!token) {
      errorMessage.value = 'Kein Token erhalten.'
      return
    }

    localStorage.setItem('token', token)
    emit('authSuccess', token)
  } catch (error) {
    console.error(error.response?.data || error)

    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else if (error.response?.data?.errors) {
      const errors = error.response.data.errors
      errorMessage.value = Object.values(errors).flat().join(' ')
    } else {
      errorMessage.value = 'Sign In fehlgeschlagen.'
    }
  }
}

async function signUp() {
  try {
    errorMessage.value = ''
    successMessage.value = ''

    const fullName = `${vorname.value} ${name.value}`.trim()

    await axios.post(`${API_URL}/register`, {
      name: fullName,
      email: email.value,
      password: password.value,
      password_confirmation: password.value
    })

    successMessage.value = 'Registrierung erfolgreich. Bitte melde dich jetzt mit Sign-in an.'

    // Email direkt ins Sign-in-Feld übernehmen
    loginEmail.value = email.value
    loginPassword.value = ''

    // Sign-up Passwortfeld leeren
    password.value = ''
  } catch (error) {
    console.error(error.response?.data || error)

    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else if (error.response?.data?.errors) {
      const errors = error.response.data.errors
      errorMessage.value = Object.values(errors).flat().join(' ')
    } else {
      errorMessage.value = 'Sign-up fehlgeschlagen.'
    }
  }
}
</script>

<template>
  <div class="signup-box">

    <div class="signin-part">
      <h2>Sign In</h2>

      <input v-model="loginEmail" type="email" placeholder="Email">
      <input v-model="loginPassword" type="password" placeholder="Password">

      <button class="signin-button" type="button" @click="signIn">
        Sign In
      </button>
    </div>

    <div class="divider"></div>

    <div class="signup-part">
      <h2>Sign Up</h2>

      <input v-model="name" type="text" placeholder="Name">
      <input v-model="vorname" type="text" placeholder="Vorname">
      <input v-model="email" type="email" placeholder="Email">
      <input v-model="password" type="password" placeholder="Password">

      <button class="signup-button" type="button" @click="signUp">
        Sign Up
      </button>
    </div>

    <p v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </p>

    <p v-if="successMessage" class="success-message">
     {{ successMessage }}
    </p>

  </div>
</template>

<style>
.signup-box {
  position: absolute;
  top: 20px;
  left: 160px;
  width: 520px;
  min-height: 260px;
  background-color: #d7ffd7;
  border: 5px solid white;
  padding: 25px;
  box-shadow: -25px 15px 30px gray;
  display: flex;
  gap: 25px;
}

.signin-part,
.signup-part {
  width: 230px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}



.signin-part h2,
.signup-part h2 {
  margin: 0 0 10px 0;
  text-align: center;
}

.divider {
  width: 2px;
  background-color: white;
}

.signup-box input {
  border: none;
  border-bottom: 2px solid gray;
  background-color: transparent;
  font-size: 20px;
  padding: 5px;
  outline: none;
}

.signin-button,
.signup-button {
  width: 120px;
  margin: 10px auto 0;
  background-color: #003c9e;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 10px;
  font-size: 16px;
  cursor: pointer;
}

.error-message {
  position: absolute;
  right: -170px;
  top: 30px;
  width: 150px;
  color: red;
  font-weight: bold;
}

.success-message {
  position: absolute;
  right: -220px;
  top: 85px;
  width: 200px;
  color: green;
  font-weight: bold;
}
</style>