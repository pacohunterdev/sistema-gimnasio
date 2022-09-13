<template>
  <div>
    <v-form ref="form" v-model="formValido">
      <v-card elevation="5" class="mx-auto">
        <v-card-text>
          <v-text-field
            label="Nombre de usuario"
            v-model="usuario.usuario"
            :rules="reglas"
            required
            hide-details="auto"
          ></v-text-field>
          <v-text-field
            label="Nombre completo"
            v-model="usuario.nombre"
            :rules="reglas"
            required
            hide-details="auto"
          ></v-text-field>
          <v-text-field
            label="Teléfono"
            v-model="usuario.telefono"
            :rules="reglas"
            required
            hide-details="auto"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn text @click="$router.push({ name: 'Usuarios' })"> Cancelar </v-btn>
          <v-spacer></v-spacer>

          <v-btn
            color="primary"
            text
            @click="registrar"
            :disabled="!formValido"
          >
            Registrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </div>
</template>
<script>
export default {
  name: "FormUsuario",
  props: ["usuario"],
  data: () => ({
    formValido: false,
    formHasErrors: false,
    reglas: [(value) => !!value || "Debes colocar este campo."],
  }),

  mounted() {
    this.formValido = false;
  },

  methods: {
    registrar() {
      this.formHasErrors = false

        Object.keys(this.form).forEach(f => {
          if (!this.form[f]) this.formHasErrors = true

          //this.$refs[f].validate(true)
        })
      this.$emit("registrado", this.usuario);
      this.formValido = false
    },
  },

  computed: {
      form () {
        return {
          usuario: this.usuario.usuario,
          nombre: this.usuario.nombre,
          telefono: this.usuario.telefono
        }
      },
    },
};
</script>