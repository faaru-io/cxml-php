<?php

namespace CXml;

use CXml\Jms\CXmlWrappingNodeJmsEventSubscriber;
use CXml\Jms\JmsDateTimeHandler;
use CXml\Model\CXml;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class Serializer
{
	private SerializerInterface $jmsSerializer;

	private function __construct(SerializerInterface $jmsSerializer)
	{
		$this->jmsSerializer = $jmsSerializer;
	}

	public static function create(): self
	{
		$jmsSerializer = SerializerBuilder::create()
			->configureListeners(function (EventDispatcherInterface $dispatcher): void {
				$dispatcher->addSubscriber(new CXmlWrappingNodeJmsEventSubscriber());
				/*$dispatcher->addListener('serializer.pre_serialize', function() {

				});*/
			})
			->configureHandlers(function (HandlerRegistry $registry): void {
				$handler = new JmsDateTimeHandler();

				$callable = [
					$handler,
					'serialize',
				];
				$registry->registerHandler(GraphNavigatorInterface::DIRECTION_SERIALIZATION, \DateTimeInterface::class, 'xml', $callable);
				$registry->registerHandler(GraphNavigatorInterface::DIRECTION_SERIALIZATION, \DateTime::class, 'xml', $callable);

				$callable = [
					$handler,
					'deserialize',
				];
				$registry->registerHandler(GraphNavigatorInterface::DIRECTION_DESERIALIZATION, \DateTimeInterface::class, 'xml', $callable);
				$registry->registerHandler(GraphNavigatorInterface::DIRECTION_DESERIALIZATION, \DateTime::class, 'xml', $callable);
			})
			->setPropertyNamingStrategy(
				new IdenticalPropertyNamingStrategy()
			)
			->build()
		;

		return new self($jmsSerializer);
	}

	public function deserialize(string $xml): CXml
	{
		// remove doctype (if exists), as it would throw a JMS\Serializer\Exception\InvalidArgumentException
		$xml = \preg_replace('/<!doctype.+>/i', '', $xml);

		if (empty($xml)) {
			throw new \RuntimeException('Cannot deserialize empty string');
		}

		return $this->jmsSerializer->deserialize($xml, CXml::class, 'xml');
	}

	public function serialize(CXml $cxml): string
	{
		$xml = $this->jmsSerializer->serialize($cxml, 'xml');

		// TODO ugly hack todo fix me - necessary for ariba
		$docType = '<!DOCTYPE cXML SYSTEM "http://xml.cxml.org/schemas/cXML/1.2.044/cXML.dtd">';

		return \str_replace('<?xml version="1.0" encoding="UTF-8"?>', '<?xml version="1.0" encoding="UTF-8"?>'.$docType, $xml);
	}
}
