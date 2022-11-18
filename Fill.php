<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

class Fill
{
    use TraitDatabase;

    private $command = 'php Curl.php';
    private $tableProperties = 'properties';
    private $tableInformations = 'informations';
    private $tablePlaces = 'places';
    private $tableTypes = 'types';

    public function __construct()
    {
        $this->connect();
    }

    public function getData($number, $sie)
    {
        $info = $this->getInfo($number, $sie);

        if ($info) {
            $decodeOutput = json_decode($info);
            $items = $decodeOutput ? $decodeOutput->data : null;
            if ($items) {
                foreach ($items as $item) {
                    $this->fiiDatabase($item);
                    if ($number != $decodeOutput->last_page) {
                         $this->getData(++$number, $sie);
                    }
                }
            }
        }
    }

    public function getInfo($number, $size)
    {
        $number = escapeshellarg($number);
        $size = escapeshellarg($size);
        return shell_exec("$this->command $number $size");
    }

    public function fiiDatabase($data)
    {
        $this->setProperty($data);
        $place = $this->setPlace($data);
        $type = $this->setType($data);
        $data->place_id = $place['id'];
        $data->type_id = $type['id'];
        $this->setInformation($data);
    }

    public function setProperty($data)
    {
        if($data->property_type_id) {
            $exists = $this->fetchQuery($this->tableProperties, $data->property_type_id);
            if (!$exists) {
                $this->prepareProperty();
                $this->bindParamProperty($data->property_type);
                $this->query();
            }
        }
    }

    public function setInformation($data)
    {
        $exists = $this->fetchQuery($this->tableInformations, $data->uuid, 'uuid');
        if (!$exists) {
            $this->prepareInformation();
            $this->bindParamInformation($data);
            $this->query();
        }
    }

    public function setPlace($data)
    {
        $place = $this->fetchQuery($this->tablePlaces, $data->town, 'town');
        if (!$place) {
            $this->preparePlace();
            $this->bindParamPlace($data);
            $this->query();
            $place = $this->fetchQuery($this->tablePlaces, $data->town, 'town');
        }
        return $place;
    }

    public function setType($data)
    {
        $type = $this->fetchQuery($this->tableTypes, $data->type, 'type');
        if (!$type) {
            $this->prepareType();
            $this->bindParamType($data);
            $this->query();
            $type = $this->fetchQuery($this->tableTypes, $data->type, 'type');
        }
        return $type;
    }
}

$fill = new Fill();
$fill->getData(1, 100);
