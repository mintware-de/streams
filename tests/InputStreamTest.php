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

use MintWare\Streams\InputStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class InputStreamTest extends TestCase
{
    public function testConstruct(): void
    {
        $stream = new InputStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
    }

    public function testIsWritable(): void
    {
        $stream = new InputStream();
        $this->assertFalse($stream->isWritable());
    }

    public function testIsSeekable(): void
    {
        $stream = new InputStream();
        $this->assertTrue($stream->isSeekable());
    }

    public function testIsReadable(): void
    {
        $stream = new InputStream();
        $this->assertTrue($stream->isReadable());
    }

    public function testGetSize(): void
    {
        $stream = new InputStream();
        $this->assertNull($stream->getSize());
        $_SERVER['CONTENT_LENGTH'] = 1337;
        $this->assertEquals(1337, $stream->getSize());
    }
}
