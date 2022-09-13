<template>
  <div>
    <v-stepper v-model="e6" vertical>
      <v-stepper-step :complete="e6 > 1" step="1">
        Datos personales
        <small>Escribe los datos del miembro</small>
      </v-stepper-step>

      <v-stepper-content step="1">
        <v-form ref="form" v-model="formDatosPersonales">
          <v-card class="mb-12">
            <v-text-field
              label="Nombre completo"
              :rules="reglasForm"
              v-model="datosPersonales.nombre"
            >
            </v-text-field>
            <v-text-field
              label="Teléfono"
              :rules="reglasForm"
              v-model="datosPersonales.telefono"
            >
            </v-text-field>
            <v-text-field
              label="Dirección"
              :rules="reglasForm"
              v-model="datosPersonales.direccion"
            >
            </v-text-field>
            <v-text-field
              label="Edad"
              :rules="reglasForm"
              v-model="datosPersonales.edad"
            >
            </v-text-field>
          </v-card>
          <v-btn
            color="primary"
            @click="e6 = 2"
            :disabled="!formDatosPersonales"
          >
            Continuar
          </v-btn>
          <v-btn text to="/miembros"> Cancelar </v-btn>
        </v-form>
      </v-stepper-content>

      <v-stepper-step :complete="e6 > 2" step="2">
        Datos de contacto
        <small
          >Escribe los siguientes datos en caso de que algun accidente
          ocurra</small
        >
      </v-stepper-step>

      <v-stepper-content step="2">
        <v-form ref="form" v-model="formDatosContacto">
          <v-card class="mb-12">
            <v-switch
              v-model="datosContacto.sufreEnfermedad"
              label="¿Sufre alguna enfermedad crónica?"
            ></v-switch>
            <v-text-field
              label="Escribe las enfermedades"
              v-if="datosContacto.sufreEnfermedad"
              v-model="datosContacto.enfermedad"
            ></v-text-field>
            <v-switch
              v-model="datosContacto.tieneSeguro"
              label="¿Tiene seguro?"
            ></v-switch>
            <v-text-field
              label="Escribe las institución de seguro"
              v-if="datosContacto.tieneSeguro"
              v-model="datosContacto.institucion"
            ></v-text-field>
            <v-text-field
              label="Nombre completo de contacto"
              :rules="reglasForm"
              v-model="datosContacto.nombreContacto"
            >
            </v-text-field>
            <v-text-field
              label="Teléfono"
              :rules="reglasForm"
              v-model="datosContacto.telefonoContacto"
            >
            </v-text-field>
          </v-card>
          <v-btn color="primary" @click="e6 = 3" :disabled="!formDatosContacto">
            Continuar
          </v-btn>
          <v-btn text @click="e6 = 1"> Regresar </v-btn>
        </v-form>
      </v-stepper-content>

      <v-stepper-step :complete="e6 > 3" step="3">
        Foto
        <small>Escoge alguna foto del miembro</small>
      </v-stepper-step>

      <v-stepper-content step="3">
        <v-card class="mb-12">
          <v-file-input
            :rules="reglaImagen"
            accept="image/png, image/jpeg, image/bmp"
            placeholder="Selecciona una imagen"
            prepend-icon="mdi-camera"
            label="Foto"
            v-model="imagen"
            type="file"
            @change="onSeleccionarImagen" 
          ></v-file-input>
          <div class="d-flex flex-column justify-space-between align-center">
            <img :src="imagenUrl" alt="" aspect-ratio="16/9" width="300" />
          </div>
          <v-card-actions class="justify-center">
            <v-btn color="primary" x-large @click="registrar"> Registrar </v-btn>
            <v-btn text x-large @click="e6 = 2"> Regresar </v-btn>
          </v-card-actions>
        </v-card>
      </v-stepper-content>
    </v-stepper>
  </div>
</template>
<script>
import Utiles from '../../Servicios/Utiles'
export default {
  name: "FormMiembro",
  props: ["miembro"],

  data: () => ({
    formDatosPersonales: false,
    formDatosContacto: false,
    reglasForm: [(value) => !!value || "Debes colocar este campo."],
    reglaImagen: [
      (value) =>
        !value || value.size < 5000000 || "La foto debe pesar menos de 5 MB!",
    ],
    e6: 1,
    imagen: null,
    imagenUrl: null,
    datosPersonales:{
       nombre: "",
        telefono: "",
        direccion: "",
        edad: 10,
    },
    datosContacto: {
      sufreEnfermedad: false,
        tieneSeguro: false,
        enfermedad: "",
        institucion: "",
        nombreContacto: "",
        telefonoContacto: ""
    },

   
  }),

  mounted() {
    //console.log(this.miembro)
    //this.datosPersonales = this.miembro.datosPersonales
    //this.datosContacto = this.miembro.datosContacto
    //this.imagenUrl = this.miembro.imagen
    this.formDatosPersonales = false;
    this.formDatosContacto = false;

  },

  methods: {

    registrar() {
      let miembro = {
        ...this.datosPersonales,
        ...this.datosContacto,
        imagen: this.imagenUrl,
      };

      this.$emit("registrado", miembro)

      console.log(miembro)

      this.formDatosPersonales =  false
      this.formDatosContacto = false,
      this.e6= 1
      this.imagen= null
      this.imagenUrl= null
      this.datosPersonales= {
        nombre: "",
        telefono: "",
        direccion: "",
        edad: 10,
      }
      this.datosContacto = {
        sufreEnfermedad: false,
        tieneSeguro: false,
        enfermedad: "",
        institucion: "",
        nombreContacto: "",
        telefonoContacto: "",
      }
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

  watch: {
    miembro:{ 
      deep: true,
      handler() {
        this.datosPersonales = this.miembro.datosPersonales
        this.datosContacto = this.miembro.datosContacto
        this.imagenUrl = Utiles.generarURL(this.miembro.imagen)
        
       }
    }
  }

};
</script>