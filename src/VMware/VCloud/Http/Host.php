<?php

namespace VMware\VCloud\Http;

use \VMware\VCloud\Object;
use \VMware\VCloud\Exception\MalformedUrl;

class Host extends Object
{
    protected $url = null;

    public function __construct($url)
    {
        $fragments = parse_url($url);
        if ($fragments === false
            || array_key_exists('host', $fragments)
            || array_key_exists('scheme', $fragments)
            || array_key_exists('port', $fragments)
            || !array_key_exists('path', $fragments)
            || array_key_exists('query', $fragments)
            || array_key_exists('fragment', $fragments)
            || strpos($url, ' ') !== false
            || strpos($url, '/') !== false
        ) {
            throw new MalformedUrl($url . ' => ' . print_r($fragments, true));
        }

        $this->set('url', $url);
    }

    public function getUrl()
    {
        return $this->get('url');
    }
}
