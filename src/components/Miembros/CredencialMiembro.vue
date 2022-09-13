<template>
    <div id="credencial">
        <div class="credencial">
            <div class="nav">
                <div>
                    <img width="50" :src="urlImagen(logoGimnasio)" alt="">
                </div>
                <div>
                    <b> {{ nombreGimnasio }}</b>
                    <p style="font-size: 10px;">Tel: {{ telefonoGimnasio }}, {{ direccionGimnasio }} </p>
                </div>
            </div>
            <p class="nombre">{{ miembro.nombre }}</p>
            <p class="matricula">{{ miembro.matricula }}</p>
            <img class="imagen" :src="urlImagen(miembro.imagen)" alt="">
            <p>Teléfono: <b>{{ miembro.telefono }}</b></p>
            <p>Dirección: <b>{{ miembro.direccion }}</b></p>
            <p>Miembro desde: <b>{{ miembro.fechaRegistro }}</b></p>
        </div>
        <div class="credencial">
            <b>En caso de accidente, comunicarse con:</b>
            <p>Nombre: <b>{{ miembro.nombreContacto }}</b></p>
            <p>Teléfono: <b>{{ miembro.telefonoContacto }}</b></p>
            <p>Enfermedad(es): <b>{{ miembro.enfermedad }}</b></p>
            <p>Tiene seguro en: <b>{{ miembro.institucion }}</b></p>
            <br>
            <br>
            <br>
            <pre>Sistema para gimnasio por</pre>
            <img src="@/assets/logo.png" width="200" alt="LOGOOOO">
        </div>
    </div>
</template>
<script>
import Printd from "printd";
import Utiles from '../../Servicios/Utiles'
const d = new Printd();
export default {
    name: "CredencialMiembro",
    props: ["matricula", "miembro"],

    data:()=>({
        nombreGimnasio: "",
        logoGimnasio: "",
        telefonoGimnasio: "",
        direccionGimnasio: "",
        cssText: `
         .nav{
            margin-top: 10px;
            columns: 10px 2;
            column-gap: 0;

        }
        .credencial {
            padding: 10px;
            width: 300px;
            height: 400px;
            border: 1px  solid black;
            text-align: center;
            float: left;
            background-image: linear-gradient(50deg, #c5d0fc 23.53%, #cbe0f2 23.53%, #cbe0f2 50%, #c5d0fc 50%, #c5d0fc 73.53%, #cbe0f2 73.53%, #cbe0f2 100%);
            background-size: 300px 400px;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        .credencial > p{
            margin: 0 !important;
            padding: 0 !important;
        }

        .nombre{ 
            font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
            font-size: 32px;
            letter-spacing: -3.6px;
            word-spacing: -1.4px;
            color: #0824A1;
            font-weight: 700;
            text-decoration: none;
            font-style: normal;
            font-variant: normal;
            text-transform: none;

        }
        
        .matricula{
            font-family: "Courier New", Courier, monospace;
            font-size: 26px;
            letter-spacing: -1.2px;
            word-spacing: -5px;
            color: #7C6D7D;
            font-weight: 700;
            text-decoration: none;
            font-style: normal;
            font-variant: small-caps;
            text-transform: none;
            
        }

        .imagen{
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }
        `
    }),

    beforeMount(){
        this.nombreGimnasio = localStorage.getItem("nombreGimnasio")
        this.logoGimnasio = localStorage.getItem("logoGimnasio")
        this.telefonoGimnasio = localStorage.getItem("telefonoGimnasio")
        this.direccionGimnasio = localStorage.getItem("direccionGimnasio")
    },

    mounted(){


        this.d = new Printd()
        const { contentWindow } = this.d.getIFrame();
        contentWindow.addEventListener("beforeprint", () =>
            console.log("before print event!")
        );
        contentWindow.addEventListener("afterprint", () =>
        console.log("after print event!")
        );



        this.imprimir()
    },

    methods: {
        urlImagen(imagen) {
            return Utiles.generarURL(imagen);
        },

        imprimir() {
            let zona = document.getElementById("credencial");
            setTimeout(() => this.d.print(zona, [this.cssText]), 10);
            this.$emit("impreso", false);
        },
    }
}
</script>
<style>
    .nav{
        margin-top: 10px;
        columns: 10px 2;
        column-gap: 0;

    }
    .credencial {
        padding: 10px;
        width: 300px;
        height: 400px;
        border: 1px  solid black;
        text-align: center;
        float: left;
        background-image: linear-gradient(50deg, #c5d0fc 23.53%, #cbe0f2 23.53%, #cbe0f2 50%, #c5d0fc 50%, #c5d0fc 73.53%, #cbe0f2 73.53%, #cbe0f2 100%);
        background-size: 300px 400px;
    }
    .credencial > p{
         margin: 0 !important;
        padding: 0 !important;
    }

    .nombre{ 
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        font-size: 32px;
        letter-spacing: -3.6px;
        word-spacing: -1.4px;
        color: #0824A1;
        font-weight: 700;
        text-decoration: none;
        font-style: normal;
        font-variant: normal;
        text-transform: none;

    }
    
    .matricula{
        font-family: "Courier New", Courier, monospace;
        font-size: 26px;
        letter-spacing: -1.2px;
        word-spacing: -5px;
        color: #7C6D7D;
        font-weight: 700;
        text-decoration: none;
        font-style: normal;
        font-variant: small-caps;
        text-transform: none;
        
    }

    .imagen{
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto;
        display: block;
    }
</style>