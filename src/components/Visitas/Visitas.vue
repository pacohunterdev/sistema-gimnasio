<template>
    <div>
        <h1>Visitas <span class="indigo--text display-1"> {{ visitas.length }}</span></h1>
        <periodo-busqueda @buscar="onBuscar"/>
        <cartas-totales v-if="visitas.length > 0" :totales="totalesMembresias" :titulo="'Visitas registradas por membresía'" :icono="'mdi-wallet-membership'" :color="'deep-purple accent-4'" />

        <v-card class="mt-3">
            <v-card-title>
                Visitas registradas: 
                <b v-if="!filtros.fechaInicio"> hoy</b>
                <b v-if="filtros.fechaInicio"> {{ filtros.fechaInicio }} al {{ filtros.fechaFin}}</b>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="busqueda"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>

            <v-data-table
            :loading="cargando"
            :headers="encabezadoTabla"
            :items="visitas"
            :search="busqueda"
            sort-by="nombre"
            class="elevation-1"
            :footer-props="{itemsPerPageText: 'Por página'}"
            >
                <template v-slot:[`item.imagen`]="{ item }">
                    <v-avatar v-if="item.imagen">
                    <img
                        :src="urlImagen(item.imagen)"
                        alt="Foto"
                    >
                    </v-avatar>
                </template>

                <template slot="no-data">
                    <v-alert :value="true" color="error" icon="warning">
                        No se han encontrado datos :(
                    </v-alert>
                    </template>
            </v-data-table>
        </v-card>

        <cartas-totales v-if="visitas.length > 0" :totales="totalesUsuarios" :titulo="'Visitas registradas por usuarios'" :icono="'mdi-badge-account'" :color="'brown darken-4'" />
        <cartas-totales-miembros v-if="visitas.length > 0" class="mt-3" :totales="totalesMiembros" :titulo="'Miembros con ,mayores visitas'"  />

    </div>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import Utiles from '../../Servicios/Utiles'
import PeriodoBusqueda from  '../Dialogos/PeriodoBusqueda.vue'
import CartasTotales from  '../Dialogos/CartasTotales.vue'
import CartasTotalesMiembros from  '../Dialogos/CartasTotalesMiembros.vue'

export default {
    name: "Visitas",
    components: { PeriodoBusqueda, CartasTotales, CartasTotalesMiembros },

    data:()=>({
        busqueda: "",
        cargando: false,
        visitas: [],
        encabezadoTabla: [
            {text: "Imagen", sortable: true, value: "imagen"},
            {text: "Miembro", sortable: true, value: "nombre"},
            {text: "Matrícula", sortable: true, value: "matricula"},
            {text: "Fecha", sortable: true, value: "fecha"},
            {text: "Membresía ", sortable: true, value: "membresia"},
            {text: "Usuario", sortable: true, value: "usuario"},
            ],
        filtros: {
            fechaInicio: null,
            fechaFin: null
        },
        totalesMembresias: [],
        totalesUsuarios: [],
        totalesMiembros: []
    }),

    mounted(){
        this.obtenerVisitas()
    },

    methods: {
        onBuscar(fechas){
            console.log(fechas)
            this.filtros = {
                fechaInicio: fechas[0],
                fechaFin: fechas[1]
            }

            this.obtenerVisitas()
        },

        urlImagen(imagen){
            return Utiles.generarURL(imagen)
        },

        obtenerVisitas(){
            this.cargando = true
            let payload = { 
                metodo: 'obtener',
                filtros: this.filtros
            }
            HttpService.obtenerConDatos(payload, 'visitas.php')
            .then(respuesta => {
                this.visitas = respuesta.visitas
                this.totalesMembresias = respuesta.totalesMembresias
                this.totalesUsuarios = respuesta.totalesUsuarios
                this.totalesMiembros = respuesta.totalesMiembros
                this.cargando = false
            })
        }
    }
    
}
</script>