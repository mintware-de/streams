<?php
/**
 * This file is part of the Streams package.
 *
 * Copyright 2019 by Julian Finkler <julian@mintware.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace MintWare\Streams;

/**
 * A MemoryStream, writes and reads data from/to php://memory
 * @package MintWare\Streams
 */
class MemoryStream extends ResourceStream
{
    /**
     * Creates a new stream which writes into memory
     * @param string|null $data Optional data which is written in the new created Stream
     */
    public function __construct(?string $data = null)
    {
        $resource = fopen('php://memory', 'rw');
        if ($resource === false) {
            throw new \RuntimeException('Failed to open memory stream.');
        }
        $this->handle = $resource;
        if ($data != null) {
            $this->write($data);
        }
    }
}
