<?php

namespace Abbotton\BigXmlParser;

interface CallbackHandlerInterface
{
    public function onProgress($bytesProcessed, $bytesTotal);

    public function onItemParsed(array $item);
}