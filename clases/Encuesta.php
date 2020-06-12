<?php
    require_once('DBAbstractModel.php');

    class Encuesta extends DBAbstractModel{
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

        public function set($pregunta = "", $respuesta = "") {
            $this->query = "INSERT INTO encuestas_respuestas (idEncuestaPregunta, Valor) VALUES (:pregunta, :respuesta)";
            $this->parametros['pregunta']= $pregunta;
            $this->parametros['respuesta']= $respuesta;
            $this->get_results_from_query();

            $this->mensaje = "<span style=\"color:green\">Respuestas almacenadas, ¡gracias por tu aportación!</span>";
        }

        public function setEncuesta($nombre = "") {
            $this->query = "INSERT INTO encuestas (Titulo) VALUES (:nombre)";
            $this->parametros['nombre']= $nombre;
            $this->get_results_from_query();

            $this->mensaje = "<span style=\"color:green\">Encuesta creada con éxito.</span>";
        }

        public function setPregunta($pregunta = "", $idEncuesta = "") {
            $this->query = "INSERT INTO encuestas_preguntas (idEncuesta, pregunta) VALUES (:idEncuesta, :pregunta)";
            $this->parametros['pregunta']= $pregunta;
            $this->parametros['idEncuesta']= $idEncuesta;
            $this->get_results_from_query();
        }

        public function get(){
            $this->query = "SELECT * FROM encuestas";
            $this->get_results_from_query();
            return $this->rows;
        }

        public function getEncuesta($nombre=""){
            $this->query = "SELECT id FROM encuestas WHERE Titulo=:nombre";
            $this->parametros['nombre']= $nombre;
            $this->get_results_from_query();

            return $this->rows[0];
        }

        public function getPreguntas($id=""){
            $this->query = "SELECT * FROM encuestas_preguntas WHERE idEncuesta=:id";
            $this->parametros["id"] = $id;
            $this->get_results_from_query();
            return $this->rows;
        }

        public function getPuntuacionMedia($idEncuestaPregunta){
            $this->query = "SELECT AVG(Valor) as media
            FROM encuestas_respuestas
            WHERE idEncuestaPregunta=:idEncuestaPregunta";

            $this->parametros["idEncuestaPregunta"] = $idEncuestaPregunta;
            $this->get_results_from_query();
            return $this->rows[0]['media'];
        }

        public function getRecomendadas(){
            $this->query = "
                SELECT idSerie, COUNT(*) as cuenta
                FROM series_user
                GROUP BY idSerie
                HAVING COUNT(*) > 2";
            $this->get_results_from_query();
            return $this->rows;
        }

        public function edit($estado = "", $id = "") {
        }
        
        public function delete($idSerie = "", $idUser = "") {
            $this->query = "DELETE FROM series_user WHERE idSerie = :idSerie AND idUser = :idUser";
            $this->parametros['idSerie']=$idSerie;
            $this->parametros['idUser']=$idUser;
            $this->get_results_from_query();
        }

        public function persist(){
        }
    }
?>