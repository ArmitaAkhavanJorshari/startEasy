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


const showRegisterSuccess = ref(false)



function getErrorMessage(error, fallbackMessage) {
  if (error.response?.data?.errors) {
    return Object.values(error.response.data.errors).flat().join('\n')
  }

  if (error.response?.data?.message) {
    return error.response.data.message
  }

  return fallbackMessage
}

function getToken(response) {
  return response.data.token ||
         response.data.access_token ||
         response.data.accessToken ||
         response.data.plainTextToken
}

function saveToken(response) {
  const token = getToken(response)

  if (!token) {
    alert('Kein Token erhalten.')
    return
  }

  localStorage.setItem('token', token)
  emit('authSuccess', token)
}

async function signIn() {
  try {
    const response = await axios.post(`${API_URL}/login`, {
      email: loginEmail.value,
      password: loginPassword.value
    })

    saveToken(response)
  } catch (error) {
    console.error(error.response?.data || error)
    alert(getErrorMessage(error, 'Sign-in fehlgeschlagen.'))
  }
}

async function signUp() {
  try {
    if (!name.value || !vorname.value || !email.value || !password.value) {
      alert('Bitte alle Felder ausfüllen.')
      return
    }

    const fullName = `${vorname.value} ${name.value}`.trim()

    const response = await axios.post(`${API_URL}/register`, {
      name: fullName,
      email: email.value,
      password: password.value,
      password_confirmation: password.value
    })

    showRegisterSuccess.value = true

    setTimeout(() => {
      showRegisterSuccess.value = false
      saveToken(response)
    }, 2000)
  } catch (error) {
    console.error(error.response?.data || error)
    alert(getErrorMessage(error, 'Sign-up fehlgeschlagen.'))
  }
}
</script>

<template>
  <div class="signup-box">

    <div class="signin-part">
      <h2>Sign-in</h2>

      <input v-model="loginEmail" type="email" placeholder="email:">
      <input v-model="loginPassword" type="password" placeholder="password:">

      <button class="signin-button" type="button" @click="signIn">
        Sign-in
      </button>
    </div>

    <div class="divider"></div>

    <div class="signup-part">
      <h2>Sign-up</h2>

      <input v-model="name" type="text" placeholder="Name">
      <input v-model="vorname" type="text" placeholder="Vorname">
      <input v-model="email" type="email" placeholder="email:">
      <input v-model="password" type="password" placeholder="password:">

      <button class="signup-button" type="button" @click="signUp">
        Sign-up
      </button>
    </div>

    <div v-if="showRegisterSuccess" class="register-success">
      <div class="checkmark">✓</div>
      <h3>Registrierung erfolgreich!</h3>
      <p>Willkommen bei StartEasy!</p>
    </div>

  </div>
</template>

<style>
.signup-box {
  position: absolute;
  top: 20px;
  left: 160px;
  width: 520px;
  min-height: 260px;
  background-color: #846c4b;
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
  border-bottom: 2px solid white;
 
  font-size: 20px;
  padding: 5px;
  outline: none;
  
}

.signin-button,
.signup-button {
  width: 120px;
  margin: 10px auto 0;
  background-color: #483826;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 10px;
  font-size: 16px;
  cursor: pointer;
}



.register-success {
  position: absolute;
  inset: 0;
  background-color: #f5f5c9;
  color: #483826;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border: 5px solid white;
  animation: popIn 0.4s ease;
  z-index: 10;
}

.checkmark {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background-color: #483826;
  color: white;
  font-size: 48px;
  line-height: 70px;
  text-align: center;
  margin-bottom: 15px;
}

.register-success h3 {
  margin: 5px 0;
  font-size: 26px;
}

.register-success p {
  margin: 0;
  font-size: 18px;
}

@keyframes popIn {
  from {
    transform: scale(0.7);
    opacity: 0;
  }

  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>