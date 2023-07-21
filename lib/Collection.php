<?php

namespace Zamzar;

/**
 * @template TZamzarObject of ZamzarObject
 *
 * @property TZamzarObject[] $data
 * @property null|array $paging
 */
class Collection extends ZamzarObject implements \Countable, \ArrayAccess, \IteratorAggregate
{
    public static function constructFrom(array $values, array $config, string $url = null, $templateClass = null)
    {
        if (!array_key_exists('data', $values)) {
            $values = ['data' => $values];
        }

        if ($url !== null) {
            $values['url'] = $url;
        }

        if ($templateClass !== null) {
            $values['template'] = $templateClass;
        }

        $class = $templateClass ?? ZamzarObject::class;
        $values['data'] = array_map(function ($obj) use ($class, $config) {
            return ($class)::constructFrom($obj, $config);
        }, $values['data']);

        return parent::constructFrom($values, $config);
    }

    public function hasMore()
    {
        return $this->paging['total_count'] > $this->count();
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->data);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        return;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        return;
    }

    /**
     * @return TZamzarObject
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @return \ArrayIterator|TZamzarObject[]
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function all($params = [])
    {
        $response = (new ApiRequestor($this->config))->request($this->url, 'GET', $params);

        return static::constructFrom($response->getBody(), $this->config, $this->url, $this->template);
    }

    /**
     * @return \Zamzar\Collection<TZamzarObject>
     */
    public function nextPage($params = [])
    {
        if (!$this->hasMore()) {
            return static::constructFrom([], $this->config, $this->url, $this->template);
        }

        $params = array_merge(
            ['limit' => $this->paging['limit']],
            ['after' => $this->paging['last']],
            $params
        );

        return $this->all($params);
    }

    /**
     * @return \Zamzar\Collection<TZamzarObject>
     */
    public function previousPage($params = [])
    {
        if (!$this->hasMore()) {
            return static::constructFrom([], $this->config, $this->url, $this->template);
        }

        $params = array_merge(
            ['limit' => $this->paging['limit']],
            ['before' => $this->paging['first']],
            $params
        );

        return $this->all($params);
    }
}
