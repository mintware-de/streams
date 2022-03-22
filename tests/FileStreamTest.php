<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace MintWare\Tests\Streams;

use MintWare\Streams\FileStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class FileStreamTest extends TestCase
{
    protected string $tempFile = '';

    protected function setUp(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'stream_test');
        if ($tempFile === false) {
            throw  new \Exception('Failed to get temp file');
        }
        $this->tempFile = $tempFile;
    }

    public function testConstructor(): void
    {
        $stream = new FileStream(__FILE__, true, false);
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertGreaterThan(0, $stream->getSize());
        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $stream->close();

        $stream = new FileStream($this->tempFile, true, true);
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $stream->close();
    }

    public function testConstructorFailsNoFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('fopen(21312asd):');
        new FileStream('21312asd', true, false);
    }

    protected function tearDown(): void
    {
        unlink($this->tempFile);
    }
}
