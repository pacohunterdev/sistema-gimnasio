<template>
    <div>
        <v-progress-linear
        :active="cargando"
        :indeterminate="cargando"
        absolute
        top
        color="deep-purple accent-4"
        ></v-progress-linear>
        <v-snackbar
        v-model="mostrarMensaje"
        :timeout="3000"
        :color="mensaje.color"
        top
        >
            {{ mensaje.texto }}
        </v-snackbar>
        <h1>Registrar usuario</h1>
        <form-usuario :usuario="usuario" @registrado="onRegistrado"/>
    </div>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import FormUsuario from './FormUsuario.vue'

export default {
    name: "NuevoUsuario",
    components: { FormUsuario},

    data:() => ({
        cargando: false,
        usuario: {
            usuario: "",
            nombre: "",
            telefono: "",
        },
        mostrarMensaje: false, 
        mensaje: {
            texto: "",
            color: ""
        },
    }),

    methods: {
        onRegistrado(usuario){
            this.cargando = true
            this.usuario = usuario
            console.log(this.usuario)
            let payload = {
                metodo: "registrar",
                usuario: this.usuario
            }
            HttpService.registrar(payload, "usuarios.php")
            .then(resultado => {
                if(resultado){
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Usuario registrado"
                    this.mensaje.color = "success"
                    this.cargando = false
                    this.usuario = {
                        usuario: "",
                        nombre: "",
                        telefono: "",
                    }
                }
            })
        }
    }
}
</script>