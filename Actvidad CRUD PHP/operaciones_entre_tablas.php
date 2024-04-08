<?php

include('conn.php');

if(isset($_GET['accion'])){

    $accion = $_GET['accion'];

    switch ($accion) {
            
            case 'agregar_dueno_al_auto':
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "SELECT * FROM autos WHERE id = $id";
                    $result = $db->query($sql);
                    if($result->num_rows > 0){
                        if(isset($_GET['dueno_id'])){
                            $dueno_id = $_GET['dueno_id'];
                            $sql = "SELECT * FROM duenos WHERE id = $dueno_id";
                            $result = $db->query($sql);
                            if($result->num_rows > 0){
                                $sql = "UPDATE autos SET dueno_id = ? WHERE id = ?";
                                $stmt = $db->prepare($sql);
                                $stmt->bind_param("ii", $dueno_id, $id);
                                if($stmt->execute()){
                                    $response["status"] = "OK";
                                    $response["mensaje"] = "Dueno puesto en el auto";
                                }
                                else{
                                    $response["status"] = "Error";
                                    $response["mensaje"] = "Error al poner dueno en el auto";
                                } 
                            }
                            else{
                                $response["status"] = "Error";
                                $response["mensaje"] = "id del dueno inexistente";
                            }
                        }
                        else{
                            $response["status"] = "Error";
                            $response["mensaje"] = "Falta id del dueno para poner en el auto";
                        }
                    }
                    else{
                        $response["status"] = "Error";
                        $response["mensaje"] = "id del auto inexistente";
                    }
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "Falta id del auto para poner dueno";
                }
                echo json_encode($response);
                break;

            case 'quitar_dueno_del_auto':
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "SELECT dueno_id FROM autos WHERE id = $id";
                    $result = $db->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $dueno_id = $row['dueno_id'];
                        if($dueno_id != null){
                            $sql = "UPDATE autos SET dueno_id = NULL WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("i", $id);
                            if($stmt->execute()){
                                $response["status"] = "OK";
                                $response["mensaje"] = "Dueno quitado del auto";
                            }
                            else{
                                $response["status"] = "Error";
                                $response["mensaje"] = "Error al quitar dueno del auto";
                            }
                        }
                        else{
                            $response["status"] = "Info";
                            $response["mensaje"] = "El auto no tiene dueno para quitar";
                        } 
                    }
                    else{
                        $response["status"] = "Error";
                        $response["mensaje"] = "id inexistente";
                    } 
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "Falta id del auto para quitar dueno";
                }
                echo json_encode($response);
                break;

            case 'obtener_autos_del_dueno':
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $sql = "SELECT * FROM autos WHERE dueno_id = $id";
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
                        $response["mensaje"] = "No hay autos registrados con ese dueno";
                    }
                }
                else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "Falta id del dueno";
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