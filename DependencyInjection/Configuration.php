<?php

namespace Timurib\Bundle\MailTemplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration structure
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('timurib_mail_template');
        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->isRequired()
                    ->useAttributeAsKey('code')
                    ->validate()
                        ->ifTrue(function ($templates) {
                            foreach (array_keys($templates) as $code) {
                                if (preg_match('#[^a-z0-9_]#i', $code)) {
                                    return true;
                                }
                            }
                            return false;
                        })
                        ->thenInvalid('Codes must contain only latin chars, digits and underscore symbols')
                    ->end()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('vars')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
