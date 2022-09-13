<template>
  <v-card>
    <v-card-title class="text-h5"
      >¿Seguro que deseas eliminar {{ nombre }}?</v-card-title
    >
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" text @click="cerrarDialogo">Cancelar</v-btn>
      <v-btn color="blue darken-1" text @click="eliminar">OK</v-btn>
      <v-spacer></v-spacer>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
    name: "DialogoEliminar",
    props: ["nombre"],

    methods: {
        cerrarDialogo(){
            this.$emit("cancelar", false)
        },

        eliminar(){
            this.$emit("eliminar", true)
        }
    }
}
</script>