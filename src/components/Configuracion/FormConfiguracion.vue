<template>
  <v-form ref="form" v-model="formDatos">
    <v-card class="mb-12 pa-3">
      <v-text-field
        label="Nombre del gimnasio"
        :rules="reglasForm"
        v-model="datos.nombre"
      >
      </v-text-field>
      <v-text-field
        label="Teléfono"
        :rules="reglasForm"
        v-model="datos.telefono"
      >
      </v-text-field>
      <v-text-field
        label="Dirección"
        :rules="reglasForm"
        v-model="datos.direccion"
      >
      </v-text-field>

      <v-file-input
        :rules="reglaImagen"
        accept="image/png, image/jpeg, image/bmp"
        placeholder="Selecciona una imagen"
        prepend-icon="mdi-camera"
        label="Logo"
        v-model="imagen"
        type="file"
        @change="onSeleccionarImagen"
      ></v-file-input>
      <div class="d-flex flex-column justify-space-between align-center">
        <img :src="imagenUrl" alt="" aspect-ratio="16/9" width="300" />
      </div>
      <v-card-actions class="justify-center">
        <v-btn elevation="14" color="primary"  x-large :disabled="!formDatos" @click="registrar"> Registrar </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>
<script>
import Utiles from '../../Servicios/Utiles'
export default {
  name: "FormConfiguracion",
  props: ["ajustes"],

  data: () => ({
    formDatos: false,
    imagenUrl: null,
    imagen: null,
    datos: {
      nombre: "",
      telefono: "",
      direccion: "",
    },
    reglasForm: [(value) => !!value || "Debes colocar este campo."],
    reglaImagen: [
      (value) =>
        !value || value.size < 5000000 || "La foto debe pesar menos de 5 MB!",
    ],
  }),

  mounted() {
    this.datos = this.ajustes
    this.imagenUrl = Utiles.generarURL(this.ajustes.logo)
  },

  methods: {
    registrar(){
      this.datos.logo = this.imagenUrl


      this.$emit("registrar", this.datos)

    },

    crearImagen(imagen){
      const reader = new FileReader()

      reader.onload = e => {
        this.imagenUrl = e.target.result
      }
      reader.readAsDataURL(imagen)
    },

    onSeleccionarImagen() {
      if (!this.imagen) {
        return
      }
      this.crearImagen(this.imagen);
    }
  },


};
</script>