<script setup>
import { ref } from 'vue'

const props = defineProps({
  item: Object
})

const showContactInfo = ref(false)

function showContact() {
  showContactInfo.value = !showContactInfo.value
}
</script>

<template>
  <div class="marketplace-card">
    <img :src="props.item.image" alt="Artikel Bild">

    <h2>{{ props.item.title }}</h2>
    <p class="card-description">{{ props.item.description }}</p>
    <p class="card-location">{{ props.item.location }}</p>

    <button class="contact-button" type="button" @click="showContact">
      Kontakt
    </button>

    <p v-if="showContactInfo" class="contact-info">
      Kontakt: {{ props.item.contact }}
    </p>
  </div>
</template>

<style>
.marketplace-card {
  background-color: #846c4b;
  width: 300px;
  padding: 0 0 25px 0;
  text-align: center;
  border-radius: 20px;

  display: flex;
  flex-direction: column;
  align-items: center;
}

.marketplace-card img {
  width: 300px;
  height: 180px;
  object-fit: cover;
  border-radius: 20px;
}

.marketplace-card h2 {
  font-size: 18px;
  margin: 12px 0 5px 0;
  font-weight: normal;
  color: white;
}

.marketplace-card p {
  margin: 5px 0;
  font-size: 16px;
  color: white;
}

.card-description {
  min-height: 72px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 12px;
  line-height: 1.5;
}

.card-location {
  min-height: 24px;
}

.contact-button {
  margin-top: auto;
  background-color: #483826;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 10px 35px;
  cursor: pointer;
}

.contact-info {
  margin-top: 15px;
  font-size: 16px;
  font-weight: bold;
  color: white;
}
</style>