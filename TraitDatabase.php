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

    public function fetchQuery($table, $id, $column = 'id')
    {
        return $this->dbConnect->query("SELECT * FROM $table WHERE $column = '$id'")->fetch_assoc();
    }

    public function prepareProperty()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO properties (id, title, description, created_at, updated_at) VALUES (?,?,?,?,?)");
    }

    public function bindParamProperty($param)
    {
        $this->smtp->bind_param("issss", $param->id, $param->title, $param->description, $param->created_at, $param->updated_at);
    }

    public function prepareInformation()
    {
        $this->smtp = mysqli_prepare($this->dbConnect, "INSERT INTO informations (uuid, property_type_id, county, country, town, description, address, image_full, image_thumbnail, latitude, longitude, num_bedrooms, num_bathrooms, price, type, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    }

    public function bindParamInformation($param)
    {
        $this->smtp->bind_param("sisssssssssiiisss", $param->uuid, $param->property_type_id, $param->county, $param->country, $param->town, $param->description, $param->address, $param->image_full, $param->image_thumbnail, $param->latitude, $param->longitude, $param->num_bedrooms, $param->num_bathrooms, $param->price, $param->type, $param->created_at, $param->updated_at);
    }

    public function query()
    {
        $this->smtp->execute();
    }

    public function getInfoAndProperty($param)
    {
        $where = $this->filter($param);

        $page = isset($param['page']) && $param['page'] > 1 ? $param['page'] : 1;
        $perPage = $this->perPage($param);
        $limit = ' LIMIT ' . ($page - 1) * $perPage . ', ' . $perPage;

        return $this->dbConnect->query("SELECT * FROM informations LEFT JOIN properties ON informations.property_type_id = properties.id" . $where . $limit)->fetch_all(MYSQLI_ASSOC);
    }

    public function total($param)
    {
        $where = $this->filter($param);

        $total = $this->dbConnect->query("SELECT COUNT(*) FROM informations LEFT JOIN properties ON informations.property_type_id = properties.id" . $where);
        return $total->fetch_array()[0];
    }

    public function pagination($param)
    {
        $total = $this->total($param);
        return $total ? ceil($total / $this->perPage($param)) : 0;
    }

    private function perPage($param)
    {
        return isset($param['per_page']) && $param['per_page'] ? $param['per_page'] : 30;;
    }

    private function filter($param)
    {
        $where = " where 1 = 1";
        if ($param) {
            if (isset($param['town']) && $param['town']) {
                $where .= " and informations.town like '%" . trim($param['town']) . "%'";
            }
            if (isset($param['number']) && $param['number']) {
                $where .= " and informations.num_bedrooms = '" . trim($param['number']) . "'";
            }
            if (isset($param['price']) && $param['price']) {
                $where .= " and informations.price = '" . trim($param['price']) . "'";
            }
            if (isset($param['property_type']) && $param['property_type']) {
                $where .= " and properties.title like '%" . trim($param['property_type']) . "%'";
            }
            if (isset($param['type']) && $param['type']) {
                $where .= " and informations.type = '" . trim($param['type']) . "'";
            }
        }

        return $where;
    }
}