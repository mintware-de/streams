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
    public function testConstructor()
    {
        $stream = new FileStream(__FILE__, true, false);
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertGreaterThan(0, $stream->getSize());
        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $stream->close();

        $tempFile = tempnam(sys_get_temp_dir(), 'stream_test');
        $stream = new FileStream($tempFile, true, true);
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(0, $stream->getSize());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $stream->close();

        if (is_file($tempFile)) {
            @unlink($tempFile);
        }
    }

    public function testConstructorFailsNoFile()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('fopen(21312asd): failed to open stream: No such file or directory');
        new FileStream('21312asd', true, false);
    }
}