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

use Exception;
use MintWare\Streams\ResourceStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class TestResourceStream extends ResourceStream
{
    public function __construct()
    {
        $resource = fopen('php://temp', 'rw');
        if ($resource === false) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new Exception('Failed to open stream');
        }
        $this->handle = $resource;
    }
}

class ResourceStreamTest extends TestCase
{
    public function testConstruct(): void
    {
        $stream = $this->createStreamMock();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
    }

    public function test__ToString(): void
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertEquals('Hello World', (string)$stream);
    }

    public function testClose(): void
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->assertFalse($stream->isSeekable());
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    public function testDetach(): void
    {
        $stream = $this->createStreamMock();
        $stream->seek(0);
        $handle = $stream->detach();
        $this->assertTrue($handle !== null && get_resource_type($handle) !== 'stream');

        $this->expectException(RuntimeException::class);
        $stream->seek(0);
    }

    public function testGetSize(): void
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->getSize());
        $stream->close();
        $this->assertNull($stream->getSize());
    }

    public function testTell(): void
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->seek(0);
        $this->assertEquals(0, $stream->tell());
    }

    public function testTellFails(): void
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->close();
        $this->expectException(RuntimeException::class);
        $this->assertEquals(0, $stream->tell());
    }

    public function testEof(): void
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertTrue($stream->eof());
        $stream->seek(0);
        $this->assertFalse($stream->eof());
    }

    public function testIsSeekable(): void
    {
        $stream = $this->createStreamMock();
        $this->assertTrue($stream->isSeekable());
        $stream->close();
        $this->assertFalse($stream->isSeekable());
    }

    public function testRewind(): void
    {
        $stream = $this->createStreamMock();
        $stream->write('Hello World');
        $this->assertEquals(11, $stream->tell());
        $stream->rewind();
        $this->assertEquals(0, $stream->tell());
    }

    public function testRewindFails(): void
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $stream->rewind();
    }

    public function testWrite(): void
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertEquals('Hello World', (string)$stream);
    }

    public function testWriteFails(): void
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $stream->write('Hello World');
    }

    public function testRead(): void
    {
        $stream = $this->createStreamMock();
        $this->assertEquals('', (string)$stream);
        $stream->write('Hello World');
        $stream->rewind();
        $this->assertEquals('Hello World', $stream->read($stream->getSize() ?? 0));
        $stream->rewind();
        $this->assertEquals('Hello', $stream->read(5));
        $stream->seek(6);
        $this->assertEquals('World', $stream->read(5));
    }

    public function testReadFails(): void
    {
        $stream = $this->createStreamMock();
        $stream->close();
        $this->expectException(RuntimeException::class);
        $stream->read(10);
    }

    public function testGetMetaData(): void
    {
        $stream = $this->createStreamMock();
        $this->assertTrue($stream->getMetadata('seekable'));
        $this->assertNull($stream->getMetadata('non-existent-key'));
        $stream->close();

        $this->assertNull($stream->getMetadata('size'));
    }

    private function createStreamMock(): TestResourceStream
    {
        return new TestResourceStream();
    }
}
