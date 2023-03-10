<?php

require_once('model.php');

class ProductSizes extends Model
{
    public static $_table = "product_sizes";

    private $_id;
	private $_id_inventory;
	private $_id_size;
	private $_stock;

    public function __construct($id, $id_inventory, $id_size, $stock)
    {
		$this->set_id($id);
		$this->set_id_inventory($id_inventory);
		$this->set_id_size($id_size);
		$this->set_stock($stock);
	}

    public function id(){ return $this->_id; }
    public function id_inventory(){ return $this->_id_inventory; }
    public function id_size(){ return $this->_id_size; }
    public function stock(){ return $this->_stock; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_inventory($id_inventory){ $this->_id_inventory = $id_inventory; }
    public function set_id_size($id_size){ $this->_id_size = $id_size; }
    public function set_stock($stock){ $this->_stock = $stock; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new ProductSizes($data['id'], $data['id_inventory'], $data['id_size'], $data['stock']);
		else
			return null;
	}

    public static function getAllByInventory($id_inventory) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_inventory = :id_inventory");
		$s->bindValue(':id_inventory', $id_inventory, PDO::PARAM_INT);
        $s->execute();
        $res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new ProductSizes($row['id'], $row['id_inventory'], $row['id_size'], $row['stock']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

    public static function insertProductSize($id_inventory, $id_size)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_inventory, id_size) VALUES (:id_inventory, :id_size)");
		$s->bindValue(':id_inventory', $id_inventory, PDO::PARAM_INT);
		$s->bindValue(':id_size', $id_size, PDO::PARAM_INT);
		$s->execute();
	}

	public function modifyProductSize($stock) 
    {
		$this->set_stock($stock);
	}

	public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET stock = :stock WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':stock', $this->stock(), PDO::PARAM_INT);
			$s->execute();
		}
	}
}