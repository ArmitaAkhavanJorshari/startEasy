<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['login-success', 'register-success'])

const API_URL = 'http://127.0.0.1:8000/api'

const loginEmail = ref('')
const loginPassword = ref('')

const name = ref('')
const vorname = ref('')
const email = ref('')
const password = ref('')

const errorMessage = ref('')
const successMessage = ref('')

async function signIn() {
  try {
    errorMessage.value = ''
    successMessage.value = ''

    await axios.post(`${API_URL}/login`, {
      email: loginEmail.value,
      password: loginPassword.value
    })

    emit('login-success')
  } catch (error) {
    console.error(error.response?.data || error)
    errorMessage.value = 'Sign In fehlgeschlagen.'
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

    emit('register-success')
  } catch (error) {
    console.error(error.response?.data || error)
    errorMessage.value = 'Sign-up fehlgeschlagen.'
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