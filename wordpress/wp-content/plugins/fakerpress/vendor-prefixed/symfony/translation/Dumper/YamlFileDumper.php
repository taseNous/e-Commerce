<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Modified by Gustavo Bordoni on 22-April-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FakerPress\ThirdParty\Symfony\Component\Translation\Dumper;

use FakerPress\ThirdParty\Symfony\Component\Translation\Exception\LogicException;
use FakerPress\ThirdParty\Symfony\Component\Translation\MessageCatalogue;
use FakerPress\ThirdParty\Symfony\Component\Translation\Util\ArrayConverter;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlFileDumper generates yaml files from a message catalogue.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class YamlFileDumper extends FileDumper
{
    private $extension;

    public function __construct(string $extension = 'yml')
    {
        $this->extension = $extension;
    }

    /**
     * {@inheritdoc}
     */
    public function formatCatalogue(MessageCatalogue $messages, string $domain, array $options = [])
    {
        if (!class_exists(Yaml::class)) {
            throw new LogicException('Dumping translations in the YAML format requires the Symfony Yaml component.');
        }

        $data = $messages->all($domain);

        if (isset($options['as_tree']) && $options['as_tree']) {
            $data = ArrayConverter::expandToTree($data);
        }

        if (isset($options['inline']) && ($inline = (int) $options['inline']) > 0) {
            return Yaml::dump($data, $inline);
        }

        return Yaml::dump($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return $this->extension;
    }
}