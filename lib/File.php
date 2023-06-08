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
        return Job::constructFrom((array)$response->getBody(), $this->config);
    }

    /**
     * Get the value of id
     * @deprecated
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of key
     * @deprecated
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the value of name
     * @deprecated
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of size
     * @deprecated
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get the value of format
     * @deprecated
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get the value of created_at
     * @deprecated
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
