<?php

namespace Zamzar;

/**
 * Formats Object
 */
class Formats extends ApiResource
{
    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;
}
