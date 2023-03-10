<?php

require_once('model.php');

class Ranks extends Model
{
    public static $_table = "ranks";

    private $_id;
	private $_id_page;
	private $_id_user;
	private $_label;
	private $_finances;
	private $_inventory;
	private $_settings;

    public function __construct($id, $id_page, $id_user, $label, $finances, $inventory, $settings)
    {
		$this->set_id($id);
		$this->set_id_page($id_page);
		$this->set_id_user($id_user);
		$this->set_label($label);
		$this->set_finances($finances);
		$this->set_inventory($inventory);
		$this->set_settings($settings);
	}

    public function id(){ return $this->_id; }
    public function id_page(){ return $this->_id_page; }
    public function id_user(){ return $this->_id_user; }
	public function label(){ return $this->_label; }
	public function finances(){ return $this->_finances; }
	public function inventory(){ return $this->_inventory; }
	public function settings(){ return $this->_settings; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_page($id_page){ $this->_id_page = $id_page; }
    public function set_id_user($id_user){ $this->_id_user = $id_user; }
	public function set_label($label){ $this->_label = $label; }
	public function set_finances($finances){ $this->_finances = $finances; }
	public function set_inventory($inventory){ $this->_inventory = $inventory; }
	public function set_settings($settings){ $this->_settings = $settings; }

    public static function getByUserAndPage($id_user, $id_page) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_user = :id_user AND id_page = :id_page");
		$s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Ranks($data['id'], $data['id_page'], $data['id_user'], $data['label'], $data['finances'], $data['inventory'], $data['settings']);
		else
			return null;
	}

	public function modifyRank($label, $finances, $inventory, $settings) 
    {
		$this->set_label($label);
		$this->set_finances($finances);
		$this->set_inventory($inventory);
		$this->set_settings($settings);
	}

    public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET label = :label, finances = :finances, inventory = :inventory, settings = :settings WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':label', $this->label(), PDO::PARAM_STR);
			$s->bindValue(':finances', $this->finances(), PDO::PARAM_INT);
			$s->bindValue(':inventory', $this->inventory(), PDO::PARAM_INT);
			$s->bindValue(':settings', $this->settings(), PDO::PARAM_INT);
			$s->execute();
		}
	}

	public static function insertRank($id_page, $id_user, $label, $finances, $inventory, $settings)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_page, id_user, label, finances, inventory, settings) VALUES (:id_page, :id_user, :label, :finances, :inventory, :settings)");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$s->bindValue(':label', $label, PDO::PARAM_STR);
		$s->bindValue(':finances', $finances, PDO::PARAM_INT);
		$s->bindValue(':inventory', $inventory, PDO::PARAM_INT);
		$s->bindValue(':settings', $settings, PDO::PARAM_INT);
		$s->execute();
	}
}