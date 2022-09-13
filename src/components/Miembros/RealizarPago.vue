<template>
  <div>
    <v-card>
      <v-card-title>
        <span class="text-h5">Realizar pago para {{matricula}}</span>
      </v-card-title>
      <v-card-text>
          <v-container>
            <v-select
              v-model="membresiaSeleccionada"
              :hint="`${membresiaSeleccionada.nombre}, ${membresiaSeleccionada.precio}`"
              :items="membresias"
              item-text="nombre"
              item-value="id"
              label="Selecciona una membresía"
              persistent-hint
              return-object
              single-line
            >
            </v-select>

            <v-row justify="center">
              <v-date-picker
                v-model="fechaSeleccionada"
                :first-day-of-week="1"
                locale="es-se"
              >
              </v-date-picker>
            </v-row>
          </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cerrarDialogo">
          Cerrar
        </v-btn>
        <v-btn
          color="blue darken-1"
          text
          @click="realizarPago"
        >
          Registrar
        </v-btn>
      </v-card-actions>
    </v-card>
    <v-overlay :value="cargando">
      <v-progress-circular
        indeterminate
        size="64"
    ></v-progress-circular>
    </v-overlay>

  </div>
  
</template>
<script>
import HttpService from '../../Servicios/HttpService'
export default {
  name: "RealizarPago",
  props: ["matricula"],

  data:()=>({
    membresiaSeleccionada: { id: "", nombre: "", precio: "", duracion: "" },
    fechaSeleccionada: new Date(
      Date.now() - new Date().getTimezoneOffset() * 60000
    )
      .toISOString()
      .substr(0, 10),
    membresias: [],
    cargando: false
  }),

  mounted(){
    this.obtenerMembresias();
  },

  methods:{
    obtenerMembresias() {
      const payload = { metodo: "get" };
      HttpService.obtenerConDatos(payload, "membresias.php").then(
        (respuesta) => {
          this.membresias = respuesta;
        }
      );
    },

    cerrarDialogo(){
      this.$emit("cerrar", false)
    },

    realizarPago(){
      if(!this.membresiaSeleccionada) return
      this.cargando = true
      
      let payload = {
        metodo: 'pagar',
        pago: {
          matricula: this.matricula,
          pago: this.membresiaSeleccionada.precio,
          idMembresia: this.membresiaSeleccionada.id,
          duracion: this.membresiaSeleccionada.duracion,
          fecha: this.fechaSeleccionada,
          idUsuario: localStorage.getItem('idUsuario')
        }
      }


      HttpService.registrar(payload,"miembros.php")
      .then(registrado => {
        if(registrado){
          this.cargando = false
          this.$emit("pagado", registrado)
          console.log(registrado)
        }
      })

      console.log(payload)
    }

  }
};
</script>