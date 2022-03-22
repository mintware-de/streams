<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019-2022 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace MintWare\Tests\Streams;

use MintWare\Streams\OutputStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class OutputStreamTest extends TestCase
{
    public function testConstruct(): void
    {
        $stream = new OutputStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testIsWritable(): void
    {
        $stream = new OutputStream();
        $this->assertTrue($stream->isWritable());
    }

    public function testIsSeekable(): void
    {
        $stream = new OutputStream();
        $this->assertFalse($stream->isSeekable());
    }

    public function testIsReadable(): void
    {
        $stream = new OutputStream();
        $this->assertFalse($stream->isReadable());
    }

    public function testGetSize(): void
    {
        $stream = new OutputStream();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Can not read the size of a write only stream.');
        $stream->getSize();
    }
}
