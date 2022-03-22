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

class OutputStream extends ResourceStream
{
    /**
     * Creates a is a write-only stream that allows you to write to the output buffer
     * mechanism in the same way as print and echo.
     */
    public function __construct()
    {
        $handle = fopen('php://output', 'w');
        if ($handle === false) {
            throw new \Exception('Output stream could not be opened');
        }
        $this->handle = $handle;
    }

    public function getSize(): ?int
    {
        throw new \RuntimeException('Can not read the size of a write only stream.');
    }
}
