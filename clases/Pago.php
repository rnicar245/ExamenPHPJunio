<?php
    require_once('DBAbstractModel.php');

    class Pago extends DBAbstractModel{
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

        public function set($idUser = "", $mes = "", $anyo = "", $importe = "") {
            $this->query = "INSERT INTO pagos_user (idUser, mes, anyo, importe, pagado) VALUES (:idUser, :mes, :anyo, :importe, \"1\")";
            $this->parametros['idUser']= $idUser;
            $this->parametros['mes']= $mes;
            $this->parametros['anyo']= $anyo;
            $this->parametros['importe']= $importe;
            $this->get_results_from_query();
            $this->mensaje = "<span style=\"color:green\">Pago realizado con éxito</span>";
            
        }
        public function guardarenDB() {
            $this->query = "INSERT INTO libro (id, titulo, autor) VALUES (:id, :titulo, :autor)";
            $this->parametros['id']= $this->id;
            $this->parametros['titulo']= $this->titulo;
            $this->parametros['autor']= $this->autor;
            $this->get_results_from_query();
            $this->mensaje = 'Usuario agregado exitosamente';
        }
        // public function getLibros($datos){
        //     $this->query = "SELECT id, titulo, autor FROM libro WHERE titulo like :filtro OR autor like : filtro";
        //     $this->parametros["id"] = $id;
        //     $this->get_results_from_query();
        // }  
        public function get($id=""){
            $this->query = "SELECT * FROM pagos_user WHERE idUser=:id";
            $this->parametros["id"] = $id;
            $this->get_results_from_query();
            return $this->rows;
        }

        public function getPago($id = ""){
            $mes = date("n");
            $anyo = date("Y");
            $this->query = "SELECT * FROM pagos_user WHERE idUser=:id AND mes<=:mes AND anyo=:anyo";
            $this->parametros["id"] = $id;
            $this->parametros["mes"] = $mes;
            $this->parametros["anyo"] = $anyo;
            $this->get_results_from_query();
            return $mes-count($this->rows);
        }

        public function getPerfil($id=""){
            $this->query = "SELECT perfil FROM usuarios WHERE id=:id";
            $this->parametros["id"] = $id;
            $this->get_results_from_query();
            
            return $this->rows[0]['perfil'];
        }

        public function getId($usuario=""){
            if($usuario!=""){
                $this->query = "SELECT id FROM usuarios WHERE usuario=:usuario";
                $this->parametros["usuario"] = $usuario;
                $this->get_results_from_query();
            }
            return $this->rows[0]['id'];
        }

        public function getUsuarios($busqueda = "%%"){
            if($busqueda != "%%"){
                $this->query = "SELECT * FROM usuario WHERE usuario LIKE :busqueda";
            }else{
                $this->query = "SELECT * FROM usuario";
            }
            $this->parametros['busqueda']=$busqueda;
            $this->get_results_from_query();
            return $this->rows;
        }

        public function editEstado($usuario="") {
            $nuevoEstado = $this->getEstado($usuario);
            if($nuevoEstado != false){
                if($nuevoEstado == "bloqueado"){
                    $nuevoEstado = "activo";
                }else{
                    $nuevoEstado = "bloqueado";
                }     
                $this->query = "UPDATE usuario SET estado=:nuevoEstado WHERE usuario = :usuario ";
                $this->parametros['usuario']=$usuario;
                $this->parametros['nuevoEstado']=$nuevoEstado;
                
                $this->get_results_from_query();
                $this->mensaje = "<span style=\"color:green\">Estado modificado con éxito</span>";
            }
        }

        public function edit($id = "") {
            $this->query = "UPDATE usuarios SET perfil=\"premium\" WHERE id = :id";
            $this->parametros['id']=$id;
            
            $this->get_results_from_query();
            $this->mensaje = "<span style=\"color:green\">Plan mejorado con éxito</span>";
        }
        
        public function delete($usuario="") {
            $this->query = "DELETE FROM usuario WHERE usuario = :usuario";
            $this->parametros['usuario']=$usuario;
            $this->get_results_from_query();
            $this->mensaje = "<span style=\"color:green\">Usuario eliminado con éxito</span>";
        }

        public function persist(){

        }
        public function getMensaje(){
            return $this->mensaje;
        }
    }
?>