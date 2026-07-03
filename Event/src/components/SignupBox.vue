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

async function signIn() {
  try {
    const response = await axios.post(`${API_URL}/login`, {
      email: loginEmail.value,
      password: loginPassword.value
    })

    
    console.log(response.data)
    emit('login-success')
  } catch (error) {
    console.error(error.response?.data || error)
    alert('Sign In fehlgeschlagen.')
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
      password: password.value
    })

    console.log(response.data)
    emit('register-success')
  } catch (error) {
    console.error(error.response?.data || error)
    alert('Sign-up fehlgeschlagen.')
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
</style>