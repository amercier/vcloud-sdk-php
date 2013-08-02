<?php
namespace VMware\VCloud;

abstract class Host extends AbstractObject
{
    protected $url = null;

    public function __construct(string $url)
    {
        if (parse_url($url) === false) {
            throw new Exception\MalformedUrl($url);
        }

        $this->set('url', $url);
    }

    public function getUrl()
    {
        return $this->get('url');
    }
}
