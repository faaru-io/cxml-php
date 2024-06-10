<?php

declare(strict_types=1);

namespace CXmlTest\Payload;

use CXml\Payload\DefaultPayloadIdentityFactory;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class DefaultPayloadIdentityFactoryTest extends TestCase
{
    public function testGenerateNewPayloadId(): void
    {
        $pif = new DefaultPayloadIdentityFactory(static fn (): DateTime|false =>
            // 2022-04-22 08:00:00.400000 +00:00
            DateTime::createFromFormat('U.v', '1650614400.400'));
        $actualIdentity = $pif->newPayloadIdentity();

        self::assertStringStartsWith('1650614400.400', $actualIdentity->getPayloadId());
    }
}
