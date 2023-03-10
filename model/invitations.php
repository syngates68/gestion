<?php

require_once('model.php');

class Invitations extends Model
{
    public static $_table = "invitations";

    private $_id;
	private $_mail;
	private $_id_page;
	private $_uuid;
	private $_date_invitation;
	private $_active;

    public function __construct($id, $mail, $id_page, $uuid, $date_invitation, $active)
    {
		$this->set_id($id);
		$this->set_mail($mail);
		$this->set_id_page($id_page);
		$this->set_uuid($uuid);
		$this->set_date_invitation($date_invitation);
		$this->set_active($active);
	}

    public function id(){ return $this->_id; }
    public function mail(){ return $this->_mail; }
    public function id_page(){ return $this->_id_page; }
    public function uuid(){ return $this->_uuid; }
    public function date_invitation(){ return $this->_date_invitation; }
    public function active(){ return $this->_active; }

    public function set_id($id){ $this->_id = (int) $id; }
    public function set_mail($mail){ $this->_mail = $mail; }
    public function set_id_page($id_page){ $this->_id_page = $id_page; }
	public function set_uuid($uuid){ $this->_uuid = $uuid; }
	public function set_date_invitation($date_invitation){ $this->_date_invitation = $date_invitation; }
	public function set_active($active){ $this->_active = $active; }

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Invitations($data['id'], $data['mail'], $data['id_page'], $data['uuid'], $data['date_invitation'], $data['active']);
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
			return new Invitations($data['id'], $data['mail'], $data['id_page'], $data['uuid'], $data['date_invitation'], $data['active']);
		else
			return null;
	}

    public function disableInvitation() 
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

    public static function insertInvitation($mail, $id_page, $uuid, $date_invitation)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (mail, id_page, uuid, date_invitation) VALUES (:mail, :id_page, :uuid, :date_invitation)");
		$s->bindValue(':mail', $mail, PDO::PARAM_STR);
		$s->bindValue(':id_page', $id_page, PDO::PARAM_INT);
		$s->bindValue(':uuid', $uuid, PDO::PARAM_STR);
		$s->bindValue(':date_invitation', $date_invitation, PDO::PARAM_STR);
		$s->execute();

        return Invitations::getById(parent::db()->lastInsertId());
	}
}