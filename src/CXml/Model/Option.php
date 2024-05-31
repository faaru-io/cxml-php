<?php

namespace CXml\Model;

use JMS\Serializer\Annotation as Serializer;

readonly class Option
{
    public function __construct(
        #[Serializer\XmlAttribute]
        private string $name,
        #[Serializer\XmlValue(cdata: false)]
        private string $value
    ) {
    }
}
