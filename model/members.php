<?php

require_once('model.php');

class Members extends Model
{
    public static $_table = "members";

    private $_id;
	private $_id_page;
	private $_id_user;
	private $_active;

    public function __construct($id, $id_page, $id_user, $active)
    {
		$this->set_id($id);
		$this->set_id_page($id_page);
		$this->set_id_user($id_user);
		$this->set_active($active);
	}

    public function id(){ return $this->_id; }
    public function id_page(){ return $this->_id_page; }
    public function id_user(){ return $this->_id_user; }
	public function active(){ return $this->_active; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_id_page($id_page){ $this->_id_page = $id_page; }
    public function set_id_user($id_user){ $this->_id_user = $id_user; }
	public function set_active($active){ $this->_active = $active; }

	public static function getByUser($id_user) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_user = :id_user");

		$s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$s->execute();
		$res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new Members($row['id'], $row['id_page'], $row['id_user'], $row['active']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

	public static function getAllByPage($id_page) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id_page = :id_page");

		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->execute();
		$res = [];

		while ($row = $s->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
        {
            array_push($res, new Members($row['id'], $row['id_page'], $row['id_user'], $row['active']));
        }

		if (!empty($res))
			return $res;
		else 
			return null;
	}

	public static function countByUser($id_user)
    {
        $table = self::$_table;

        $s = self::$_db->prepare("SELECT COUNT(*) AS total FROM $table WHERE id_user = :id_user");
        $s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $s->execute();
        $count = $s->fetch(PDO::FETCH_ASSOC);

        if ($count)
            return $count['total'];
        else
            return null;
    }

	public static function countByPage($id_page)
    {
        $table = self::$_table;

        $s = self::$_db->prepare("SELECT COUNT(*) AS total FROM $table WHERE id_page = :id_page");
        $s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
        $s->execute();
        $count = $s->fetch(PDO::FETCH_ASSOC);

        if ($count)
            return $count['total'];
        else
            return null;
    }

	public static function countByUserAndPage($id_user, $id_page)
    {
        $table = self::$_table;

        $s = self::$_db->prepare("SELECT COUNT(*) AS total FROM $table WHERE id_user = :id_user AND id_page = :id_page");
        $s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
        $s->execute();
        $count = $s->fetch(PDO::FETCH_ASSOC);

        if ($count)
            return $count['total'];
        else
            return null;
    }

	public static function insertMember($id_page, $id_user)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (id_page, id_user) VALUES (:id_page, :id_user)");
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$s->execute();
	}
}