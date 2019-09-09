<?php
class VendingModel
{
    private $money = array();
    private $products = array();
    private $valet = array();

    public function __construct($data = array())
    {

        function objectToArray($query)
        {
            if (is_object($query)) {
                $query = get_object_vars($query);
            }

            if (is_array($query)) {
                return array_map(__FUNCTION__, $query);
            } else {
                return $query;
            }
        }

        foreach ($data as $file) {
            $this->{$file} = objectToArray(json_decode((file_get_contents(DB . $file . '.json'))));
        }
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        $trace = debug_backtrace();
        trigger_error(
            'Неопределенное свойство в __get(): ' . $property .
            ' в файле ' . $trace[0]['file'] .
            ' на строке ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
}
