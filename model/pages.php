<?php

require_once('model.php');

class Pages extends Model
{
    public static $_table = "pages";

    private $_id;
	private $_id_creator;
	private $_name;
	private $_picture;
	private $_uuid;
	private $_active;

    public function __construct($id, $id_creator, $name, $picture, $uuid, $active)
    {
		$this->set_id($id);
		$this->set_id_creator($id_creator);
		$this->set_name($name);
		$this->set_picture($picture);
		$this->set_uuid($uuid);
		$this->set_active($active);
	}

    public function id(){ return $this->_id; }
    public function id_creator(){ return $this->_id_creator; }
    public function name(){ return $this->_name; }
    public function picture(){ return $this->_picture; }
    public function uuid(){ return $this->_uuid; }
	public function active(){ return $this->_active; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_creator($id_creator){ $this->_id_creator = $id_creator; }
    public function set_name($name){ $this->_name = $name; }
    public function set_picture($picture){ $this->_picture = $picture; }
    public function set_uuid($uuid){ $this->_uuid = $uuid; }
	public function set_active($active){ $this->_active = $active; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Pages($data['id'], $data['id_creator'], $data['name'], $data['picture'], $data['uuid'], $data['active']);
		else
			return null;
	}

    public static function getByUuid($uuid) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE uuid = :uuid");
		$s->bindValue(':uuid', $uuid, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Pages($data['id'], $data['id_creator'], $data['name'], $data['picture'], $data['uuid'], $data['active']);
		else
			return null;
	}

    public static function insertPage($id_creator, $name, $uuid)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_creator, name, uuid) VALUES (:id_creator, :name, :uuid)");
		$s->bindValue(':id_creator', $id_creator, PDO::PARAM_INT);
		$s->bindValue(':name', $name, PDO::PARAM_STR);
		$s->bindValue(':uuid', $uuid, PDO::PARAM_STR);
		$s->execute();

        return Pages::getById(parent::db()->lastInsertId());
	}

	public function modifyPage($name) 
    {
		$this->set_name($name);
	}

    public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET name = :name, picture = :picture WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':name', $this->name(), PDO::PARAM_STR);
			$s->bindValue(':picture', $this->picture(), PDO::PARAM_STR);
			$s->execute();
		}
	}
}