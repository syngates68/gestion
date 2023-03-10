<?php

require_once('model.php');

class Sold extends Model
{
    public static $_table = "sold";

    private $_id;
	private $_id_finance;
	private $_id_inventory;
	private $_number_sold;
	private $_is_garment;

    public function __construct($id, $id_finance, $id_inventory, $number_sold, $is_garment)
    {
		$this->set_id($id);
		$this->set_id_finance($id_finance);
		$this->set_id_inventory($id_inventory);
		$this->set_number_sold($number_sold);
		$this->set_is_garment($is_garment);
	}

    public function id(){ return $this->_id; }
    public function id_finance(){ return $this->_id_finance; }
    public function id_inventory(){ return $this->_id_inventory; }
	public function number_sold(){ return $this->_number_sold; }
	public function is_garment(){ return $this->_is_garment; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_finance($id_finance){ $this->_id_finance = $id_finance; }
    public function set_id_inventory($id_inventory){ $this->_id_inventory = $id_inventory; }
	public function set_number_sold($number_sold){ $this->_number_sold = $number_sold; }
	public function set_is_garment($is_garment){ $this->_is_garment = $is_garment; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Sold($data['id'], $data['id_finance'], $data['id_inventory'], $data['number_sold'], $data['is_garment']);
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
            array_push($res, new Sold($row['id'], $row['id_finance'], $row['id_inventory'], $row['number_sold'], $row['is_garment']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

    public static function insertSold($id_finance, $id_inventory, $number_sold, $is_garment)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_finance, id_inventory, number_sold, is_garment) VALUES (:id_finance, :id_inventory, :number_sold, :is_garment)");
		$s->bindValue(':id_finance', $id_finance, PDO::PARAM_INT);
		$s->bindValue(':id_inventory', $id_inventory, PDO::PARAM_INT);
		$s->bindValue(':number_sold', $number_sold, PDO::PARAM_INT);
		$s->bindValue(':is_garment', $is_garment, PDO::PARAM_INT);
		$s->execute();
	}

    public function deleteSold()
	{
		if ($this->_id != null) 
        {
            $table = self::$_table;

			$q = self::$_db->prepare("DELETE FROM $table WHERE id = :id");
			$q->bindValue(':id', $this->id());
			$q->execute();
		}
	}
}