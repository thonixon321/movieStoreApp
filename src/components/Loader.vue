<template>
  <div>
    <svg class='loader-container' width='70px' height='70px' viewBox='0 0 52 52'>
      <circle class='path' cx='26px' cy='26px' r='20px' fill='none' stroke-width='4px'></circle>
    </svg>
  </div>
</template>

<script>
export default {

}
</script>

<style>
.loader-container {
  animation: rotate 2s linear infinite;
}

.loader-container .path {
  stroke: #354f88;
  animation: dash 1.5s ease-in-out infinite;
}

@keyframes rotate {
  100% { transform: rotate(360deg); }
}

@keyframes dash {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}

</style>