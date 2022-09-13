<template>
    <div>
        <h1>Usuarios</h1>
        <v-btn 
        fab dark x-large elevation="8" 
        color="primary" 
        fixed right bottom
        to="/nuevo-usuario">
        <v-icon dark>add</v-icon>
        </v-btn>

        <v-data-table
        :loading="cargando"
        :headers="encabezadoTabla"
        :items="usuarios"
        sort-by="nombre"
        class="elevation-1"
        :footer-props="{itemsPerPageText: 'Por página'}"
        >
        <template v-slot:[`item.opciones`]="{ item }">
            <v-icon
                color="blue"
                class="mr-2"
                @click="editar(item.id)"
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
        <v-dialog v-model="mostrarDialogoEliminar" max-width="500px">
            <dialogo-eliminar :nombre="itemSeleccionado" @cancelar="cerrarDialogoEliminar" @eliminar="confirmarEliminar"/>
        </v-dialog>
        <v-snackbar
        v-model="mostrarMensaje"
        :timeout="3000"
        :color="mensaje.color"
        top
        >
            {{ mensaje.texto }}
        </v-snackbar>
    </div>
</template>
<script>
import DialogoEliminar from '../Dialogos/DialogoEliminar.vue'
import HttpService from '../../Servicios/HttpService' 

export default ({
    name: "Usuarios",
    components: { DialogoEliminar },

    data: () => ({
        cargando: false,
        mostrarDialogoEliminar: false,
        usuario: {},
        usuarios: [],
        encabezadoTabla: [
            {text: "Usuario", sortable: true, value: "usuario"},
            {text: "Nombre", sortable: true, value: "nombre"},
            {text: "telefono", value: "telefono"},
            {text: 'Opciones', value: 'opciones', sortable: false },
            ],
        itemSeleccionado: "",
        mensaje: {
            texto: "",
            color: ""
        },
        mostrarMensaje: false
    }),

    mounted(){
        this.obtenerUsuarios()
    },

    methods:{
        editar(idUsuario){
            this.$router.push({
                name: "EditarUsuario",
                params: { id: idUsuario },
            })
        },

        eliminar(usuario){
            this.itemSeleccionado = usuario.nombre
            this.usuario = usuario
            this.mostrarDialogoEliminar = true
        },

        cerrarDialogoEliminar(){
            this.mostrarDialogoEliminar = false
            this.usuario = {}
        },

        confirmarEliminar(){
            this.cargando = true
            let payload = {
                metodo: "delete",
                id: this.usuario.id
            }
            HttpService.eliminar("usuarios.php", payload)
            .then(eliminado => {
                if(eliminado){
                    this.mostrarDialogoEliminar = false
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Usuario eliminado"
                    this.mensaje.color = "success"
                    this.cargando = false
                    this.obtenerUsuarios()
                }
            })
        },



        obtenerUsuarios(){
            this.cargando = true
            let payload = {metodo: "get"}
            HttpService.obtenerConDatos(payload, "usuarios.php")
            .then(respuesta => {
                this.usuarios = respuesta
                this.cargando = false
            })
        }
    }

    
})
</script>
