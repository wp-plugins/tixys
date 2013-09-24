<?php

class tixys_backend
{
    private $settings;

    private $api_base = 'https://tixo.net/api/v1/StationSearch?request=%1$s&locale=%2$s';

    private $cache_ttl = 300;

    public function __construct(stdClass $settings)
    {
        $this->settings = $settings;
    }

    public function get_result($type, $id=null)
    {
        $result = $this->cache_get($type, $id);

        if (!is_array($result))
        {
            $result = $this->retrieve_result($type, $id);
            $this->cache_put($type, $id, $result);
        }

        return $result;
    }

    private function cache_get($type, $id)
    {
        $value = get_transient($this->cache_create_key($type, $id));
        return is_array($value) ? $value : null;
    }

    private function cache_put($type, $id, $result)
    {
        $value = set_transient($this->cache_create_key($type, $id), $result, $this->cache_ttl);
    }

    private function cache_create_key($type, $id)
    {
        $key = ($type === 'start') ? 'start' : "$type-$id";
        return sprintf('%s-%s', $key, $this->settings->site);
    }

    private function retrieve_result($type, $id)
    {
        try
        {
            $request = (object)array(
                'Site' => $this->settings->site,
                'orderBy' => 'name'
            );

            if ($type === 'from')   $request->StationFrom = $id;
            elseif ($type === 'to') $request->StationTo = $id;
            else                    $request->onlyStartStations = true;

            $request = urlencode(json_encode($request));
            $response = file_get_contents(sprintf($this->api_base, $request, get_locale()));

            $response = json_decode($response);

            if (!$response || !is_object($response) || !isset($response->success) || $response->success !== true)
                throw new \Exception("Failed loading data.");

            $payload = $this->transform_api_result($response->payload, $response->entityList);
            $list = $this->filter_station_list($payload->itemList);
        }
        catch(\Exception $e)
        {
            $list = null;
        }

        return $list;
    }

    private function transform_api_result($value, $entityList)
    {
        if (is_object($value))
        {
            $new_value = new stdClass();

            foreach ((array)$value as $k=>$v)
                $new_value->$k = $this->transform_api_result($v, $entityList);
        }
        elseif (is_array($value))
        {
            $new_value = array();

            foreach ($value as $k=>$v)
                $new_value[$k] = $this->transform_api_result($v, $entityList);
        }
        else if (is_string($value) && preg_match("|#e#[a-z]+:[0-9]+|i", $value))
        {
            $key = substr($value, 3);
            $new_value = $this->transform_api_result($entityList->$key, $entityList);
        }
        else
        {
            $new_value = $value;
        }

        return $new_value;
    }

    private function filter_station_list($list)
    {
        foreach ($list as $k=>$station)
            unset($list[$k]->Site, $list[$k]->status, $list[$k]->description, $list[$k]->timezone, $list[$k]->lat, $list[$k]->lon);

        return $list;
    }
}
