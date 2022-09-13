<template>
    <div>
        <v-progress-linear
        :active="cargando"
        :indeterminate="cargando"
        absolute
        top
        color="deep-purple accent-4"
        ></v-progress-linear>
        <h1>Membresías</h1>
        <v-data-table
        :headers="encabezadoTabla"
        :items="membresias"
        sort-by="nombre"
        class="elevation-1"
        :footer-props="{itemsPerPageText: 'Por página'}"
        >
        <template v-slot:[`item.opciones`]="{ item }">
            <v-icon
                color="blue"
                class="mr-2"
                @click="editar(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                color="red"
                @click="eliminar(item)"
            >
                mdi-delete
            </v-icon>
            </template>
        </v-data-table>
        <v-snackbar
        v-model="mostrarMensaje"
        :timeout="3000"
        :color="mensaje.color"
        top
        >
            {{ mensaje.texto }}
        </v-snackbar>
        <v-btn 
        fab dark x-large elevation="8" 
        color="primary" 
        fixed right bottom
        @click="mostrarDialogo = true">
            <v-icon dark>add</v-icon>
        </v-btn>

        <v-dialog
        v-model="mostrarDialogo"
        persistent
        max-width="600px"
        >
            <form-membresia @cerrado="onCerrado" @registrar="onRegistrar" :membresia="membresia" :titulo="titulo"/>
        </v-dialog>
        <v-dialog v-model="mostrarDialogoEliminar" max-width="500px">
            <dialogo-eliminar :nombre="itemSeleccionado" @cancelar="cerrarDialogoEliminar" @eliminar="confirmarEliminar"/>
        </v-dialog>
    </div>
</template>
<script>
import FormMembresia from './FormMembresia.vue'
import DialogoEliminar from '../Dialogos/DialogoEliminar.vue'
import HttpService from '../../Servicios/HttpService'

export default ({
    name: "Membresias",
    components: { FormMembresia, DialogoEliminar },

    data: () => ({
        cargando: false,
        mostrarDialogo: false,
        mostrarDialogoEliminar: false,
        membresia: {
            nombre: "",
            duracion: "",
            precio: ""
        },
        membresias: [],
        mensaje: {
            texto: "",
            color: ""
        },
        mostrarMensaje: false,
        encabezadoTabla: [
            {text: "Membresía", sortable: true, value: "nombre"},
            {text: "Duración", value: "duracion"},
            {text: "Precio", value: "precio"},
            {text: 'Opciones', value: 'opciones', sortable: false },
            ],
        titulo: "Agregar",
        itemSeleccionado: ""
    }),

    mounted(){
        this.obtenerMembresias()
    },

    methods:{
        editar(membresia){
            this.titulo = "Editar"
            this.membresia = membresia
            this.mostrarDialogo = true
        },

        eliminar(membresia){
            this.itemSeleccionado = membresia.nombre
            this.membresia = membresia
            this.mostrarDialogoEliminar = true
        },

        cerrarDialogoEliminar(){
            this.mostrarDialogoEliminar = false
            this.membresia = {
                nombre: "",
                duracion: "",
                precio: ""
            }
        },

        confirmarEliminar(){
            this.cargando = true
            let payload = {
                metodo: "delete",
                id: this.membresia.id
            }
            HttpService.eliminar("membresias.php", payload)
            .then(eliminado => {
                if(eliminado){
                    this.mostrarDialogoEliminar = false
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Membresía eliminada"
                    this.mensaje.color = "success"
                    this.cargando = false
                    this.obtenerMembresias()
                }
            })
        },

        onCerrado(valor) {
            this.membresia = {
                nombre: "",
                duracion: "",
                precio: ""
            }
            this.mostrarDialogo = valor
            this.titulo = "Agregar"
        },
        
        onRegistrar(membresia) {
            let metodo = (this.titulo === "Agregar") ? "post" : "put"
            this.membresia = membresia
            this.cargando = true
            let payload = {
                metodo: metodo,
                membresia: this.membresia
            }
            HttpService.registrar(payload, "membresias.php")
            .then(respuesta => {
                if (respuesta){
                    this.membresia = {
                        nombre: "",
                        duracion: "",
                        precio: ""
                    }
                    this.mostrarDialogo = false
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Membresía registrada"
                    this.mensaje.color = "success"
                    this.cargando = false
                    this.obtenerMembresias()
                    this.titulo = "Agregar"
                }

            })
            
        },

        obtenerMembresias(){
            this.cargando = true
            let payload = {metodo: "get"}
            HttpService.obtenerConDatos(payload, "membresias.php")
            .then(respuesta => {
                this.membresias = respuesta
                this.cargando = false
            })
        }
    }

})
</script>
