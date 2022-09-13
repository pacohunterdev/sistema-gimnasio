<template>
  <v-container>
    <h1>Cambiar contraseña</h1>
    <v-form ref="form" v-model="formValido">
      <v-card elevation="5" class="mx-auto">
        <v-card-text>
          <v-text-field
            v-model="passwordActual"
            :append-icon="mostrarPasswordActual ? 'mdi-eye' : 'mdi-eye-off'"
            :type="mostrarPasswordActual ? 'text' : 'password'"
            name="input-10-1"
            label="Contraseña actual"
            hint="Por lo menos 8 caracteres"
            counter
            @click:append="mostrarPasswordActual = !mostrarPasswordActual"
          ></v-text-field>
          <v-text-field
            v-model="nuevaPassword"
            :append-icon="mostrarNueva ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="mostrarNueva ? 'text' : 'password'"
            name="input-10-1"
            label="Contraseña nueva"
            hint="Por lo menos 8 caracteres"
            counter
            @click:append="mostrarNueva = !mostrarNueva"
          ></v-text-field>
          <v-text-field
            v-model="repitePassword"
            :append-icon="mostrarRepetida ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="mostrarRepetida ? 'text' : 'password'"
            name="input-10-1"
            label="Repite la nueva contraseña"
            hint="Por lo menos 8 caracteres"
            counter
            @click:append="mostrarRepetida = !mostrarRepetida"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          
          <v-spacer></v-spacer>

          <v-btn color="primary" text @click="cambiar" :disabled="!formValido">
            Registrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
    <v-snackbar
      v-model="mostrarMensaje"
      :timeout="3000"
      :color="mensaje.color"
      top
    >
      {{ mensaje.texto }}
    </v-snackbar>
  </v-container>
</template>
<script>
import HttpService from "../../Servicios/HttpService";
export default {
  name: "CambiarPassword",

  data: () => ({
    mostrarMensaje: false,
    mensaje: {
      texto: "",
      color: "",
    },
    formValido: false,
    mostrarPasswordActual: false,
    mostrarNueva: false,
    mostrarRepetida: false,
    passwordActual: "",
    nuevaPassword: "",
    repitePassword: "",
    rules: {
      required: (value) => !!value || "Debes colocar este dato.",
      min: (v) => v.length >= 8 || "Mínimo 8 caracteres",
    },
  }),

  methods: {
    async cambiar() {
      let passwordActualCoincide = await this.verificarPasswordActual();
      console.log(passwordActualCoincide);
      if (!passwordActualCoincide) {
        this.mostrarMensaje = true;
        this.mensaje = {
          texto: "La contraseña actual ingresada es incorrecta",
          color: "error",
        };
        return;
      }

      if (this.nuevaPassword !== this.repitePassword) {
        this.mostrarMensaje = true;
        this.mensaje = {
          texto: "La contraseña repetida debe coincidir con la nueva",
          color: "error",
        };
        return;
      }

      let payload = {
        metodo: "cambiar_pass",
        idUsuario: localStorage.getItem("idUsuario"),
        password: this.repitePassword,
      };

      HttpService.registrar(payload, "usuarios.php").then((resultado) => {
        if (resultado) {
          this.mostrarMensaje = true;
          this.mensaje = {
            texto:
              "La contraseña se ha actualizado. Debes iniciar sesión de nuevo",
            color: "success",
          };
          localStorage.removeItem("logeado");
          window.location.reload();
        }
      });
    },

    async verificarPasswordActual() {
      let payload = {
        metodo: "verifica_pass",
        password: this.passwordActual,
        id: localStorage.getItem("idUsuario"),
      };
      let respuesta = await HttpService.obtenerConDatos(
        payload,
        "usuarios.php"
      );
      return respuesta;
    },
  },
};
</script>