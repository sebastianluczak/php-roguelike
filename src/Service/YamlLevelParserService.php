<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\Yaml\Yaml;

class YamlLevelParserService
{
    protected string $yamlFilePath;
    private array $yamlProcessed;

    /**
     * @throws Exception
     */
    protected function processCurrentYamlFile(): void
    {
        if (!$this->yamlFilePath) {
            throw new Exception('Not file specified');
        }

        $this->yamlProcessed = (array) Yaml::parseFile($this->yamlFilePath);
    }

    public function setYamlFilePath(string $yamlFilePath): void
    {
        $this->yamlFilePath = $yamlFilePath;
    }

    // Push to higher service

    /**
     * @throws Exception
     */
    public function getMapName(): string
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['name'];
    }

    /**
     * @throws Exception
     */
    public function getMapHeight(): int
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['size']['height'];
    }

    /**
     * @throws Exception
     */
    public function getMapWidth(): int
    {
        $this->processCurrentYamlFile();

        return $this->yamlProcessed['size']['width'];
    }

    public function getRoomsDefinitions(): array
    {
        return $this->yamlProcessed['places'];
    }
}
