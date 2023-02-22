<?php

namespace CXml\Model;

/**
 * Represents a date *without* time. This is a separate class to allow for different serialization formats.
 */
class Date extends \DateTime
{
	public static function createFromFormat($format, $datetime, \DateTimeZone $timezone = null)
	{
		$dateTime = parent::createFromFormat($format, $datetime, $timezone);
		if (!$dateTime) {
			return false;
		}

		return new self($dateTime->format('Y-m-d'), $timezone);
	}
}