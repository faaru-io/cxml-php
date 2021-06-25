<?php

namespace Mathielen\CXml\Model;

use JMS\Serializer\Annotation as Ser;

class Transaction
{
    /**
     * @Ser\XmlAttribute
     */
    private string $requestName;

    /**
     * @Ser\SerializedName("URL")
     * @Ser\XmlElement(cdata=false)
     */
    private string $url;

    /**
     * @Ser\XmlList(inline=true, entry="Option")
     *
     * @var Option[]
     */
    private array $options = [];

    public function __construct(string $requestName, string $url)
    {
        $this->requestName = $requestName;
        $this->url = $url;
    }

    public function addOption(Option $option): void
    {
        $this->options[] = $option;
    }
}
