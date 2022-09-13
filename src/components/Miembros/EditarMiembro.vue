<template>
    <div>
        <h1>Editar miembro</h1>
        <form-miembro :miembro="datosMiembro" @registrado="onEditar" />
        <v-snackbar
        v-model="mostrarMensaje"
        :timeout="3000"
        :color="mensaje.color"
        top
        >
            {{ mensaje.texto }}
        </v-snackbar>
        <v-overlay :value="cargando">
            <v-progress-circular size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import FormMiembro from './FormMiembro.vue'

export default {
    name: "EditarMiembro",
    components: { FormMiembro },

    data:()=>({
        cargando: false,
        datosMiembro: {
            datosPersonales: null,
            datosContacto: null
        },
        mostrarMensaje: false,
        mensaje: {
            texto: "",
            color: ""
        }
    }),

    mounted(){
        this.cargando = true
        const payload = {
            metodo: "obtener_id",
            id: this.$route.params.id
        }

        HttpService.obtenerConDatos(payload, "miembros.php")
        .then(resultado => {
            this.datosMiembro.datosPersonales = {
                nombre: resultado.nombre,
                telefono: resultado.telefono,
                direccion: resultado.direccion,
                edad: resultado.edad,
            }
            this.datosMiembro.datosContacto = {
                sufreEnfermedad: resultado.sufreEnfermedad,
                tieneSeguro: resultado.tieneSeguro,
                enfermedad: resultado.enfermedad,
                institucion: resultado.institucion,
                nombreContacto: resultado.nombreContacto,
                telefonoContacto: resultado.telefonoContacto,
            }
            this.datosMiembro.imagen = resultado.imagen
            
            
            this.cargando = false
        })
    },

    methods: {
        onEditar(miembro){
            const imagenCambia = miembro.imagen.includes('data:image')
            if(!imagenCambia){
                miembro.imagen = miembro.imagen.split('api/')[1]
            }
            miembro.imagenCambia =  imagenCambia
            miembro.id = this.$route.params.id
             
            this.cargando = true
            let payload = {
                metodo: "editar",
                miembro: miembro
            }
            HttpService.registrar(payload, "miembros.php")
            .then(resultado => {
                if(resultado){
                    this.mostrarMensaje = true
                    this.mensaje.texto = "Información de miembro actualizada"
                    this.mensaje.color = "success"
                    
                    setTimeout(()=>
                        this.$router.push({
                            name: "Miembros"
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
