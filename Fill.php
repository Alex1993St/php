<?php
require_once 'TraitDatabase.php';

class Fill
{
    use TraitDatabase;

    private $command = 'php Curl.php';
    private $tableProperties = 'properties';
    private $tableInformations = 'informations';

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
                // TODO переробити на batch insert
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
        $this->setInformation($data);
    }

    public function setProperty($data)
    {
        if($data->property_type_id) {
            // TODO можена закешувати $exists щоб не робити зайві запити в БД
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
        // TODO можена закешувати $exists щоб не робити зайві запити в БД
        $exists = $this->fetchQuery($this->tableInformations, $data->uuid, 'uuid');
        if (!$exists) {
            $this->prepareInformation();
            $this->bindParamInformation($data);
            $this->query();
        }
    }
}

$fill = new Fill();
$fill->getData(1, 100);
