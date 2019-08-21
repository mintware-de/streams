<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Billbee\Tests\CustomShopApi\Http;

use MintWare\Streams\MemoryStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class MemoryStreamTest extends TestCase
{
    public function testConstruct()
    {
        $stream = new MemoryStream("test");
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(4, $stream->getSize());
    }
}