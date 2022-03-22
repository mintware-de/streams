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

use Exception;
use RuntimeException;

class FileStream extends ResourceStream
{
    /**
     * Creates a stream which reads/writes from files
     *
     * @param string $filename The filename
     * @param bool $readable Defines if the stream is readable
     * @param bool $writable Defines if the stream is writable
     */
    public function __construct(string $filename, bool $readable = true, bool $writable = true)
    {
        $mode = '';
        if ($writable) {
            $mode = $readable ? 'w+' : 'w';
        } elseif ($readable) {
            $mode = 'r';
        }

        try {
            $handle = fopen($filename, $mode);
            if ($handle === false) {
                throw new Exception('File could not be opened');
            }
            $this->handle = $handle;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
