<?php


            //============asignando variables============
            $resultQuery['DNI'] = "44791512";
            $resultQuery["Nombre"] = "JOSE LUIS";
            $resultQuery["Paterno"] = "ZAMBRANO IBAÑEZ";
            $resultQuery["Materno"] = "YACHA";
            $resultQuery["FechaNac"] = '';
            $resultQuery["Sexo"] = '';
            $resultQuery["distrito"] = '';
            $resultQuery["provincia"] = '';
            $resultQuery["departamento"] = '';
            //resultado final
            $RtaBusqueda['success'] = "True"; //
            $RtaBusqueda['statusMessage'] = "REGISTRO ENCONTRADO";
            $RtaBusqueda['result'] = $resultQuery;
            
            //echo json_encode($RtaBusqueda, JSON_PRETTY_PRINT);
            
           print_json($RtaBusqueda);


function print_json($data) {
    header("HTTP/1.1");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data, JSON_PRETTY_PRINT);
}
 
