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

class InputStream extends ResourceStream
{
    /**
     * Creates a read-only stream that allows you to read raw data from the request body.
     * @throws Exception If php://input could not be opened
     */
    public function __construct()
    {
        $handle = fopen('php://input', 'r');
        if ($handle === false) {
            throw new Exception('Input stream could not be opened');
        }
        $this->handle = $handle;
    }

    public function getSize(): ?int
    {
        return isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : null;
    }
}
