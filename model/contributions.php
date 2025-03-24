<?php

require_once('model.php');

class Contributions extends Model
{
    public static $_table = "contributions";

    private $_id;
	private $_id_finance;
	private $_id_user;

    public function __construct($id, $id_finance, $id_user)
    {
		$this->set_id($id);
		$this->set_id_finance($id_finance);
		$this->set_id_user($id_user);
	}

    public function id(){ return $this->_id; }
    public function id_finance(){ return $this->_id_finance; }
    public function id_user(){ return $this->_id_user; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_finance($id_finance){ $this->_id_finance = $id_finance; }
    public function set_id_user($id_user){ $this->_id_user = $id_user; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Sold($data['id'], $data['id_finance'], $data['id_user'], 0, 0);
		else
			return null;
	}

    public static function getAllByFinance($id_finance) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_finance = :id_finance");
		$s->bindValue(':id_finance', $id_finance, PDO::PARAM_INT);
		$s->execute();
        $res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new Sold($row['id'], $row['id_finance'], $row['id_user'], 0, 0));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

    public static function insertContribution($id_finance, $id_user)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_finance, id_user) VALUES (:id_finance, :id_user)");
		$s->bindValue(':id_finance', $id_finance, PDO::PARAM_INT);
		$s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$s->execute();
	}
}