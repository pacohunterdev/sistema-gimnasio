const RUTA_IMAGEN = "http://localhost/sistema-gimnasio/api/"
const DIAS = ["Domingo" ,"Lunes" ,"Martes" ,"Miércoles" ,"Jueves" ,"Viernes" ,"Sábado"]
const MESES = ["Enero" ,"Febrero" ,"Marzo" ,"Abril" ,"Mayo" ,"Junio" ,"Julio" ,"Agosto" ,"Septiembre" ,"Octubre" ,"Noviembre" ,"Diciembre"]

const Utiles = {
    generarURL(imagen) {
        return RUTA_IMAGEN + imagen
    },

    obtenerClaves(array){
        let claves = array.map(elemento => {
            return elemento[Object.keys(elemento)[0]]
        })
        return claves
    },

    obtenerValores(array){
        let valores = array.map(elemento => {
            return elemento.numeroVisitas
        })
        return valores
    },
    
    obtenerValoresPagos(array){
        let valores = array.map(elemento => {
            return parseInt(elemento.total)
        })
        return valores
    },

    cambiarDiaSemana(array){
        for(let i = 0; i < array.length; i++){
            let pos = array[i].numeroDia 
            array[i].dia = DIAS[pos-1]
            
        }
        return array
    },

    cambiarNumeroANombreMes(arreglo){
		for(let i = 0; i < arreglo.length; i++){
			let pos = arreglo[i].mes 
			arreglo[i].mes = MESES[pos-1]
			
		}
		return arreglo
	},
}

export default Utiles