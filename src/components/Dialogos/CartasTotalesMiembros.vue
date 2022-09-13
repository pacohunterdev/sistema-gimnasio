<template>
    <section >
        <p class="display-1">{{ titulo }}</p>
        <div class="row">
        <div class="col-sm-6 col-lg-3 col-12" v-for="(item,index) in totales" :key="index">
            <div class="v-card--material pa-3 v-card--material-stats v-card v-sheet theme--light v-card--material--has-heading">
                <div class="d-flex grow flex-wrap">
                    <v-avatar
                    size="90"
                    >
                        <v-img :src="urlImagen(item.imagen)"></v-img>
                    </v-avatar>
                    <div class="ml-6">
                        <div class="ml-auto text-right">
                            <div class="body-3 grey--text font-weight-light">
                                {{ item.nombre }}
                                <br>
                                <small>{{ item.matricula }}</small>
                            </div>
                            <h3 class="display-1 font-weight-light text--primary">{{ item.total}}
                                <small></small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="px-0 col col-12">
                    <hr role="separator" aria-orientation="horizontal" class="v-divider theme--light">
                </div>
                <v-rating
                    color="warning"
                    :value="numeroEstrellas(item.total)"
                    half-increments
                    readonly 
                    ></v-rating>
            </div>
        </div>
        </div>
    </section>
</template>
<script>
import Utiles from '../../Servicios/Utiles'

export default {
    name: "CartasTotalesMiembros",
    props: ["totales", "titulo"],

    data:()=>({
        numeroMayor: 0
    }),

    mounted(){
        this.numeroMayor = this.totales[0].total
    },

    methods: {
        urlImagen(imagen) {
            return Utiles.generarURL(imagen);
        },

        numeroEstrellas(total){
            return  parseFloat(total*5/this.numeroMayor)
        }
    }
}
</script>