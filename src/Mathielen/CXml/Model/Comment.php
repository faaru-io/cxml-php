<?php

namespace Mathielen\CXml\Model;

use JMS\Serializer\Annotation as Ser;

class Comment
{
	/**
	 * @Ser\SerializedName("Attachment")
	 */
	private ?Url $attachment;

	/**
	 * @Ser\XmlValue(cdata=false)
	 */
	private ?string $value;

	/**
	 * @Ser\XmlAttribute(namespace="http://www.w3.org/XML/1998/namespace")
	 */
	private ?string $lang;

	/**
	 * @Ser\XmlAttribute()
	 */
	private ?string $type;

	public function __construct(?string $value = null, ?string $type = null, ?string $lang = null, ?string $attachment = null)
	{
		$this->value = $value;
		$this->type = $type;
		$this->lang = $lang;
		$this->attachment = $attachment ? new Url($attachment) : $attachment;
	}

	public function getAttachment()
	{
		return $this->attachment;
	}

	public function getValue(): ?string
	{
		return $this->value;
	}

	public function getLang(): ?string
	{
		return $this->lang;
	}

	public function getType(): ?string
	{
		return $this->type;
	}
}
