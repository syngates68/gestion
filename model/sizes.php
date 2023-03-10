<?php

require_once('model.php');

class Sizes extends Model
{
    public static $_table = "sizes";

    private $_id;
	private $_label;

    public function __construct($id, $label)
    {
		$this->set_id($id);
		$this->set_label($label);
	}

    public function id(){ return $this->_id; }
    public function label(){ return $this->_label; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_label($label){ $this->_label = $label; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Sizes($data['id'], $data['label']);
		else
			return null;
	}

    public static function getAll() 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table");
		$s->execute();
        $res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new Sizes($row['id'], $row['label']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}
}