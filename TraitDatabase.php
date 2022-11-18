<?php

trait TraitDatabase
{
    static $database = 'php';
    static $host = 'mysql';
    static $userName = 'root';
    static $password = 'root';
    protected $dbConnect;
    protected $smtp;

    public function connect()
    {
        $this->dbConnect = mysqli_connect(self::$host, self::$userName, self::$password, self::$database) or die(mysqli_error());
    }

    public function fetchQuery($table, $value, $column = 'id')
    {
        return $this->dbConnect->query('SELECT * FROM ' . $table . ' WHERE ' . $column . ' = "' . $value . '"')->fetch_assoc();
    }

    public function prepareProperty()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO properties (id, title, description, created_at, updated_at) VALUES (?,?,?,?,?)");
    }

    public function bindParamProperty($param)
    {
        $this->smtp->bind_param("issss", $param->id, $param->title, $param->description, $param->created_at, $param->updated_at);
    }

    public function preparePlace()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO places (id, county, country, town, address, latitude, longitude, num_bedrooms, num_bathrooms) VALUES (?,?,?,?,?,?,?,?,?)");
    }

    public function bindParamPlace($param)
    {
        $this->smtp->bind_param("issssssii", $param->id, $param->county, $param->country, $param->town, $param->address, $param->latitude, $param->longitude, $param->num_bedrooms, $param->num_bathrooms);
    }

    public function prepareType()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO types (id, type) VALUES (?,?)");
    }

    public function bindParamType($param)
    {
        $this->smtp->bind_param("is", $param->id, $param->type);
    }

    public function prepareInformation()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO informations (uuid, property_type_id, place_id, description, image_full, image_thumbnail, price, type_id, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)");
    }

    public function bindParamInformation($param)
    {
        $this->smtp->bind_param("siisssiiss", $param->uuid, $param->property_type_id, $param->place_id, $param->description, $param->image_full, $param->image_thumbnail, $param->price, $param->type_id, $param->created_at, $param->updated_at);
    }

    public function query()
    {
        $this->smtp->execute();
    }

    public function getInfo($where, $limit)
    {
        return $this->dbConnect->query("SELECT * FROM informations LEFT JOIN properties ON informations.property_type_id = properties.id 
                                                                   LEFT JOIN places ON informations.place_id = places.id 
                                                                   LEFT JOIN types ON informations.type_id = types.id 
                                                                  " . $where . $limit)->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotal($where)
    {
        $total = $this->dbConnect->query("SELECT COUNT(*) FROM informations LEFT JOIN properties ON informations.property_type_id = properties.id 
                                                                   LEFT JOIN places ON informations.place_id = places.id 
                                                                   LEFT JOIN types ON informations.type_id = types.id 
                                                                  " . $where);
        return $total->fetch_array()[0];
    }

    public function getType()
    {
        return $this->dbConnect->query("SELECT * FROM types")->fetch_all(MYSQLI_ASSOC);
    }

    public function getTown()
    {
        return $this->dbConnect->query("SELECT id, town FROM places")->fetch_all(MYSQLI_ASSOC);
    }
}