<script setup>
const props = defineProps({
  event: Object
})

const emit = defineEmits(['join'])

function joinEvent() {
  emit('join', props.event.id)
}
</script>

<template>
  <div class="event-card">
    <h2>{{ props.event.title }}</h2>
    <p>{{ props.event.date }}</p>
    <p>{{ props.event.time }}</p>
    <p>{{ props.event.location }}</p>
    <p>{{ props.event.participants }} Teilnehmer</p>

    
    <button class="join-button" @click="joinEvent">
      Beitreten
    </button>
  </div>
</template>

<style>
.event-card {
  background-color: #ddffd7;
  width: 300px;
  padding: 30px 20px;
  text-align: center;
}

.event-card h2 {
  font-size: 17px;
}

.event-card p {
  margin: 5px 0;
}

.join-button {
  margin-top: 18px;
  background-color: #003c9e;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 10px 35px;
  cursor: pointer;
}
</style>