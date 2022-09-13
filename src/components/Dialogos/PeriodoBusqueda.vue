<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    :return-value.sync="fechas"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="fechas"
        label="Selecciona un periodo"
        prepend-icon="mdi-calendar"
        v-bind="attrs"
        v-on="on"
        :first-day-of-week="1"
        locale="es-se"
      ></v-text-field>
    </template>
    <v-date-picker v-model="fechas" range no-title scrollable>
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="menu = false"> Cancelar </v-btn>
      <v-btn text color="primary" @click="buscar"> Buscar </v-btn>
    </v-date-picker>
  </v-menu>
</template>
<script>
export default {
  name: "PeriodoBusqueda",

  data:()=>({
    fechas: [],
    menu: false,
  }),

  methods: {
    buscar(){
        this.$emit("buscar", this.fechas)
        this.menu =  false
    }
  }
};
</script>