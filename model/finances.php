<?php

require_once('model.php');

class Finances extends Model
{
    public static $_table = "finances";

    private $_id;
	private $_id_page;
	private $_type;
	private $_description;
	private $_amount;
	private $_date_add;
	private $_active;

    public function __construct($id, $id_page, $type, $description, $amount, $date_add, $active)
    {
		$this->set_id($id);
		$this->set_id_page($id_page);
		$this->set_type($type);
		$this->set_description($description);
		$this->set_amount($amount);
		$this->set_date_add($date_add);
		$this->set_active($active);
	}

    public function id(){ return $this->_id; }
    public function id_page(){ return $this->_id_page; }
    public function type(){ return $this->_type; }
    public function description(){ return $this->_description; }
    public function amount(){ return $this->_amount; }
    public function date_add(){ return $this->_date_add; }
    public function active(){ return $this->_active; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_page($id_page){ $this->_id_page = $id_page; }
    public function set_type($type){ $this->_type = $type; }
    public function set_description($description){ $this->_description = $description; }
    public function set_amount($amount){ $this->_amount = $amount; }
    public function set_date_add($date_add){ $this->_date_add = $date_add; }
    public function set_active($active){ $this->_active = $active; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Finances($data['id'], $data['id_page'], $data['type'], $data['description'], $data['amount'], $data['date_add'], $data['active']);
		else
			return null;
	}

    public static function getAllByPage($id_page) 
    {
        $table = self::$_table;
        
		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_page = :id_page AND active = 1 ORDER BY date_add DESC");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->execute();
        $res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new Finances($row['id'], $row['id_page'], $row['type'], $row['description'], 
            $row['amount'], $row['date_add'], $row['active']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

    public static function getCaisseByPage($id_page)
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_page = :id_page AND active = 1");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->execute();
        $data = $s->fetchAll(PDO::FETCH_ASSOC);

        $recettes_totales = 0; //Total des entrées (sans apport)
        $caisse = 0; //Total des entrées et sorties (sans apport)
        $apport = 0; //Total des apports
        
        if ($data)
        {
            foreach ($data as $d)
            {
                //Sortie d'argent
                if ($d['type'] == 0)
                    $caisse -= $d['amount'];
                //Entrée d'argent
                else if ($d['type'] == 1)
                {
                    $caisse += $d['amount'];
                    $recettes_totales += $d['amount'];
                }
                //Apport
                else
                    $apport += $d['amount'];
            }
        }

        $reel_gagne = $recettes_totales - $apport;

        return [$recettes_totales, $caisse, $apport, $reel_gagne];
    }

    public function deleteFinance() 
    {
		$this->set_active(0);
	}

    public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET active = :active WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':active', $this->active(), PDO::PARAM_INT);
			$s->execute();
		}
	}

    public static function insertFinance($id_page, $type, $description, $amount, $date_add)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_page, type, description, amount, date_add) VALUES (:id_page, :type, :description, :amount, :date_add)");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->bindValue(':type', $type, PDO::PARAM_INT);
		$s->bindValue(':description', $description, PDO::PARAM_STR);
		$s->bindValue(':amount', $amount, PDO::PARAM_STR);
		$s->bindValue(':date_add', $date_add, PDO::PARAM_STR);
		$s->execute();

        return Finances::getById(parent::db()->lastInsertId());
	}
}