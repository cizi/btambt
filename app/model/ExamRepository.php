<?php

namespace App\Model;

use App\Model\Entity\ExamEntity;

class ExamRepository extends BaseRepository {

	/**
	 * @param ExamEntity $examEntity
	 * @return \Dibi\Result|int
	 */
	public function save(ExamEntity $examEntity) {
        if ($examEntity->getID() == null) {
			$query = ["insert into appdata_pes_zkousky", $examEntity->extract()];
		} else {
			$query = ["update appdata_pes_zkousky set ", $examEntity->extract(), "where ID = %i", $examEntity->getID()];
		} 
		return $this->connection->query($query);
	}

	/**
	 * @param int $Pid
	 * @return \Dibi\Result|int
	 */
	public function deleteByPid($Pid) {
		$query = ["delete from appdata_pes_zkousky where pID = %i", $Pid];
		return $this->connection->query($query);
    }
    
    /**
	 * @param int $Pid
	 * @return \Dibi\Result|int
	 */
	public function deleteByPidAndZid($Pid, $Zid) {
		$query = ["delete from appdata_pes_zkousky where pID = %i and zID = %i", $Pid, $Zid];
		return $this->connection->query($query);
	}

	/**
	 * @param int $Pid
	 * @return ExamEntity[]
	 */
	public function findByPid($Pid) {
        $exams = [];
		$query = ["select * from appdata_pes_zkousky where pID = %i", $Pid];
        $result = $this->connection->query($query)->fetchAll();
        foreach ($result as $exam) {
            $examEntity = new ExamEntity();
            $examEntity->hydrate($exam->toArray());
            $exams[] = $examEntity;
        }
        
		return $exams;
    }
    
    /**
	 * @param int $Pid
	 * @return int[]
	 */
	public function findByPidToSelect($Pid) {
        $exams = [];
		$query = ["select * from appdata_pes_zkousky where pID = %i", $Pid];
        $result = $this->connection->query($query)->fetchAll();
        foreach ($result as $exam) {
            $examEntity = new ExamEntity();
            $examEntity->hydrate($exam->toArray());
            $exams[] = $examEntity->getZID();
        }
        
		return $exams;
	}
}