<template>
  <div>
    <busqueda-miembro @seleccionado="onMiembroSeleccionado" />
    <v-card class="mx-auto mt-3 px-5" v-if="!miembro">
      <v-card-title
        >Escribe el nombre o matrícula del miembro o registra una visita
        regular para aquellas personas que solo van una o dos veces a la semana.</v-card-title
      >
      <v-card-actions class="justify-center">
        <v-btn
          x-large
          color="indigo"
          dark
          @click="mostrarDialogoRegular = true"
        >
          <v-icon left> check </v-icon>
          Registrar visita regular
        </v-btn>
      </v-card-actions>
    </v-card>
    <v-card
      class="mx-auto mt-3"
      max-width="400"
      aspect-ratio="16/9"
      v-if="miembro"
    >
      <v-img :src="urlImagen(miembro.imagen)" height="200px"></v-img>

      <v-card-title>
        {{ miembro.nombre }}
      </v-card-title>

      <v-card-subtitle> Matrícula: {{ miembro.matricula }} </v-card-subtitle>
      <v-card-text>
        <v-chip class="ma-2" :color="estado(miembro.estado)" text-color="white">
          {{ miembro.estado }}
          <span v-if="!miembro.estado">SIN MEMBRESÍA</span>
        </v-chip>

        <v-list two-line>
          <v-list-item v-if="miembro.membresia">
            <v-list-item-icon>
              <v-icon color="indigo"> mdi-wallet-membership </v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>
                {{ miembro.membresia }}
              </v-list-item-title>
              <v-list-item-subtitle
                >Válida hasta {{ miembro.fechaFin }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card-text>

      <v-card-actions>
        <div class="mx-auto" v-if="miembro.estado === 'ACTIVO'">
          <v-btn x-large color="primary" dark @click="abrirRegistrarVisita">
            <v-icon left> check </v-icon>
            Registrar visita
          </v-btn>
        </div>
        <div class="mx-auto" v-if="!miembro.membresia || miembro.estado === 'VENCIDO'">
          <v-btn x-large color="warning" dark @click="abrirRealizarPago">
            <v-icon left> check </v-icon>
            Actualizar membresía
          </v-btn>
        </div>
      </v-card-actions>
    </v-card>

    <v-dialog v-model="mostrarRegistrarVisita" max-width="600px" v-if="miembro">
      <v-card>
        <v-card-title class="text-h5"
          >¿Registrar visita del miembro {{ miembro.nombre }}?</v-card-title
        >
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="cerrarDialogo"
            >Cancelar
          </v-btn>
          <v-btn
            color="blue darken-1"
            @click="registrar"
            :loading="cargando"
            text
            :disabled="cargando"
          >
            Registrar visita
            <template v-slot:loader>
              <span>Registrando...</span>
            </template>
          </v-btn>
          <v-spacer></v-spacer>
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

    <v-snackbar
      v-model="mostrarMensaje"
      :timeout="3000"
      :color="mensaje.color"
      top
    >
      {{ mensaje.texto }}
    </v-snackbar>

    <v-dialog v-model="mostrarDialogoRegular" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Registrar visita regular</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-text-field
              label="Escribe el pago de la visita"
              v-model="pagoVisita"
              required
            ></v-text-field>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="mostrarDialogoRegular = false"
          >
            Cerrar
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            :loading="cargando"
            :disabled="cargando"
            @click="registrarVisitaRegular"
          >
            Registrar
            <template v-slot:loader>
              <span>Registrando...</span>
            </template>
          </v-btn>
        </v-card-actions>
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

  data: () => ({
    miembro: null,
    mostrarRegistrarVisita: false,
    mostrarRealizarPago: false,
    mostrarDialogoRegular: false,
    cargando: false,
    mensaje: {},
    mostrarMensaje: false,
    matricula: "",
    pagoVisita:""
  }),

  methods: {
    registrarVisitaRegular(){
        if(!this.pagoVisita) return
        console.log(this.pagoVisita)
        this.cargando = true
        HttpService.registrar({
            metodo: 'registrar_regular',
            visita: { idUsuario: localStorage.getItem('idUsuario'), pago: this.pagoVisita }
        },'visitas.php')
        .then(resultado =>{
            if(resultado){
                this.cargando = false
                this.mostrarDialogoRegular = false
                this.mostrarMensaje = true
                this.mensaje = {
                    texto: "Visita regular registrar",
                    color: "success"
                }
            }
        })
    },

    abrirRealizarPago() {
      this.matricula = this.miembro.matricula;
      this.mostrarRealizarPago = true;
    },

    onPagado(resultado) {
      if (resultado) {
        this.mostrarRealizarPago = false;
        this.mostrarMensaje = true;
        this.mensaje = {
          color: "success",
          texto: "Pago realizado con éxito, Ahora puedes registrar la visita",
        };
        this.miembro = null;
      }
    },

    cerrarDialogoPago() {
      this.mostrarRealizarPago = false;
    },

    cerrarDialogo() {
      this.mostrarRegistrarVisita = false;
    },

    registrar() {
      this.cargando = true;
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
        }
      });
    },

    abrirRegistrarVisita() {
      this.mostrarRegistrarVisita = true;
    },

    urlImagen(imagen) {
      return Utiles.generarURL(imagen);
    },

    onMiembroSeleccionado(miembro) {
      this.miembro = miembro;
    },

    estado(val) {
      return val === "ACTIVO"
        ? "success"
        : val === "VENCIDO"
        ? "error"
        : "warning";
    },
  },
};
</script>