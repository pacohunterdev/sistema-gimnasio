<template>
  <div>
    <!-- Agrega el input para la búsqueda -->
    <p>Escanea el código QR para registrar la visita</p>
    <v-text-field v-model="codigoQR" label="Código QR" @keyup.enter="buscarMiembro" />

    <!-- Resto del código... -->

    <v-card class="mx-auto mt-3 px-5" v-if="miembro_no_valido">
      <v-card-title></v-card-title>
      <v-card-actions class="justify-center">
        <h1 style="color: red;">El miembro no cuenta con una membresia activa</h1>
      </v-card-actions>
    </v-card>

    <v-card class="mx-auto mt-3 px-5" v-if="miembro_no_encontrado">
      <v-card-title></v-card-title>
      <v-card-actions class="justify-center">
        <h1 style="color: red;">No se encontró el miembro con el código QR</h1>
      </v-card-actions>
    </v-card>

    <v-card class="mx-auto mt-3 px-5" v-if="visita_registrada">
      <v-card-title></v-card-title>
      <v-card-actions class="justify-center">
        <h1 style="color: green;">Visita registrada</h1>
      </v-card-actions>
    </v-card>

    <!-- ... -->

    <!-- Vuetify Modal para indicar al usuario que el sistema está cargando -->
    <v-dialog v-model="cargando" persistent max-width="300px">
      <v-card color="primary" dark>
        <v-card-text>Registrando visita...</v-card-text>
        <v-progress-linear indeterminate color="white"></v-progress-linear>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import BusquedaMiembro from "../Miembros/BusquedaMiembro.vue";
import RealizarPago from "../Miembros/RealizarPago.vue";
import Utiles from "../../Servicios/Utiles";
import HttpService from "../../Servicios/HttpService";

export default {
  name: "RegistrarVisita",
  components: { BusquedaMiembro, RealizarPago },

  data() {
    return {
      miembro: null,
      mostrarRegistrarVisita: false,
      mostrarRealizarPago: false,
      mostrarDialogoRegular: false,
      cargando: false,
      mensaje: {},
      mostrarMensaje: false,
      matricula: "",
      pagoVisita: "",
      codigoQR: "",
      estadoMiembro: "",
      miembro_no_valido: false,
      miembro_no_encontrado: false,
      visita_registrada: false,
    };
  },

  methods: {
    buscarMiembro() {
      if (this.codigoQR.trim() !== "") {
        this.cargando = true
        this.visita_registrada = false;
        HttpService.registrar({
            metodo: 'buscar_qr',
            visita: { idUsuario: localStorage.getItem('idUsuario'), codigoQR: this.codigoQR }
        },'visitas.php')
        .then(resultado =>{
            console.log("Resultado:", resultado)
            if(resultado){
                this.cargando = false
                this.mostrarDialogoRegular = false
                this.mostrarMensaje = true
                this.mensaje = {
                    texto: "Visita regular registrar",
                    color: "success"
                }
            }
            // count the number of results
            let number_of_results = Object.keys(resultado).length;
            // console.log("Number of results:", number_of_results)
            if(number_of_results == 0){
              this.miembroNoEncontrado();
            } else {
              this.miembro = resultado[0];
              console.log("Miembro encontrado:", this.miembro);
  
              this.estadoMiembro = this.miembro.estado;
              // console.log("Estado del miembro:", this.estadoMiembro);
              if (this.estadoMiembro === "ACTIVO") {
                this.registrarVisitaQR();
              } else {
                this.miembroNoValido();
              }
            }
        })
      }
    },

    // Resto de los métodos...
    miembroNoValido() {
      console.log("Miembro no válido");
      this.miembro_no_valido = true;
      this.miembro_no_encontrado = false;
      this.miembro = null;
      this.cargar = false;

      this.codigoQR = "";
    },
    miembroNoEncontrado() {
      console.log("Miembro no encontrado");
      this.miembro_no_encontrado = true;
      this.miembro_no_valido = false;
      this.miembro = null;
      this.cargar = false;

      this.codigoQR = "";
    },
    registrarVisitaQR() {
      console.log("Registrar visita QR");
      this.miembro_no_valido = false;
      this.miembro_no_encontrado = false;

      // this.cargando = true;
      let payload = {
        metodo: "registrar",
        visita: {
          idMiembro: this.miembro.id,
          idMembresia: this.miembro.idMembresia,
          idUsuario: localStorage.getItem("idUsuario"),
        },
      };

      HttpService.registrar(payload, "visitas.php").then((resultado) => {
        if (resultado) {
          this.mostrarMensaje = true;
          this.mensaje = {
            texto: "Visita registrada",
            color: "success",
          };

          this.miembro = null;
          this.mostrarRegistrarVisita = false;
          this.cargando = false;

          this.visita_registrada = true;
          this.codigoQR = "";
        }
      });
    },
  },
};
</script>
