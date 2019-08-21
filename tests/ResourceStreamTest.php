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

use MintWare\Streams\ResourceStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class TestResourceStream extends ResourceStream
{
    public function __construct()
    {
        $this->handle = fopen('php://temp', 'rw');
    }
}

class ResourceStreamTest extends TestCase
{
    public function testConstruct()
    {
        $stream = $this->createStreamMock();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
    }

    public function test__ToString()
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        $this->assertEquals('Hello World', (string)$stream);
    }

    public function testClose()
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->assertFalse($stream->isSeekable());
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    public function testDetach()
    {
        $stream = $this->createStreamMock();
        $stream->seek(0);
        $handle = $stream->detach();
        $this->assertTrue($handle !== null && !is_resource($handle));

        $this->expectException(RuntimeException::class);
        $stream->seek(0);
    }

    public function testGetSize()
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->getSize());
        $stream->close();
        $this->assertNull($stream->getSize());
    }

    public function testTell()
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->seek(0);
        $this->assertEquals(0, $stream->tell());
    }

    public function testTellFails()
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->close();
        $this->expectException(RuntimeException::class);
        $this->assertEquals(0, $stream->tell());
    }

    public function testEof()
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertTrue($stream->eof());
        $stream->seek(0);
        $this->assertFalse($stream->eof());
    }

    public function testIsSeekable()
    {
        $stream = $this->createStreamMock();
        $this->assertTrue($stream->isSeekable());
        $stream->close();
        $this->assertFalse($stream->isSeekable());
    }

    public function testRewind()
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->rewind();
        $this->assertEquals(0, $stream->tell());
    }

    public function testRewindFails()
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $this->assertEquals(0, $stream->rewind());
    }

    public function testWrite()
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        $this->assertEquals('Hello World', (string)$stream);
    }

    public function testWriteFails()
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $stream->write('Hello World');
    }

    public function testRead()
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        $stream->rewind();
        $this->assertEquals('Hello World', $stream->read($stream->getSize()));
        $stream->rewind();
        $this->assertEquals('Hello', $stream->read(5));
        $stream->seek(6);
        $this->assertEquals('World', $stream->read(5));
    }

    public function testReadFails()
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $stream->read(10);
    }

    public function testGetMetaData()
    {
        $stream = $this->createStreamMock();
        $this->assertTrue($stream->getMetadata('seekable'));
        $this->assertNull($stream->getMetadata('non-existent-key'));
        $stream->close();

        $this->assertNull($stream->getMetadata('size'));
    }

    private function createStreamMock()
    {
        $mock = new TestResourceStream();

        return $mock;
    }
}