<template>
    <div>
        <h1>Pagos <span class="indigo--text display-1"> ${{ totalPagos }}</span></h1>
        <periodo-busqueda @buscar="onBuscar"/>
        
        <cartas-totales v-if="pagos.length > 0" :totales="totalesMembresias" :titulo="'Pagos realizados por membresía'" :icono="'mdi-currency-usd'" :color="'deep-orange darken-1'" />
        
        <v-card class="mt-3">
            <v-card-title>
                Pagos realizados: 
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
            :items="pagos"
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
        <cartas-totales v-if="pagos.length > 0" class="mt-3" :totales="totalesUsuarios" :titulo="'Pagos realizados por usuario'" :icono="'mdi-account-cash'" :color="'green darken-3'" />

        <cartas-totales-miembros v-if="pagos.length > 0" class="mt-3" :totales="totalesMiembros" :titulo="'Pagos realizados por miembros'"  />

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
import Utiles from '../../Servicios/Utiles'
import PeriodoBusqueda from '../Dialogos/PeriodoBusqueda.vue'
import CartasTotales from '../Dialogos/CartasTotales.vue'
import CartasTotalesMiembros from '../Dialogos/CartasTotalesMiembros.vue'

export default {
    name: "Pagos",
    components: { PeriodoBusqueda, CartasTotales, CartasTotalesMiembros },
    data:()=>({
        busqueda: "",
        cargando: false,
        pagos: [],
        encabezadoTabla: [
            {text: "Imagen", sortable: true, value: "imagen"},
            {text: "Miembro", sortable: true, value: "nombre"},
            {text: "Matrícula", sortable: true, value: "matricula"},
            {text: "Fecha", sortable: true, value: "fecha"},
            {text: "Monto pagado", sortable: true, value: "monto"},
            {text: "Membresía pagada", sortable: true, value: "membresia"},
            {text: "Cobró", sortable: true, value: "usuario"},
            ],
        filtros: {
            fechaInicio: null,
            fechaFin: null
        },
        totalPagos: 0,
        totalesMembresias: [],
        totalesUsuarios: [],
        totalesMiembros: []
    }),

    mounted(){
        this.obtenerPagos()
    },

    methods: {
        onBuscar(fechas){
            console.log(fechas)
            this.filtros = {
                fechaInicio: fechas[0],
                fechaFin: fechas[1]
            }

            this.obtenerPagos()
        },

        urlImagen(imagen){
            return Utiles.generarURL(imagen)
        },

        obtenerPagos(){
            this.cargando = true
            let payload = { 
                metodo: 'obtener',
                filtros: this.filtros
            }
            HttpService.obtenerConDatos(payload, 'pagos.php')
            .then(respuesta => {
                this.pagos = respuesta.pagos
                this.totalPagos = respuesta.totalPagos
                this.totalesMembresias = respuesta.totalesMembresias
                this.totalesUsuarios = respuesta.totalesUsuario
                this.totalesMiembros = respuesta.totalesMiembros
                console.log(respuesta)
                this.cargando = false
            })

        }
    }
}
</script>