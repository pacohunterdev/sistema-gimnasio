<template>
  <v-card >
    <v-sheet
      class="v-sheet--offset mx-auto"
      color="white"
      elevation="6"
      max-width="calc(100% - 32px)"
    >
      <v-sparkline
        :labels="etiquetas"
        :value="valores"
        :color="color"
        line-width="3"
        padding="16"
      ></v-sparkline>
    </v-sheet>

    <v-card-text class="pt-0">
      <div class="text-h6 font-weight-light mb-2">{{ titulo }}</div>
      <v-divider class="my-2"></v-divider>
      <span class="text-caption grey--text font-weight-light">{{
        subtitulo
      }}</span>
      <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th v-for="(etiqueta, index) in etiquetas" :key="index">{{ etiqueta }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td v-for="(valor, index) in valores" :key="index">{{ valor }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  name: "SparklineComponent",
  props: ["etiquetas", "valores", "color", "titulo", "subtitulo"],

};
</script>