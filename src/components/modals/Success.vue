<template>
  <div class="modalBG">
    <div class='modalCard'>
      Success! Order has been placed.
    </div>
  </div>
</template>

<script>
export default {
  name: 'SuccessModal'
}
</script>

<style scoped>
  .modalBG {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    z-index: 3;
    background: rgba(70, 69, 69, 0.6);
  }

    .modalCard {
      max-width: 35em;
      height: auto;
      padding: 1em 0 0 0;
      border-radius: 5px;
      background: white;
      transition: 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
    }
</style>