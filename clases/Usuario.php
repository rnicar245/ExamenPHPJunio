<?php
    require_once('DBAbstractModel.php');

    class Usuario extends DBAbstractModel{
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

        public function set($id = "") {
            $this->query = "INSERT INTO usuario (nombre, email, usuario, password, estado) VALUES (:nombre, :email, :usuario, :password, :estado)";
            $this->parametros['nombre']= $nombre;
            $this->parametros['email']= $email;
            $this->parametros['usuario']= $usuario;
            $this->parametros['password']= $password;
            $this->parametros['estado']= $estado;
            $this->get_results_from_query();
            //$this->execute_single_query();
            $this->mensaje = "<span style=\"color:green\">Plan mejorado con éxito.</span>";
            
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
        public function get($usuario=""){
            if($usuario!=""){
                $this->query = "SELECT usuario FROM usuario WHERE usuario=:usuario";
                $this->parametros["usuario"] = $usuario;
                $this->get_results_from_query();
            }
            if(count($this->rows) >= 1){
                foreach($this->rows[0] as $propiedad=>$valor){
                    $this->propiedad = $valor;
                }$this->mensaje="<span style=\"color:green\">Usuario encontrado</span>";
                return false;
            }else{
                $this->mensaje="<span style=\"color:red\">Usuario no encontrado</span>";
            }
            return true;
        }

        public function getUsuarioCorrecto($usuario="", $password =""){
            if($usuario!=""){
                $this->query = "SELECT usuario FROM usuarios WHERE usuario=:usuario AND passwd=:password";
                $this->parametros["usuario"] = $usuario;
                $this->parametros["password"] = $password;
                $this->get_results_from_query();
            }
            if(count($this->rows) == 1){
                foreach($this->rows[0] as $propiedad=>$valor){
                    $this->propiedad = $valor;
                }$this->mensaje="<span style=\"color:green\">Logeado correctamente</span>";
                return true;
            }else{
                $this->mensaje="<span style=\"color:red\">Las credenciales son incorrectas.</span>";
            }
            return false;
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