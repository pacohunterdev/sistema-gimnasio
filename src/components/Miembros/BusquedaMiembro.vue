<template>
  <div>
    <v-card>
      <v-card-text>
        <v-autocomplete
          :items="miembros"
          :filter="customFilter"
          color="indigo"
          :loading="cargando"
          @input.native="buscarMiembros"
          item-text="nombre"
          item-value="id"
          clearable
          label="Escribe el nombre o la matrícula del miembro"
          return-object
          v-model="miembroSeleccionado"
        >
          <template v-slot:no-data>
            <div class="px-4">
              No se encontraron resultados con {{ busqueda }}
            </div>
          </template>
          <template v-slot:item="data">
            <template v-if="typeof data.item !== 'object'">
              <v-list-item-content v-text="data.item"></v-list-item-content>
            </template>
            <template v-else>
              <v-list-item-avatar>
                <img :src="urlImagen(data.item.imagen)" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title
                  v-html="data.item.nombre"
                ></v-list-item-title>
                <v-list-item-subtitle
                  v-html="data.item.matricula"
                ></v-list-item-subtitle>
              </v-list-item-content>
            </template>
          </template>
        </v-autocomplete>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import HttpService from "../../Servicios/HttpService";
import Utiles from "../../Servicios/Utiles";

export default {
  name: "BusquedaMiembro",

  data: () => ({
    cargando: false, 
    busqueda: "",
    miembros: [],
    miembroSeleccionado: {},
  }),

  methods: {
    urlImagen(imagen) {
      return Utiles.generarURL(imagen);
    },

    buscarMiembros(value) {
      if (value.data) {
        this.busqueda += value.data;
        let payload = {
          metodo: "obtener_nombre_matricula",
          busqueda: this.busqueda,
        };
        this.cargando = true
        HttpService.obtenerConDatos(payload, "miembros.php").then(
          (resultados) => {
            this.miembros = resultados;
            this.cargando = false
          }
        );
      }
      this.busqueda = "";
    },

    customFilter(item, queryText, itemText) {
      const textOne = item.nombre.toLowerCase();
      const textTwo = item.matricula.toString();
      const searchText = queryText.toLowerCase();

      return (
        textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1
      );
    },
  },

  watch: {
    miembroSeleccionado(miembro){
        this.$emit("seleccionado", miembro)
    }
  }
};
</script>