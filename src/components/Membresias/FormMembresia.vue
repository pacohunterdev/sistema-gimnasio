<template>
  <v-card>
    <v-card-title>
      <span class="text-h5">{{ titulo }} membresía</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form" v-model="formValido" lazy-validation>
          <v-row>
            <v-col cols="12">
              <v-text-field
                label="Nombre de la membresía"
                :rules="reglas"
                required
                hide-details="auto"
                v-model="membresia.nombre"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                label="Días de duración"
                :rules="reglas"
                type="number"
                required
                hide-details="auto"
                v-model="membresia.duracion"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                label="Precio"
                :rules="reglas"
                type="number"
                required
                hide-details="auto"
                v-model="membresia.precio"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" text @click="cerrarDialogo"> Cerrar </v-btn>
      <v-btn
        color="blue darken-1"
        text
        :disabled="!formValido"
        @click="registrar"
      >
        Registrar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  name: "FormMembresia",
  props: ["membresia", "titulo"],

  data: () => ({
    
    formValido: false,
    reglas: [(value) => !!value || "Debes colocar este campo."],
  }),

  mounted(){
    this.formValido = false
  },
  methods: {
    cerrarDialogo() {
      this.$emit("cerrado", false);
    },

    registrar() {
      this.$emit("registrar", this.membresia);
    },
  },
};
</script>