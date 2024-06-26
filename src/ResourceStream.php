<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019-2024 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace MintWare\Streams;

use Exception;
use LogicException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

abstract class ResourceStream implements StreamInterface
{
    /** @var resource */
    protected $handle = null;

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString(): string
    {
        if ($this->handle == null || $this->getSize() === 0) {
            return "";
        }

        if ($this->isSeekable()) {
            $this->seek(0);
        }
        return $this->getContents();
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->handle != null) {
            fclose($this->handle);
        }
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        fclose($this->handle);
        return $this->handle;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize(): ?int
    {
        $length = null;

        if ($this->handle != null && is_resource($this->handle)) {
            /** @var array{"size": string} $stat */
            $stat = fstat($this->handle);
            $length = (int)$stat['size'];
        }

        return $length;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws RuntimeException on error.
     */
    public function tell(): int
    {
        try {
            if ($this->handle === null || get_resource_type($this->handle) !== 'stream') {
                throw new Exception('ResourceStream::$handle must be a stream.', 2);
            }
            return ftell($this->handle) ?: 0;
        } catch (Exception $e) {
            throw new RuntimeException('', $e->getCode(), $e);
        }
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        return $this->tell() == $this->getSize();
    }

    /**
     * Returns whether the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        if (!is_resource($this->handle)) {
            return false;
        }
        return $this->getMetadata('seekable') === true;
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws RuntimeException on failure.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        try {
            if ($this->handle === null || get_resource_type($this->handle) !== 'stream') {
                throw new Exception('ResourceStream::$handle must be a stream.', 2);
            }
            fseek($this->handle, $offset, $whence);
        } catch (Exception $e) {
            throw new RuntimeException('', $e->getCode(), $e);
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @throws RuntimeException on failure.
     * @link http://www.php.net/manual/en/function.fseek.php
     * @see seek()
     */
    public function rewind(): void
    {
        try {
            if ($this->handle === null || get_resource_type($this->handle) !== 'stream') {
                throw new Exception('ResourceStream::$handle must be a stream.', 2);
            }
            rewind($this->handle);
        } catch (Exception $e) {
            throw new RuntimeException('', $e->getCode(), $e);
        }
    }

    /**
     * Returns whether the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        if (!is_resource($this->handle)) {
            return false;
        }
        $metadata = $this->getMetadata('mode');
        if (!is_string($metadata)) {
            return false;
        }
        return stristr($metadata, 'w') !== false;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws RuntimeException on failure.
     */
    public function write(string $string): int
    {
        try {
            if ($this->handle === null || get_resource_type($this->handle) !== 'stream') {
                throw new Exception('ResourceStream::$handle must be a stream.', 2);
            }
            return fwrite($this->handle, $string) ?: 0;
        } catch (Exception $e) {
            throw new RuntimeException('', $e->getCode(), $e);
        }
    }

    /**
     * Returns whether the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        if (!is_resource($this->handle)) {
            return false;
        }

        $mode = $this->getMetadata('mode');
        if (!is_string($mode)) {
            return false;
        }

        return stristr($mode, 'w+') !== false
            || stristr($mode, 'r') !== false;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return them.
     *     Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws RuntimeException if an error occurs.
     */
    public function read(int $length): string
    {
        try {
            if ($this->handle === null || get_resource_type($this->handle) !== 'stream') {
                throw new Exception('ResourceStream::$handle must be a stream.', 2);
            }
            if ($length <= 0) {
                throw new LogicException('Length must be positive.');
            }
            return fread($this->handle, $length) ?: '';
        } catch (Exception $e) {
            throw new RuntimeException('', $e->getCode(), $e);
        }
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents(): string
    {
        return $this->read($this->getSize() - $this->tell());
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null): mixed
    {
        if (!is_resource($this->handle) || $key === null) {
            return null;
        }

        $metaData = stream_get_meta_data($this->handle);
        return $metaData[$key] ?? null;
    }
}
