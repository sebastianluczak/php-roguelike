<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class YamlLevelParserService
{
    protected string $yamlFilePath;
    private array $yamlProcessed;

    protected function processCurrentYamlFile()
    {
        if (!$this->yamlFilePath) {
            throw new \Exception("Not file specified");
        }

        $this->yamlProcessed = Yaml::parseFile($this->yamlFilePath);
    }

    /**
     * @param string $yamlFilePath
     */
    public function setYamlFilePath(string $yamlFilePath): void
    {
        $this->yamlFilePath = $yamlFilePath;
    }

    // Push to higher service

    /**
     * @throws \Exception
     */
    public function getMapName()
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['name'];
    }

    public function getMapHeight()
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['size']['height'];
    }

    public function getMapWidth()
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['size']['width'];
    }

    public function getRoomsDefinitions()
    {
        return $this->yamlProcessed['places'];
    }
}