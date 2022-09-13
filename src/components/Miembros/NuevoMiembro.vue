<template>
  <div>
    <h1>Registrar miembro</h1>
    <breadcrumbs
      :items="[
        { text: 'Inicio', href: '/' },
        { text: 'Miembros', href: '/#/miembros' },
      ]"
    />
    <form-miembro :miembro="datosMiembro" @registrado="onRegistrado" />

    <v-dialog v-model="mostrarMiembroRegistrado" persistent max-width="600">
      <v-card>
        <v-card-title class="text-h5"> Miembro registrado </v-card-title>
        <v-card-text>
          El miembro ha sido registrado con la matricula {{ matricula }}. Puedes
          pagar la membresía en este momento o en la primera visita.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="generarCredencial"
          >
            Generar credencial
          </v-btn>
          <v-btn color="green darken-1" text @click="realizarPago">
            Realizar pago
          </v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="mostrarMiembroRegistrado = false"
          >
            Salir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="mostrarRealizarPago" persistent max-width="600">
      <realizar-pago
        :matricula="matricula"
        @cerrar="cerrarDialogoPago"
        @pagado="onPagado"
      />
    </v-dialog>

    <credencial-miembro :matricula="matricula" :miembro="miembro"  @impreso="onImpreso" v-if="mostrarCredencial"/>

    <v-overlay :value="cargando">
      <v-progress-circular size="64"></v-progress-circular>
    </v-overlay>

    <v-snackbar
      v-model="mostrarMensaje"
      :timeout="3000"
      :color="mensaje.color"
      top
    >
      {{ mensaje.texto }}
    </v-snackbar>
  </div>
</template>
<script>
import FormMiembro from "./FormMiembro.vue";
import Breadcrumbs from "../Dialogos/Breadcrumbs.vue";
import HttpService from "../../Servicios/HttpService";
import RealizarPago from "./RealizarPago.vue";
import CredencialMiembro from "./CredencialMiembro.vue";

export default {
  name: "NuevoMiembro",
  components: { FormMiembro, Breadcrumbs, RealizarPago, CredencialMiembro },

  data: () => ({
    mostrarMensaje: false,
    mensaje: {
      color: "",
      texto: ""
    },
    miembro: {},
    mostrarMiembroRegistrado: false,
    mostrarRealizarPago: false,
    mostrarCredencial: false,
    matricula: "",
    cargando: false,
    datosMiembro: {
        datosPersonales: {
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
        telefonoContacto: "",
      },
      imagen: null
    }
  }),

  methods: {
    generarCredencial(){
      HttpService.obtenerConDatos({
        metodo: 'obtener_imagen',
        matricula: this.matricula
      }, 'miembros.php')
      .then(respuesta => {
        this.miembro.imagen = respuesta.imagen
        this.miembro.fechaRegistro = new Date().toLocaleDateString()
        this.mostrarCredencial = true
      })
     
    },

    onImpreso(resultado) {
      this.mostrarCredencial = resultado;
    },

    onRegistrado(miembro) {
      this.miembro = miembro;
      let payload = {
        metodo: "registrar",
        miembro: this.miembro,
      };

      console.log(this.miembro);
      this.cargando = true;
      HttpService.registrar(payload, "miembros.php").then((registrado) => {
        console.log(registrado);
        if (registrado) {
          this.matricula = registrado;
          this.mostrarMiembroRegistrado = true;
          this.cargando = false;
        }
      });
    },

    realizarPago() {
      this.mostrarMiembroRegistrado = false;
      this.mostrarRealizarPago = true;
    },

    onPagado(resultado) {
      if (resultado) {
        this.mostrarRealizarPago = false
        this.mostrarMensaje = true
        this.mensaje = {
          color: "success",
          texto: "Pago realizado con éxito"
        }
      }
    },

    cerrarDialogoPago(resultado) {
      this.mostrarRealizarPago = resultado;
    },
  },
};
</script>