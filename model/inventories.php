<?php

require_once('model.php');
require_once('productsizes.php');
require_once('sizes.php');

class Inventories extends Model
{
    public static $_table = "inventories";

    private $_id;
	private $_id_page;
	private $_description;
	private $_price;
	private $_stock;
	private $_picture;
	private $_active;
    private $_sizes;

    public function __construct($id, $id_page, $description, $price, $stock, $picture, $active, $sizes = [])
    {
		$this->set_id($id);
		$this->set_id_page($id_page);
		$this->set_description($description);
		$this->set_price($price);
		$this->set_stock($stock);
		$this->set_picture($picture);
		$this->set_active($active);
		$this->set_sizes($sizes);
	}

    public function id(){ return $this->_id; }
    public function id_page(){ return $this->_id_page; }
    public function description(){ return $this->_description; }
    public function price(){ return $this->_price; }
    public function stock(){ return $this->_stock; }
    public function picture(){ return $this->_picture; }
    public function active(){ return $this->_active; }
    public function sizes(){ return $this->_sizes; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_page($id_page){ $this->_id_page = $id_page; }
    public function set_description($description){ $this->_description = $description; }
    public function set_price($price){ $this->_price = $price; }
    public function set_stock($stock){ $this->_stock = $stock; }
    public function set_picture($picture){ $this->_picture = $picture; }
    public function set_active($active){ $this->_active = $active; }
    public function set_sizes($sizes){ $this->_sizes = $sizes; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Inventories($data['id'], $data['id_page'], $data['description'], $data['price'], $data['stock'], $data['picture'], $data['active']);
		else
			return null;
	}

    public static function getAllByPage($id_page) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_page = :id_page AND active = 1");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->execute();
        $res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
			$productSizes = ProductSizes::getAllByInventory($row['id']);
			$sizes = [];

			if ($productSizes != null)
			{
				foreach ($productSizes as $ps)
				{
					array_push($sizes, $ps);
				}
			}

            array_push($res, new Inventories($row['id'], $row['id_page'], $row['description'], 
            $row['price'], $row['stock'], $row['picture'], $row['active'], $sizes));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

    public static function insertInventory($id_page, $description, $price, $stock)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_page, description, price, stock) VALUES (:id_page, :description, :price, :stock)");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->bindValue(':description', $description, PDO::PARAM_STR);
		$s->bindValue(':price', $price, PDO::PARAM_STR);
		$s->bindValue(':stock', $stock, PDO::PARAM_INT);
		$s->execute();

		return Inventories::getById(parent::db()->lastInsertId());
	}

    public function modifyInventory($description, $price, $stock) 
    {
		$this->set_description($description);
		$this->set_price($price);
		$this->set_stock($stock);
	}

    public function disableInventory() 
    {
		$this->set_active(0);
	}

    public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET description = :description, price = :price, stock = :stock, picture = :picture, active = :active WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':description', $this->description(), PDO::PARAM_STR);
			$s->bindValue(':price', $this->price(), PDO::PARAM_STR);
			$s->bindValue(':stock', $this->stock(), PDO::PARAM_INT);
			$s->bindValue(':picture', $this->picture(), PDO::PARAM_STR);
			$s->bindValue(':active', $this->active(), PDO::PARAM_INT);
			$s->execute();
		}
	}
}