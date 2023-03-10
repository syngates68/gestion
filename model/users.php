<?php

require_once('model.php');

class Users extends Model
{
    public static $_table = "users";

    private $_id;
	private $_name;
	private $_first_name;
	private $_mail;
	private $_password;
	private $_profile_picture;
	private $_date_registration;
	private $_confirmed;
	private $_active;

    public function __construct($id, $name, $first_name, $mail, $password, $profile_picture, $date_registration, $confirmed, $active)
    {
		$this->set_id($id);
		$this->set_name($name);
		$this->set_first_name($first_name);
		$this->set_mail($mail);
		$this->set_password($password);
		$this->set_profile_picture($profile_picture);
		$this->set_date_registration($date_registration);
		$this->set_confirmed($confirmed);
		$this->set_active($active);
	}

    public function id(){ return $this->_id; }
	public function name(){ return $this->_name; }
	public function first_name(){ return $this->_first_name; }
	public function mail(){ return $this->_mail; }
	public function password(){ return $this->_password; }
	public function profile_picture(){ return $this->_profile_picture; }
	public function date_registration(){ return $this->_date_registration; }
	public function confirmed(){ return $this->_confirmed; }
	public function active(){ return $this->_active; }

    public function set_id($id){ $this->_id = (int) $id; }
	public function set_name($name){ $this->_name = $name; }
	public function set_first_name($first_name){ $this->_first_name = $first_name; }
	public function set_mail($mail){ $this->_mail = $mail; }
	public function set_password($password){ $this->_password = $password; }
	public function set_profile_picture($profile_picture){ $this->_profile_picture = $profile_picture; }
	public function set_date_registration($date_registration){ $this->_date_registration = $date_registration; }
	public function set_confirmed($confirmed){ $this->_confirmed = $confirmed; }
	public function set_active($active){ $this->_active = $active; }

    public static function getByMail($mail) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE mail = :mail");
		$s->bindValue(':mail', $mail, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Users($data['id'], $data['name'], $data['first_name'], $data['mail'], $data['password'], $data['profile_picture'], $data['date_registration'], $data['confirmed'], $data['active']);
		else
			return null;
	}

    public static function getById($id) 
    {
        $table = self::$_table;

		$s = self::$_db->prepare("SELECT * FROM $table WHERE id = :id");
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
        
		if ($data)
			return new Users($data['id'], $data['name'], $data['first_name'], $data['mail'], $data['password'], $data['profile_picture'], $data['date_registration'], $data['confirmed'], $data['active']);
		else
			return null;
	}

	public function confirmAccount() 
    {
		$this->set_confirmed(1);
	}

    public function save()
	{
		if ($this->id() != null) 
        {
            $table = self::$_table;

            $sql = "UPDATE $table SET confirmed = :confirmed WHERE id = :id";

			$s = self::$_db->prepare($sql);
			$s->bindValue(':id', $this->id(), PDO::PARAM_INT);
			$s->bindValue(':confirmed', $this->active(), PDO::PARAM_INT);
			$s->execute();
		}
	}

    public static function insertUser($name, $first_name, $mail, $password, $profile_picture, $date_registration)
	{
        $table = self::$_table;

		$s = self::$_db->prepare("INSERT INTO $table (name, first_name, mail, password, profile_picture, date_registration) VALUES (:name, :first_name, :mail, :password, :profile_picture, :date_registration)");
		$s->bindValue(':name', $name, PDO::PARAM_STR);
		$s->bindValue(':first_name', $first_name, PDO::PARAM_STR);
		$s->bindValue(':mail', $mail, PDO::PARAM_STR);
		$s->bindValue(':password', $password, PDO::PARAM_STR);
		$s->bindValue(':profile_picture', $profile_picture, PDO::PARAM_STR);
		$s->bindValue(':date_registration', $date_registration, PDO::PARAM_STR);
		$s->execute();

		return Users::getById(parent::db()->lastInsertId());
	}
}