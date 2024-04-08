<?php

include('conn.php');

if(isset($_GET['accion'])){
    $accion = $_GET['accion']; 

    switch ($accion) {

        case 'obtener_todos_duenos':
            $sql = "SELECT * FROM duenos WHERE 1";
            $result = $db->query($sql);
            if($result->num_rows > 0){
                while($fila = $result->fetch_assoc()){
                    $item['id'] = $fila['id'];
                    $item['nombre'] = $fila['nombre'];
                    $item['email'] = $fila['email'];
                    $duenos[] = $item;
                }
                $response["status"] = "OK";                    
                $response["mensaje"] = $duenos;
            }
            else{
                $response["status"] = "Info";
                $response["mensaje"] = "No hay duenos registrados";
            }
            echo json_encode($response);
            break;

        case 'insertar_dueno':
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT * FROM duenos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows == 0){
                    if(isset($_GET['nombre']) && isset($_GET['email'])) {
                        $nombre = $_GET['nombre'];
                        $email = $_GET['email'];

                        $sql = "INSERT INTO duenos (id, nombre, email) VALUES (?,?,?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("iss", $id, $nombre, $email);  
                        if($stmt->execute()){
                            $response["status"] = "OK";
                            $response["mensaje"] = "dueno insertado";
                        } 
                        else {
                            $response["status"] = "Error";
                            $response["mensaje"] = "Error al insertar dueno";
                        }
                    }
                    else{
                        $response["status"] = "Error";
                        $response["mensaje"] = "Faltan los datos del dueno";
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

        case 'actualizar_dueno':
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM duenos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc(); // Agarra los datos existentes del dueno
                    // Verifica cuales datos el usuario ha insertado para hacer la actualizacion, sino mantiene los datos anteriores
                    $nombre = isset($_GET['nombre']) && !empty($_GET['nombre'])  ? $_GET['nombre'] : $row['nombre'];
                    $email = isset($_GET['email']) && !empty($_GET['email']) ? $_GET['email'] : $row['email'];

                    $sql = "UPDATE duenos SET nombre = ?, email = ? WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ssi", $nombre, $email, $id);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Dueno actualizado";
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
                $response["mensaje"] = "Falta id para actualizacion";
            }
            echo json_encode($response);
            break;

        case 'borrar_dueno':
            if(isset($_GET['id'])) {
                $id=$_GET['id'];
                $sql = "SELECT * FROM duenos WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $sql = "DELETE FROM duenos WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i",$id);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Dueno borrado";
                    } 
                    else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "Error al borrar dueno";
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
    $response["mensaje"] = "Falta id de la accion";
    echo json_encode($response);
}
