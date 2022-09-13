<template>
    <div>
        <h1>Editar usuario</h1>
        <form-usuario @registrado="onRegistrado" :usuario="usuario"></form-usuario>
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
    </div>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import FormUsuario from './FormUsuario.vue'

export default {

    name: "EditarUsuario",
    components: { FormUsuario },

    data:()=>({
        cargando:false,
        usuario: {},
        mostrarMensaje: false, 
        mensaje: {
            texto: "",
            color: ""
        },
    }),

    mounted(){
        this.cargando = true
        const payload = {
            metodo: "un_usuario",
            id: this.$route.params.id
        }

        HttpService.obtenerConDatos(payload, "usuarios.php")
        .then(resultado => {
            this.usuario = resultado
            this.cargando = false
        })
    },

    methods: {
        onRegistrado(usuario){
            this.cargando = true
            this.usuario = usuario
            //console.log(this.usuario)
            let payload = {
                metodo: "put",
                usuario: this.usuario
            }
            HttpService.registrar(payload, "usuarios.php")
            .then(resultado => {
                if(resultado){
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Información de usuario actualizada"
                    this.mensaje.color = "success"
                    
                    setTimeout(()=>
                        this.$router.push({
                            name: "Usuarios"
                        }),
                        1000
                    )
                    this.cargando = false
                }
            })
        }
    }
}
</script>
