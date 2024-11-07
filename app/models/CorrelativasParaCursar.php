<?php
require_once('Db.php');

class CorrelativasParaCursar
{
    protected $table = 'correlativasparacursar';
	protected $conection;
	private $id;
	private $idMateria; 
	private $idMateriaRequerida;
	private $idCondicionMateriaRequerida;
    protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get cantidad  */
	public function getCantidad(){
		return $this->cantidad;
	}

    /* Get all*/
	public function getCorrelativasCursadoById($id){
		$this->getConection();
		$sql = "SELECT cpc.id, m.id as materia_id, m.nombre as materia, m.carrera as carrera,  
                       m1.id as materia_requerida_id, m1.nombre as materia_requerida, 
                       m1.carrera as carrera_requerida, cpc.idCondicionMateriaRequerida 
                FROM correlativasparacursar cpc, materia m, materia m1 
                WHERE cpc.idMateria = m.id and cpc.idMateriaRequerida = m1.id and cpc.id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

    /* Get all */
	public function getCorrelativasCursado(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table . " ORDER BY id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
        $res = $stmt->fetchAll();
        $this->cantidad = count($res);
		return $res;
	}

    
    /********************************************************************************************************/
    //Saca las correlativas que debe cumplir una materia. Las correlativas varian si es para Rendir o para Cursar
    // condicion: 'Rendir', 'Cursar'
    //********************************************************************************************************/
    public function getMateriasCorrelativasByIdMateria($materia_id)
    {
        $this->getConection();
        $arr_materias_requeridas_aprobadas = [];
        $arr_materias_requeridas_regulares = [];
        $arr_resultados = [];
        $sql = "SELECT idMateriaRequerida,idCondicionMateriaRequerida
                  FROM correlativasparacursar
                  WHERE idMateria = ?";

        $stmt = $this->conection->prepare($sql);
		$stmt->execute([$materia_id]);

		foreach ($stmt->fetchAll(PDO::FETCH_DEFAULT) as $fila) {
            if ($fila['idCondicionMateriaRequerida']==1) {
                $arr_materias_requeridas_regulares[] = $fila['idMateriaRequerida'];
            } elseif ($fila['idCondicionMateriaRequerida']==2) {
                $arr_materias_requeridas_aprobadas[] = $fila['idMateriaRequerida'];
            }
		}

        $arr_resultados['regulares'] = $arr_materias_requeridas_regulares;
        $arr_resultados['aprobadas'] = $arr_materias_requeridas_aprobadas;
        return $arr_resultados;
    }

/* Save Correlativas Cursado */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $idMateria = $idMateriaRequerida = $idCondicionMateriaRequerida = "";

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualObjeto = $this->getCorrelativasCursadoById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$idMateria = $actualObjeto["idMateria"];
				$idMateriaRequerida = $actualObjeto["idMateriaRequerida"];
				$idCondicionMateriaRequerida = $actualObjeto["idCondicionMateriaRequerida"];
				
			}
		}

		//* Received values 
		if(isset($param["materia_id"])) $idMateria = $param["materia_id"];
		if(isset($param["materia_requerida_id"])) $idMateriaRequerida = $param["materia_requerida_id"];
		if(isset($param["condicion_id"])) $idCondicionMateriaRequerida = $param["condicion_id"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idMateria=?, idMateriaRequerida=?, idCondicionMateriaRequerida=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$idMateria,$idMateriaRequerida,$idCondicionMateriaRequerida, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idMateria, idMateriaRequerida, idCondicionMateriaRequerida) values(?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$idMateria,$idMateriaRequerida,$idCondicionMateriaRequerida]);
			$id = $this->conection->lastInsertId();
		}
		return $id;	

	}

	/* Delete Alumno by id */
	public function deleteCorrelativasCursadoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}
    

}

//$cc = new CorrelativasParaCursar();
//var_dump($cc->getMateriasCorrelativasByIdMateria(54));