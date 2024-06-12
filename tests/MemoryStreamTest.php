<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019-2024 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace MintWare\Tests\Streams;

use MintWare\Streams\MemoryStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class MemoryStreamTest extends TestCase
{
    public function testConstruct(): void
    {
        $stream = new MemoryStream("test");
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(4, $stream->getSize());
    }
}
