<?php

declare(strict_types=1);

namespace AntonEvers\MonorepoBuilderAdobeCommerce;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerPathNormalizerInterface;
use MonorepoBuilderPrefix202308\Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\Package\PackageComposerJsonMergerTest
 */
final class MapPathNormalizer implements ComposerPathNormalizerInterface
{
    /**
     * @var string[]
     */
    private const SECTIONS_WITH_PATH = ['map'];

    /**
     *
     */
    public function normalizePaths(ComposerJson $packageComposerJson, SmartFileInfo $packageFile): void
    {
        $extra = $this->normalizeExtraArray($packageFile, $packageComposerJson->getExtra());
        $packageComposerJson->setExtra($extra);
    }

    /**
     * @param array<string, mixed> $extraArray
     * @return array<string, mixed>
     */
    private function normalizeExtraArray(SmartFileInfo $packageFile, array $extraArray): array
    {
        foreach (self::SECTIONS_WITH_PATH as $sectionWithPath) {
            if (!isset($extraArray[$sectionWithPath])) {
                continue;
            }

            $extraArray[$sectionWithPath] = $this->relativizePath($extraArray[$sectionWithPath], $packageFile);
        }

        return $extraArray;
    }

    /**
     * @param mixed[] $patchesSubSection
     * @return mixed[]
     */
    private function relativizePath(array $patchesSubSection, SmartFileInfo $packageFileInfo): array
    {
        $packageRelativeDirectory = dirname($packageFileInfo->getRelativeFilePathFromDirectory(getcwd()));

        $mappings = [];

        foreach ($patchesSubSection as $key => $value) {
            if (is_array($value)) {
                $mappings[$key]['source'] = $this->relativizeSinglePath($packageRelativeDirectory, $patchesSubSection[$key][0]);
                $mappings[$key]['dest'] = $patchesSubSection[$key][1];
            }
        }

        return $mappings;
    }

    private function relativizeSinglePath(string $packageRelativeDirectory, string $path): string
    {
        return $packageRelativeDirectory . '/' . ltrim($path, '/');
    }
}
