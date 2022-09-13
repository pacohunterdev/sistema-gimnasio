<template>
  <v-footer  padless class="mt-5">
    <v-card class="flex" flat tile>

      <v-card-text class="py-2 text-center">
        {{ new Date().getFullYear() }} — <img src="@/assets/logo.png" width="100">
      </v-card-text>
    </v-card>
  </v-footer>
</template>
<script>
export default {
  name: "PieComponent",
};
</script>