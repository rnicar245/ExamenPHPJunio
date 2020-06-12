<?php
    require_once('DBAbstractModel.php');

    class Serie extends DBAbstractModel{
        private static $instancia;

        public static function getInstancia() {
            if (!isset(self::$instancia)) {
                $miclase = __CLASS__;
                self::$instancia = new $miclase;
            }
            return self::$instancia;
        }

        public function __clone() {
            trigger_error('La clonación no es permitida.', E_USER_ERROR);
        }

        public function set($user_data=array()) {
            if(array_key_exists('titulo', $user_data)) {
                if($this->get($user_data['titulo'])){
                    foreach ($user_data as $campo=>$valor) {
                        $$campo = $valor;
                    }
                    $this->query = "INSERT INTO series (titulo, img, estado) VALUES (:titulo, :img, :estado)";
                    $this->parametros['titulo']= $titulo;
                    $this->parametros['img']= $img;
                    $this->parametros['estado']= $estado;
                    $this->get_results_from_query();
                    $this->mensaje = "<span style=\"color:green\">Serie agregada exitosamente</span>";
                    return true;
                    
                }else{  
                    $this->mensaje = "<span style=\"color:red\">La serie ya existe</span>";
                    return false;
                }
            }else{
                $this->mensaje = "<span style=\"color:red\">No se ha agregado la serie</span>";
                return false;
            }
            
        }

        public function get($id=""){
            $this->query = "SELECT * FROM series WHERE id = :id";
            $this->parametros['id']=$id;
            $this->get_results_from_query();
            return $this->rows[0];
        }

        public function getAllSeries(){
            $this->query = "SELECT * FROM series";
            $this->get_results_from_query();
            return $this->rows;  
        }

        public function getTitulo($id = ""){
            $this->query = "SELECT titulo FROM series WHERE id = :id";
            $this->get_results_from_query();
            return $this->rows[0];  
        }

        public function edit($estado = "", $id = "") {
            if($estado == "habilitado"){
                $this->query = "UPDATE series SET estado=\"deshabilitado\" WHERE id = :id";
            }else{
                $this->query = "UPDATE series SET estado=\"habilitado\" WHERE id = :id";
            }
            $this->parametros['id']=$id;
            $this->parametros['estado']=$estado;

            $this->get_results_from_query();
            $this->mensaje = "<span style=\"color:green\">Estado modificado con éxito</span>";
        }
        
        public function delete($fichero="", $idUsuario="") {
            $this->query = "DELETE FROM documentos WHERE fichero = :fichero AND idUsuario = :idUsuario";
            $this->parametros['fichero']=$fichero;
            $this->parametros['idUsuario']=$idUsuario;
            $this->get_results_from_query();
            $this->mensaje = "<span style=\"color:green\">Fichero eliminado con éxito</span>";
        }

        public function persist(){

        }
        public function getMensaje(){
            return $this->mensaje;
        }
    }
?>