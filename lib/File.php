<?php

namespace Zamzar;

/**
 * @property int $id
 * @property int $size
 * @property string $key
 * @property string $name
 * @property string $format
 * @property string $created_at
 */
class File extends ApiResource
{
    public function __toString()
    {
        return $this->id . '-' . $this->name;
    }

    /**
     * @param string $path Path to where the file should be downloaded. This can be a file, or a directory;
     * in the case of a directory, the file's name will be used.
     */
    public function download($path)
    {
        if (is_dir($path)) {
            $path = rtrim($path, '/') . '/' . $this->name;
        }

        $this->request('GET', $this->instanceUrl() . '/content', ['download_path' => $path], true);
        return $this;
    }

    public function delete()
    {
        $this->request('DELETE', $this->instanceUrl());
        return $this;
    }

    /**
     * Convert this file (which already exists on Zamzar)
     * Specify target_format in the params
     */
    public function convert($params)
    {
        $response = $this->request('POST', '/v1/jobs', array_merge($params, ['source_file' => $this->id]));
        return Job::constructFrom($response->getBody(), $this->config);
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
