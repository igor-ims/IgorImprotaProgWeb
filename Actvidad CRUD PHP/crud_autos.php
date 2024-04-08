<?php

include('conn.php');

if(isset($_GET['accion'])){

    $accion = $_GET['accion'];

    switch ($accion) {

        case 'obtener_todos_autos':
            $sql = "SELECT * FROM autos WHERE 1";
            $result = $db->query($sql);
            if($result->num_rows > 0){
                $autos = array();
                while($fila = $result->fetch_assoc()){
                    $item = array();
                    $item['id'] = $fila['id'];
                    $item['marca'] = $fila['marca'];
                    $item['modelo'] = $fila['modelo'];
                    $item['anno'] = $fila['anno'];
                    $item['num_serie'] = $fila['num_serie'];
                    $item['dueno_id'] = $fila['dueno_id'];
                    $autos[] = $item;
                }
                $response["status"] = "OK";                    
                $response["mensaje"] = $autos;
            }
            else{
                $response["status"] = "Info";
                $response["mensaje"] = "No hay autos registrados";
            }
            echo json_encode($response);
            break;

        case "insertar_auto":
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT * FROM autos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows == 0){
                    if(isset($_GET['marca']) && isset($_GET['modelo']) && isset($_GET['anno']) && isset($_GET['num_serie'])) {
                        $marca = $_GET['marca'];
                        $modelo = $_GET['modelo'];
                        $anno = $_GET['anno'];
                        $num_serie = $_GET['num_serie'];

                        $sql = "INSERT INTO autos (id, marca, modelo, anno, num_serie) VALUES (?,?,?,?,?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("issis", $id, $marca, $modelo, $anno, $num_serie);
                        if($stmt->execute()){
                            $response["status"] = "OK";
                            $response["mensaje"] = "Auto insertado";
                        } 
                        else {
                            $response["status"] = "Error";
                            $response["mensaje"] = "Error al insertar auto";
                        }
                    }
                    else{
                        $response["status"] = "Error";
                        $response["mensaje"] = "Faltan los datos del auto";
                    }
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "id existente";
                }
            }
            else{
                $response["status"] = "Error";
                $response["mensaje"] = "id necesario para insercion";
            }
            echo json_encode($response);
            break;

        case 'actualizar_auto':
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM autos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc(); // Agarra los datos existentes del auto
                    // Verifica cuales datos el usuario ha insertado para hacer la actualizacion, sino mantiene los datos anteriores
                    $marca = isset($_GET['marca']) && !empty($_GET['marca'])  ? $_GET['marca'] : $row['marca'];
                    $modelo = isset($_GET['modelo']) && !empty($_GET['modelo']) ? $_GET['modelo'] : $row['modelo'];
                    $anno = isset($_GET['anno']) && !empty($_GET['anno'])  ? $_GET['anno'] : $row['anno'];
                    $num_serie = (isset($_GET['num_serie']) && !empty($_GET['num_serie'])) ? $_GET['num_serie'] : $row['num_serie'];

                    $sql = "UPDATE autos SET marca = ?, modelo = ?, anno = ?, num_serie = ? WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ssiii", $marca, $modelo, $anno, $num_serie, $id);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Auto actualizado";
                    }
                    else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "Error en la actualizacion";
                    } 
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "id inexistente para actualizar";
                } 
            }
            else{
                $response["status"] = "Error";
                $response["mensaje"] = "Falta id para actualizar";
            }
            echo json_encode($response);
            break;

        case 'borrar_auto':
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM autos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $sql = "DELETE FROM autos WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i",$id);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Auto borrado";
                    } 
                    else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "Error al borrar auto";
                    }
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "id inexistente";
                }
            }else{
                $response["status"] = "Error";
                $response["mensaje"] = "falta id para borrar";
            }    
            echo json_encode($response);
            break;

            default:
                $response["status"] = "Error SQL";
                $response["mensaje"] = "No existe esa operacion";
                echo json_encode($response);
                break;
    }
}
else{
    $response["status"] = "Error ACCION";
    $response["mensaje"] = "Falta escoger una accion";
    echo json_encode($response);
}