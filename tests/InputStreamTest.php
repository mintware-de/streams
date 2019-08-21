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

use MintWare\Streams\InputStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class InputStreamTest extends TestCase
{
    public function testConstruct()
    {
        $stream = new InputStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
    }

    public function testIsWritable()
    {
        $stream = new InputStream();
        $this->assertFalse($stream->isWritable());
    }

    public function testIsSeekable()
    {
        $stream = new InputStream();
        $this->assertTrue($stream->isSeekable());
    }

    public function testIsReadable()
    {
        $stream = new InputStream();
        $this->assertTrue($stream->isReadable());
    }
}