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
    <p>{{ props.item.description }}</p>
    <p>{{ props.item.location }}</p>

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
  background-color: #ddffd7;
  width: 300px;
  padding: 0 0 25px 0;
  text-align: center;
}

.marketplace-card img {
  width: 300px;
  height: 180px;
  object-fit: cover;
  border-radius: 4px;
}

.marketplace-card h2 {
  font-size: 18px;
  margin: 12px 0 5px 0;
  font-weight: normal;
  color: black;
}

.marketplace-card p {
  margin: 5px 0;
  font-size: 16px;
  color: black;
}

.contact-button {
  margin-top: 18px;
  background-color: #003c9e;
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
  color: black;
}
</style>